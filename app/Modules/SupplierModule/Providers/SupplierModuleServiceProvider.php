<?php

namespace App\Modules\SupplierModule\Providers;

use App\Modules\SupplierModule\Contracts\SupplierDirectoryContract;
use App\Modules\SupplierModule\Services\SupplierDirectoryService;
use Illuminate\Support\ServiceProvider;

class SupplierModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SupplierDirectoryContract::class, SupplierDirectoryService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'supplier');
    }
}
