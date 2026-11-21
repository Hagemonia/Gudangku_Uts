<?php

namespace App\Modules\SupplierModule\Contracts;

use App\Modules\SupplierModule\DataTransferObjects\SupplierData;
use Illuminate\Support\Collection;

interface SupplierDirectoryContract
{
    public function find(int $supplierId): ?SupplierData;

    public function options(): Collection;

    public function activeCount(): int;
}
