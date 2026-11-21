<?php

namespace App\Modules\ProductModule\Services;

use App\Modules\ProductModule\Contracts\ProductCatalogContract;
use App\Modules\ProductModule\DataTransferObjects\ProductData;
use App\Modules\ProductModule\Exceptions\InsufficientStockException;
use App\Modules\ProductModule\Models\Product;
use Illuminate\Support\Collection;

class ProductCatalogService implements ProductCatalogContract
{
    public function find(int $productId): ?ProductData
    {
        $product = Product::withTrashed()->with('unit')->find($productId);

        return $product === null ? null : $this->toData($product);
    }

    public function findMany(array $productIds): Collection
    {
        return Product::withTrashed()
            ->with('unit')
            ->whereKey($productIds)
            ->get()
            ->mapWithKeys(fn (Product $product) => [$product->id => $this->toData($product)]);
    }

    public function activeProducts(): Collection
    {
        return Product::query()
            ->with('unit')
            ->active()
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product) => $this->toData($product))
            ->values();
    }

    public function allProducts(): Collection
    {
        return Product::query()
            ->with('unit')
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product) => $this->toData($product))
            ->values();
    }

    public function increaseStock(int $productId, int $quantity): int
    {
        $product = Product::query()->lockForUpdate()->findOrFail($productId);
        $product->increment('stock', $quantity);

        return $product->stock;
    }

    public function decreaseStock(int $productId, int $quantity): int
    {
        $product = Product::query()->lockForUpdate()->findOrFail($productId);

        if ($product->stock < $quantity) {
            throw new InsufficientStockException($product->name, $product->stock, $quantity);
        }

        $product->decrement('stock', $quantity);

        return $product->stock;
    }

    public function summary(): array
    {
        return [
            'total' => Product::query()->count(),
            'active' => Product::query()->active()->count(),
            'low_stock' => Product::query()->active()->whereColumn('stock', '<=', 'min_stock')->count(),
            'total_units' => (int) Product::query()->sum('stock'),
        ];
    }

    public function lowStockProducts(int $limit = 5): Collection
    {
        return Product::query()
            ->with('unit')
            ->active()
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->limit($limit)
            ->get()
            ->map(fn (Product $product) => $this->toData($product))
            ->values();
    }

    private function toData(Product $product): ProductData
    {
        return new ProductData(
            id: $product->id,
            sku: $product->sku,
            name: $product->name,
            unit: $product->unit?->symbol ?? '',
            stock: $product->stock,
            minStock: $product->min_stock,
            isActive: $product->is_active && ! $product->trashed(),
        );
    }
}
