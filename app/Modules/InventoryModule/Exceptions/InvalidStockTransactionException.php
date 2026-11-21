<?php

namespace App\Modules\InventoryModule\Exceptions;

use App\Support\BusinessException;

class InvalidStockTransactionException extends BusinessException
{
    public static function inactiveProduct(string $name): self
    {
        return new self("Barang {$name} tidak ditemukan atau sudah nonaktif, transaksi dibatalkan.");
    }

    public static function inactiveSupplier(): self
    {
        return new self('Pemasok tidak ditemukan atau sudah nonaktif, transaksi dibatalkan.');
    }
}
