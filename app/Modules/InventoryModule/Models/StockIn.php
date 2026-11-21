<?php

namespace App\Modules\InventoryModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockIn extends Model
{
    protected $fillable = [
        'code',
        'supplier_id',
        'supplier_name',
        'received_at',
        'notes',
        'user_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return ['received_at' => 'date'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockInItem::class);
    }
}
