<?php

namespace App\Modules\SupplierModule\Exceptions;

use App\Support\BusinessException;

class PaymentTermInUseException extends BusinessException
{
    public function __construct(string $termName)
    {
        parent::__construct("Termin {$termName} masih dipakai oleh pemasok dan tidak bisa dihapus.");
    }
}
