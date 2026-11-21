<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Modules\AuthModule\Providers\AuthModuleServiceProvider::class,
    App\Modules\ProductModule\Providers\ProductModuleServiceProvider::class,
    App\Modules\SupplierModule\Providers\SupplierModuleServiceProvider::class,
    App\Modules\InventoryModule\Providers\InventoryModuleServiceProvider::class,
];
