<?php

namespace App\Modules\ProductModule\Providers;

use App\Modules\ProductModule\Contracts\ProductCatalogContract;
use App\Modules\ProductModule\Services\ProductCatalogService;
use Illuminate\Support\ServiceProvider;

class ProductModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductCatalogContract::class, ProductCatalogService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'product');
    }
}
