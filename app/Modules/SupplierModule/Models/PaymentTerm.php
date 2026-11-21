<?php

namespace App\Modules\SupplierModule\Models;

use App\Modules\SupplierModule\Database\Factories\PaymentTermFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentTerm extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'days', 'description'];

    protected static function newFactory(): PaymentTermFactory
    {
        return PaymentTermFactory::new();
    }

    protected function casts(): array
    {
        return ['days' => 'integer'];
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }
}
