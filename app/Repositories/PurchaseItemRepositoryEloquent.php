<?php

namespace App\Repositories;

use App\Models\PurchaseItem;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PurchaseItemRepository;
use App\Validators\PurchaseItemValidator;

/**
 * Class PurchaseItemRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PurchaseItemRepositoryEloquent extends BaseRepository implements PurchaseItemRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PurchaseItem::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
