<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface CategoryRepository.
 *
 * @package namespace App\Repositories;
 */
interface CategoryRepository extends RepositoryInterface
{
    public function createCategory(array $data);
    public function updateCategory(array $data, $id);
    public function deleteCategory($id);
    public function getParentCategories($excludeId = null);
    public function getCategoriesWithProperHierarchy();
    public function bulkAction($action, $ids);
}
