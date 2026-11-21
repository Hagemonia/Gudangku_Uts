<?php

namespace App\Modules\InventoryModule\Database\Seeders;

use App\Modules\InventoryModule\Enums\StockOutReason;
use App\Modules\InventoryModule\Services\StockInService;
use App\Modules\InventoryModule\Services\StockOutService;
use App\Modules\ProductModule\Contracts\ProductCatalogContract;
use App\Modules\ProductModule\DataTransferObjects\ProductData;
use App\Modules\SupplierModule\Contracts\SupplierDirectoryContract;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class InventoryModuleSeeder extends Seeder
{
    public function run(
        StockInService $stockIn,
        StockOutService $stockOut,
        ProductCatalogContract $products,
        SupplierDirectoryContract $suppliers,
    ): void {
        $admin = Auth::createUserProvider('users')?->retrieveByCredentials(['email' => 'admin@gudang.test']);

        if ($admin === null) {
            return;
        }

        $userId = (int) $admin->getAuthIdentifier();
        $userName = (string) $admin->name;
        $supplierIds = $suppliers->options()->pluck('id')->values();

        if ($supplierIds->isEmpty()) {
            return;
        }

        $products->activeProducts()->chunk(5)->values()->each(
            function (Collection $chunk, int $index) use ($stockIn, $supplierIds, $userId, $userName) {
                $stockIn->record([
                    'supplier_id' => $supplierIds[$index % $supplierIds->count()],
                    'received_at' => now()->subDays(14 - $index)->toDateString(),
                    'notes' => 'Stok awal gudang',
                    'items' => $chunk->map(fn (ProductData $product) => [
                        'product_id' => $product->id,
                        'quantity' => 30 + (($product->id * 17) % 70),
                        'unit_cost' => 5000 + (($product->id * 13) % 40) * 2500,
                    ])->values()->all(),
                ], $userId, $userName);
            }
        );

        $current = $products->activeProducts()->values();

        $stockOut->record([
            'issued_at' => now()->subDays(3)->toDateString(),
            'reason' => StockOutReason::Sale->value,
            'notes' => 'Pesanan pelanggan',
            'items' => $current->take(3)->map(fn (ProductData $product) => [
                'product_id' => $product->id,
                'quantity' => max(1, $product->stock - $product->minStock),
            ])->values()->all(),
        ], $userId, $userName);

        $stockOut->record([
            'issued_at' => now()->subDay()->toDateString(),
            'reason' => StockOutReason::Usage->value,
            'notes' => 'Kebutuhan operasional kantor',
            'items' => $current->slice(5, 4)->map(fn (ProductData $product) => [
                'product_id' => $product->id,
                'quantity' => 5,
            ])->values()->all(),
        ], $userId, $userName);
    }
}
