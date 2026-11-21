<?php

namespace App\Modules\InventoryModule\Http\Controllers;

use App\Modules\InventoryModule\Http\Requests\StockCardFilterRequest;
use App\Modules\InventoryModule\Models\StockCard;
use App\Modules\ProductModule\Contracts\ProductCatalogContract;
use Illuminate\View\View;

class StockCardController
{
    public function __construct(private readonly ProductCatalogContract $products)
    {
    }

    public function index(StockCardFilterRequest $request): View
    {
        $filters = $request->validated();

        $cards = StockCard::query()
            ->when($filters['product_id'] ?? null, fn ($query, $id) => $query->where('product_id', $id))
            ->when($filters['from'] ?? null, fn ($query, $date) => $query->whereDate('occurred_at', '>=', $date))
            ->when($filters['to'] ?? null, fn ($query, $date) => $query->whereDate('occurred_at', '<=', $date))
            ->orderByDesc('occurred_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('inventory::stock-cards.index', [
            'cards' => $cards,
            'filters' => $filters,
            'productOptions' => $this->products->allProducts(),
            'productNames' => $this->products->findMany($cards->pluck('product_id')->unique()->all()),
        ]);
    }
}
