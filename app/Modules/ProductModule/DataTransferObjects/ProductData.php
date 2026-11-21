<?php

namespace App\Modules\ProductModule\DataTransferObjects;

final readonly class ProductData
{
    public function __construct(
        public int $id,
        public string $sku,
        public string $name,
        public string $unit,
        public int $stock,
        public int $minStock,
        public bool $isActive,
    ) {
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->minStock;
    }
}
