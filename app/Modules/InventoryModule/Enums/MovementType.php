<?php

namespace App\Modules\InventoryModule\Enums;

enum MovementType: string
{
    case In = 'in';
    case Out = 'out';

    public function label(): string
    {
        return match ($this) {
            self::In => 'Masuk',
            self::Out => 'Keluar',
        };
    }
}
