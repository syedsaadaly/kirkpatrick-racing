<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Variation;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productRepository;
    protected $categoryRepository;

    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $products = $this->productRepository->getProductsWithRelations();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $allCategories = Category::orderBy('name', 'asc')->get();
        $categories = $this->categoryRepository->buildCategoryTree($allCategories);
        $variations    = Variation::with('options')->where('is_active', true)->get();

        return view('admin.products.create', compact('categories', 'variations'));
    }

    public function store(ProductStoreRequest $request)
    {
        try {
            $this->productRepository->createProduct($request->validated());
            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $product = $this->productRepository->find($id);
        $allCategories = Category::orderBy('name', 'asc')->get();
        $categories = $this->categoryRepository->buildCategoryTree($allCategories);
        $variations = Variation::with('options')->where('is_active', true)->get();

        $product->load('variations.variationOptions');

        return view('admin.products.edit', compact('product', 'categories', 'variations'));
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        try {
            $data = array_merge(
                $request->validated(),
                [
                    'is_active'          => $request->has('is_active'),
                    'is_featured'        => $request->has('is_featured'),
                    'variations'         => $request->input('variations', []),
                    'deleted_variations' => $request->input('deleted_variations', []),
                ]
            );

            $this->productRepository->updateProduct($data, $id);
            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->productRepository->deleteProduct($id);
            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'ids' => 'required|string'
        ]);

        try {
            $this->productRepository->bulkAction($request->action, $request->ids);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
