<?php

namespace App\Modules\SupplierModule\DataTransferObjects;

final readonly class SupplierData
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public bool $isActive,
    ) {
    }
}
