<?php

namespace App\Modules\InventoryModule\Http\Controllers;

use App\Modules\InventoryModule\Models\StockCard;
use App\Modules\InventoryModule\Models\StockIn;
use App\Modules\InventoryModule\Models\StockOut;
use App\Modules\ProductModule\Contracts\ProductCatalogContract;
use App\Modules\SupplierModule\Contracts\SupplierDirectoryContract;
use Illuminate\View\View;

class DashboardController
{
    public function __construct(
        private readonly ProductCatalogContract $products,
        private readonly SupplierDirectoryContract $suppliers,
    ) {
    }

    public function __invoke(): View
    {
        $recent = StockCard::query()->orderByDesc('id')->limit(8)->get();

        return view('inventory::dashboard', [
            'summary' => $this->products->summary(),
            'lowStock' => $this->products->lowStockProducts(),
            'supplierCount' => $this->suppliers->activeCount(),
            'stockInToday' => StockIn::query()->whereDate('received_at', today())->count(),
            'stockOutToday' => StockOut::query()->whereDate('issued_at', today())->count(),
            'recent' => $recent,
            'productNames' => $this->products->findMany($recent->pluck('product_id')->unique()->all()),
        ]);
    }
}
