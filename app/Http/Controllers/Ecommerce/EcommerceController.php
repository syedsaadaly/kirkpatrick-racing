<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Cart;

class EcommerceController extends Controller
{
    public function productListing(Request $request)
    {
        $query = Product::with([
            'categories',
            'variations' => function ($q) {
                $q->where('is_active', true);
            },
            'variations.variationOptions.attribute',
        ])->where('is_active', true);

        $selectedCategory = null;

        if ($request->filled('category')) {
            $selectedCategory = Category::active()->where('slug', $request->category)->first();

            $categorySlugs = [$request->category];
            if ($selectedCategory) {
                $categorySlugs = array_merge($categorySlugs, $selectedCategory->children()->active()->pluck('slug')->toArray());
            }

            $query->whereHas('categories', function ($q) use ($categorySlugs) {
                $q->whereIn('slug', $categorySlugs);
            });
        }

        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, base_price) asc');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, base_price) desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->whereNull('parent_id')->withCount('products')->with(['children' => function ($q) {
            $q->active()->orderBy('name');
        }])->orderBy('name')->get();

        return view('front.shop', compact('products', 'categories', 'selectedCategory'));
    }

    public function productDetails(string $slug)
    {
        $product = Product::with([
            'categories',
            'media',
            'variations' => function ($query) {
                $query->where('is_active', true);
            },
            'variations.variationOptions.attribute'
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('front.product-details', compact('product'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|numeric|min:1',
        ]);

        $cartUserId = auth()->check() ? auth()->id() : session()->getId();
        $cart = Cart::session($cartUserId);

        try {
            if ($request->filled('product_variation_id')) {
                $variation = ProductVariation::with('product')->findOrFail($request->product_variation_id);

                $uniqueId = 'var_' . $variation->id;
                $name = $variation->product->name;
                $price = $variation->sale_price ?? $variation->price;

                $imageUrl = $variation->getFirstMediaUrl('products') ?: $variation->product->getFirstMediaUrl('products');

                $attributes = [
                    'variation_id' => $variation->id,
                    'sku' => $variation->sku,
                    'image' => $imageUrl,
                    'details' => $variation->variation_name,
                ];

                $stockQuantity = $variation->stock_quantity;
                $manageStock = $variation->manage_stock;
            } else {
                $product = Product::findOrFail($request->product_id);

                $uniqueId = 'prod_' . $product->id;
                $name = $product->name;
                $price = $product->sale_price ?? $product->base_price;

                $attributes = [
                    'sku' => $product->sku,
                    'image' => $product->getFirstMediaUrl('products'),
                    'details' => null
                ];

                $stockQuantity = $product->stock_quantity;
                $manageStock = $product->manage_stock;
            }

            if ($manageStock) {
                $existingItem = $cart->get($uniqueId);
                $alreadyInCart = $existingItem ? $existingItem->quantity : 0;

                if (($alreadyInCart + (int) $request->qty) > $stockQuantity) {
                    $remaining = max(0, $stockQuantity - $alreadyInCart);
                    return redirect()->back()->with(
                        'error',
                        $remaining > 0
                            ? "Only {$remaining} left in stock."
                            : 'This item is out of stock.'
                    );
                }
            }

            $cart->add([
                'id' => $uniqueId,
                'name' => $name,
                'price' => (float) $price,
                'quantity' => (int) $request->qty,
                'attributes' => $attributes,
            ]);

            return redirect()->route('cart.view')->with('success', 'Item added to your cart!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function viewCart()
    {
        $cartUserId = auth()->check() ? auth()->id() : session()->getId();
        $cartItems = Cart::session($cartUserId)->getContent()->sortBy(fn ($item) => $item->id);
        $cartTotal = Cart::session($cartUserId)->getTotal();

        return view('ecommerce.cart-view', compact('cartItems', 'cartTotal'));
    }

    public function update(Request $request)
    {
        $cartUserId = auth()->check() ? auth()->id() : session()->getId();
        $cart = Cart::session($cartUserId);

        $item = $cart->get($request->id);

        if ($item) {
            $variationId = $item->attributes['variation_id'] ?? null;
            $stockable = $variationId
                ? ProductVariation::find($variationId)
                : Product::find((int) preg_replace('/[^0-9]/', '', $request->id));

            if ($stockable && $stockable->manage_stock && $request->quantity > $stockable->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$stockable->stock_quantity} left in stock.",
                ], 422);
            }
        }

        Cart::session($cartUserId)->update($request->id, [
            'quantity' => [
                'relative' => false,
                'value' => $request->quantity
            ],
        ]);

        return response()->json(['success' => true]);
    }

    public function remove($id)
    {
        $cartUserId = auth()->check() ? auth()->id() : session()->getId();
        Cart::session($cartUserId)->remove($id);

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}
