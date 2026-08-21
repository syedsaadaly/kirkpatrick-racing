<?php

namespace App\Providers;

use App\Models\Page;
use Cart;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('admin.layouts.partials.sidebar', function ($view) {
            $view->with('pages', Page::cms()->get());
            $view->with('modules', Page::module()->get());
        });

        View::composer('front.include.header', function ($view) {
            $cartUserId = auth()->check() ? auth()->id() : session()->getId();
            $view->with('cartCount', Cart::session($cartUserId)->getTotalQuantity());
        });
    }
}
