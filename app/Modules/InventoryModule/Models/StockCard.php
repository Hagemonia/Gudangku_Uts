<?php

namespace App\Modules\InventoryModule\Models;

use App\Modules\InventoryModule\Enums\MovementType;
use Illuminate\Database\Eloquent\Model;

class StockCard extends Model
{
    protected $fillable = [
        'product_id',
        'movement_type',
        'reference_code',
        'quantity',
        'balance_after',
        'occurred_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'movement_type' => MovementType::class,
            'quantity' => 'integer',
            'balance_after' => 'integer',
            'occurred_at' => 'date',
        ];
    }
}
