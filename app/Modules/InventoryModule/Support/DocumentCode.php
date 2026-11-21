<?php

namespace App\Modules\InventoryModule\Support;

use Illuminate\Support\Str;

class DocumentCode
{
    public static function make(string $prefix): string
    {
        return sprintf('%s-%s-%s', $prefix, now()->format('Ymd'), Str::upper(Str::random(5)));
    }
}
