<?php

namespace App\Modules\InventoryModule\Http\Controllers;

use App\Modules\InventoryModule\Http\Requests\StockInRequest;
use App\Modules\InventoryModule\Models\StockIn;
use App\Modules\InventoryModule\Services\StockInService;
use App\Modules\ProductModule\Contracts\ProductCatalogContract;
use App\Modules\SupplierModule\Contracts\SupplierDirectoryContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StockInController
{
    public function __construct(
        private readonly StockInService $service,
        private readonly ProductCatalogContract $products,
        private readonly SupplierDirectoryContract $suppliers,
    ) {
    }

    public function index(): View
    {
        $stockIns = StockIn::query()
            ->withCount('items')
            ->withSum('items', 'quantity')
            ->orderByDesc('received_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('inventory::stock-ins.index', ['stockIns' => $stockIns]);
    }

    public function create(): View
    {
        return view('inventory::stock-ins.create', [
            'products' => $this->products->activeProducts(),
            'suppliers' => $this->suppliers->options(),
        ]);
    }

    public function store(StockInRequest $request): RedirectResponse
    {
        $stockIn = $this->service->record($request->validated(), $request->user()->id, $request->user()->name);

        return redirect()
            ->route('stock-ins.show', $stockIn)
            ->with('success', "Stok masuk {$stockIn->code} berhasil dicatat.");
    }

    public function show(StockIn $stockIn): View
    {
        return view('inventory::stock-ins.show', ['stockIn' => $stockIn->load('items')]);
    }
}
