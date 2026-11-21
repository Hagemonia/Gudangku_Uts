<?php

namespace App\Modules\ProductModule\Contracts;

use App\Modules\ProductModule\DataTransferObjects\ProductData;
use Illuminate\Support\Collection;

interface ProductCatalogContract
{
    public function find(int $productId): ?ProductData;

    public function findMany(array $productIds): Collection;

    public function activeProducts(): Collection;

    public function allProducts(): Collection;

    public function increaseStock(int $productId, int $quantity): int;

    public function decreaseStock(int $productId, int $quantity): int;

    public function summary(): array;

    public function lowStockProducts(int $limit = 5): Collection;
}
