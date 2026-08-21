<?php

namespace App\Repositories;

use App\Models\Category;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\CategoryRepository;
use App\Validators\CategoryValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class CategoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

    /**
     * Create a new category with auto-slug generation
     */
    public function createCategory(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['slug']) && !empty($data['name'])) {
                $data['slug'] = $this->generateUniqueSlug($data['name']);
            }

            if (isset($data['parent_id']) && empty($data['parent_id'])) {
                $data['parent_id'] = null;
            }

            $category = parent::create($data);

            if (request()->hasFile('image')) {
                $category->clearMediaCollection('categories');
                $category->addMediaFromRequest('image')->toMediaCollection('categories');
            }

            return $category;
        });
    }

    /**
     * Update category with proper slug handling
     */
    public function updateCategory(array $data, $id)
    {
        return DB::transaction(function () use ($data, $id) {
            $category = $this->find($id);

            if (isset($data['parent_id']) && $data['parent_id'] == $category->id) {
                throw new \Exception('Category cannot be its own parent.');
            }

            if (!empty($data['name']) && empty($data['slug'])) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $category->id);
            }

            $category = parent::update($data, $id);

            if (request()->has('remove_image')) {
                $category->clearMediaCollection('categories');
            }

            if (request()->hasFile('image')) {
                $category->clearMediaCollection('categories');
                $category->addMediaFromRequest('image')->toMediaCollection('categories');
            }

            return $category;
        });
    }

    /**
     * Delete category with checks
     */
    public function deleteCategory($id)
    {
        return DB::transaction(function () use ($id) {
            $category = $this->find($id);

            $category->clearMediaCollection('categories');

            return parent::delete($id);
        });
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        $query = Category::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;

            $query = Category::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Get categories
     */
    public function getParentCategories($excludeId = null)
    {
        $query = Category::active()->orderBy('name');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get();
    }

    /**
     * Get all categories with hierarchy
     */
    public function getCategoriesWithProperHierarchy()
    {
        $categories = Category::with(['parent', 'children', 'media'])
            ->withCount('children')
            ->orderBy('name')
            ->get();

        return $this->buildHierarchy($categories);
    }

    /**
     * Build hierarchical tree from flat categories array
     */
    private function buildHierarchy($categories, $parentId = null, $level = 0)
    {
        $hierarchy = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $category->level = $level;
                $hierarchy[] = $category;

                $children = $this->buildHierarchy($categories, $category->id, $level + 1);
                $hierarchy = array_merge($hierarchy, $children);
            }
        }

        return $hierarchy;
    }

    /**
     * Bulk actions
     */
    public function bulkAction($action, $ids)
    {
        return DB::transaction(function () use ($action, $ids) {
            $categoryIds = explode(',', $ids);

            switch ($action) {
                case 'activate':
                    Category::whereIn('id', $categoryIds)->update(['is_active' => true]);
                    break;

                case 'deactivate':
                    Category::whereIn('id', $categoryIds)->update(['is_active' => false]);
                    break;

                case 'delete':
                    $categories = Category::whereIn('id', $categoryIds)->get();
                    foreach ($categories as $category) {
                        $category->clearMediaCollection('categories');
                        $category->delete();
                    }
                    break;

                default:
                    throw new \Exception('Invalid action');
            }

            return true;
        });
    }

    public function buildCategoryTree($categories, $parentId = null, $level = 0)
    {
        $tree = collect();

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $category->level = $level;
                $tree->push($category);

                $children = $this->buildCategoryTree($categories, $category->id, $level + 1);
                $tree = $tree->merge($children);
            }
        }

        return $tree;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
