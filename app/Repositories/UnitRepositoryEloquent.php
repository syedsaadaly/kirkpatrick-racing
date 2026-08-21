<?php

namespace App\Repositories;

use App\Models\Unit;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UnitRepository;
use App\Validators\UnitValidator;

/**
 * Class UnitRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UnitRepositoryEloquent extends BaseRepository implements UnitRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Unit::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
