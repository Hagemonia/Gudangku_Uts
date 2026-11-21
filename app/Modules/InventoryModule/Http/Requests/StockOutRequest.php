<?php

namespace App\Modules\InventoryModule\Http\Requests;

use App\Modules\InventoryModule\Enums\StockOutReason;
use App\Modules\InventoryModule\Rules\ActiveProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StockOutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'issued_at' => ['required', 'date'],
            'reason' => ['required', Rule::enum(StockOutReason::class)],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1', 'max:50'],
            'items.*.product_id' => ['bail', 'required', 'integer', 'distinct', new ActiveProduct],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'items.*.product_id' => 'barang',
            'items.*.quantity' => 'jumlah',
        ];
    }
}
