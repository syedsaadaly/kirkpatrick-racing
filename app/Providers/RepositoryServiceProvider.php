<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryEloquent;
use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryEloquent;
use App\Repositories\PurchaseRepository;
use App\Repositories\PurchaseRepositoryEloquent;
use App\Repositories\UnitRepository;
use App\Repositories\UnitRepositoryEloquent;
use App\Repositories\VariationRepository;
use App\Repositories\VariationRepositoryEloquent;
use App\Repositories\VendorRepository;
use App\Repositories\VendorRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CategoryRepository::class,
            CategoryRepositoryEloquent::class
        );

        $this->app->bind(
            ProductRepository::class,
            ProductRepositoryEloquent::class
        );

        $this->app->bind(
            VariationRepository::class,
            VariationRepositoryEloquent::class
        );

        $this->app->bind(
            UnitRepository::class,
            UnitRepositoryEloquent::class
        );

        $this->app->bind(
            VendorRepository::class,
            VendorRepositoryEloquent::class
        );

        $this->app->bind(
            PurchaseRepository::class,
            PurchaseRepositoryEloquent::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
