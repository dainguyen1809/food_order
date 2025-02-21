<?php

namespace App\Providers;

use App\Services\Product\DrinkService;
use App\Services\Product\FoodService;
use App\Services\Product\ProductStrategy;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        ProductStrategy::registerProductType('food', FoodService::class);
        ProductStrategy::registerProductType('drink', DrinkService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
