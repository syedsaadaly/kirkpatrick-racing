<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryBulkActionRequest;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        try {
            $categories = $this->categoryRepository->getCategoriesWithProperHierarchy();
            return view('admin.categories.index', compact('categories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load categories: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        try {
            $parentCategories = $this->categoryRepository->getParentCategories();
            return view('admin.categories.create', compact('parentCategories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load create form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $category = $this->categoryRepository->createCategory($validated);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create category: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        try {
            $category = $this->categoryRepository->find($id);
            $parentCategories = $this->categoryRepository->getParentCategories($id);

            return view('admin.categories.edit', compact('category', 'parentCategories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified category in storage.
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $validated = $request->validated();
            $category = $this->categoryRepository->updateCategory($validated, $id);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update category: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        try {
            $this->categoryRepository->deleteCategory($id);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions
     */
    public function bulkAction(CategoryBulkActionRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->categoryRepository->bulkAction($validated['action'], $validated['ids']);

            $message = match($validated['action']) {
                'activate' => 'Selected categories have been activated successfully!',
                'deactivate' => 'Selected categories have been deactivated successfully!',
                'delete' => 'Selected categories have been deleted successfully!',
                default => 'Action completed successfully!'
            };

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action: ' . $e->getMessage()
            ], 500);
        }
    }
}
