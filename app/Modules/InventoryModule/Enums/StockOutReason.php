<?php

namespace App\Modules\InventoryModule\Enums;

enum StockOutReason: string
{
    case Sale = 'penjualan';
    case Usage = 'pemakaian';
    case Damaged = 'rusak';
    case Transfer = 'transfer';
    case Other = 'lainnya';

    public function label(): string
    {
        return match ($this) {
            self::Sale => 'Penjualan',
            self::Usage => 'Pemakaian internal',
            self::Damaged => 'Rusak / kedaluwarsa',
            self::Transfer => 'Transfer antar gudang',
            self::Other => 'Lainnya',
        };
    }
}
