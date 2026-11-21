<?php

namespace App\Modules\SupplierModule\Services;

use App\Modules\SupplierModule\Contracts\SupplierDirectoryContract;
use App\Modules\SupplierModule\DataTransferObjects\SupplierData;
use App\Modules\SupplierModule\Models\Supplier;
use Illuminate\Support\Collection;

class SupplierDirectoryService implements SupplierDirectoryContract
{
    public function find(int $supplierId): ?SupplierData
    {
        $supplier = Supplier::withTrashed()->find($supplierId);

        return $supplier === null ? null : $this->toData($supplier);
    }

    public function options(): Collection
    {
        return Supplier::query()
            ->active()
            ->orderBy('name')
            ->get()
            ->map(fn (Supplier $supplier) => $this->toData($supplier))
            ->values();
    }

    public function activeCount(): int
    {
        return Supplier::query()->active()->count();
    }

    private function toData(Supplier $supplier): SupplierData
    {
        return new SupplierData(
            id: $supplier->id,
            code: $supplier->code,
            name: $supplier->name,
            isActive: $supplier->is_active && ! $supplier->trashed(),
        );
    }
}
