<?php

namespace App\Modules\InventoryModule\Rules;

use App\Modules\SupplierModule\Contracts\SupplierDirectoryContract;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ActiveSupplier implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $supplier = app(SupplierDirectoryContract::class)->find((int) $value);

        if ($supplier === null || ! $supplier->isActive) {
            $fail('Pemasok yang dipilih tidak ditemukan atau sudah nonaktif.');
        }
    }
}
