<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Setting;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Cart;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cartUserId = auth()->check() ? auth()->id() : session()->getId();
        $cart = Cart::session($cartUserId);
        $total = $cart->getTotal();

        if ($cart->isEmpty()) {
            return redirect()->route('cart.view');
        }

        $stripeKeys = Setting::stripeKeys();

        if (!$stripeKeys['enabled']) {
            return redirect()->route('cart.view')->with('error', 'Online payment is currently unavailable. Please try again later.');
        }

        Stripe::setApiKey($stripeKeys['secret']);

        $paymentIntent = PaymentIntent::create([
            'amount' => $total * 100,
            'currency' => 'usd',
            'metadata' => ['user_id' => auth()->id() ? (string) auth()->id() : 'guest'],
        ]);

        return view('ecommerce.checkout', [
            'cartItems' => $cart->getContent(),
            'total' => $total,
            'clientSecret' => $paymentIntent->client_secret,
            'stripeKey' => $stripeKeys['key'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone' => 'required|string',
            'country' => 'required|string|size:2',
            'payment_intent_id' => 'required'
        ]);

        $cartUserId = auth()->check() ? auth()->id() : session()->getId();
        $cart = Cart::session($cartUserId);

        if ($cart->isEmpty()) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        $stripeKeys = Setting::stripeKeys();

        if (!$stripeKeys['enabled']) {
            return redirect()->route('cart.view')->with('error', 'Online payment is currently unavailable. Please try again later.');
        }

        Stripe::setApiKey($stripeKeys['secret']);

        try {
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to verify payment: ' . $e->getMessage())->withInput();
        }

        if ($paymentIntent->status !== 'succeeded') {
            return redirect()->back()->with('error', 'Payment was not completed successfully.')->withInput();
        }

        try {
            return DB::transaction(function () use ($request, $cart) {
                foreach ($cart->getContent() as $item) {
                    $cleanId = (int) preg_replace('/[^0-9]/', '', $item->id);
                    $isVariation = isset($item->attributes['variation_id']);

                    $stockable = $isVariation
                        ? ProductVariation::lockForUpdate()->find($cleanId)
                        : Product::lockForUpdate()->find($cleanId);

                    if (!$stockable) {
                        throw new Exception("{$item->name} is no longer available.");
                    }

                    if ($stockable->manage_stock && $stockable->stock_quantity < $item->quantity) {
                        throw new Exception("{$item->name} only has {$stockable->stock_quantity} left in stock.");
                    }
                }

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                    'total_amount' => $cart->getTotal(),
                    'status' => 'paid',
                    'payment_id' => $request->payment_intent_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'country' => $request->country,
                ]);

                foreach ($cart->getContent() as $item) {
                    $cleanId = (int) preg_replace('/[^0-9]/', '', $item->id);
                    $isVariation = isset($item->attributes['variation_id']);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $isVariation ? null : $cleanId,
                        'variation_id' => $isVariation ? $cleanId : null,
                        'product_name' => $item->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'attributes' => $item->attributes,
                    ]);

                    $stockable = $isVariation
                        ? ProductVariation::find($cleanId)
                        : Product::find($cleanId);

                    if ($stockable->manage_stock) {
                        $stockable->decrement('stock_quantity', $item->quantity);
                    }
                }

                $cart->clear();

                return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
