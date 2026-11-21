<?php

namespace App\Modules\InventoryModule\Rules;

use App\Modules\ProductModule\Contracts\ProductCatalogContract;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ActiveProduct implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $product = app(ProductCatalogContract::class)->find((int) $value);

        if ($product === null || ! $product->isActive) {
            $fail('Barang yang dipilih tidak ditemukan atau sudah nonaktif.');
        }
    }
}
