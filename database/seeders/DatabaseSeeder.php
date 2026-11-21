<?php

namespace Database\Seeders;

use App\Modules\AuthModule\Database\Seeders\AuthModuleSeeder;
use App\Modules\InventoryModule\Database\Seeders\InventoryModuleSeeder;
use App\Modules\ProductModule\Database\Seeders\ProductModuleSeeder;
use App\Modules\SupplierModule\Database\Seeders\SupplierModuleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AuthModuleSeeder::class,
            ProductModuleSeeder::class,
            SupplierModuleSeeder::class,
            InventoryModuleSeeder::class,
        ]);
    }
}
