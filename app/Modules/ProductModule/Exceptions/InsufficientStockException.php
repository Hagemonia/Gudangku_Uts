<?php

namespace App\Modules\ProductModule\Exceptions;

use App\Support\BusinessException;

class InsufficientStockException extends BusinessException
{
    public function __construct(
        public readonly string $productName,
        public readonly int $available,
        public readonly int $requested,
    ) {
        parent::__construct(
            $available === 0
                ? "Stok {$productName} habis, tidak bisa dikeluarkan."
                : "Stok {$productName} tidak cukup. Tersedia {$available}, diminta {$requested}."
        );
    }
}
