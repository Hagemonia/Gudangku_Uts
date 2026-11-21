<?php

namespace App\Modules\InventoryModule\Models;

use App\Modules\InventoryModule\Enums\StockOutReason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockOut extends Model
{
    protected $fillable = [
        'code',
        'reason',
        'issued_at',
        'notes',
        'user_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'reason' => StockOutReason::class,
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockOutItem::class);
    }
}
