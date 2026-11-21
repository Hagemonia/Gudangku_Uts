<?php

namespace App\Modules\InventoryModule\Http\Controllers;

use App\Modules\InventoryModule\Enums\StockOutReason;
use App\Modules\InventoryModule\Http\Requests\StockOutRequest;
use App\Modules\InventoryModule\Models\StockOut;
use App\Modules\InventoryModule\Services\StockOutService;
use App\Modules\ProductModule\Contracts\ProductCatalogContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StockOutController
{
    public function __construct(
        private readonly StockOutService $service,
        private readonly ProductCatalogContract $products,
    ) {
    }

    public function index(): View
    {
        $stockOuts = StockOut::query()
            ->withCount('items')
            ->withSum('items', 'quantity')
            ->orderByDesc('issued_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('inventory::stock-outs.index', ['stockOuts' => $stockOuts]);
    }

    public function create(): View
    {
        return view('inventory::stock-outs.create', [
            'products' => $this->products->activeProducts(),
            'reasons' => StockOutReason::cases(),
        ]);
    }

    public function store(StockOutRequest $request): RedirectResponse
    {
        $stockOut = $this->service->record($request->validated(), $request->user()->id, $request->user()->name);

        return redirect()
            ->route('stock-outs.show', $stockOut)
            ->with('success', "Stok keluar {$stockOut->code} berhasil dicatat.");
    }

    public function show(StockOut $stockOut): View
    {
        return view('inventory::stock-outs.show', ['stockOut' => $stockOut->load('items')]);
    }
}
