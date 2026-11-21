<?php

namespace App\Modules\InventoryModule\Http\Requests;

use App\Modules\InventoryModule\Rules\ActiveProduct;
use App\Modules\InventoryModule\Rules\ActiveSupplier;
use Illuminate\Foundation\Http\FormRequest;

class StockInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => ['bail', 'required', 'integer', new ActiveSupplier],
            'received_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1', 'max:50'],
            'items.*.product_id' => ['bail', 'required', 'integer', 'distinct', new ActiveProduct],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'items.*.unit_cost' => ['nullable', 'numeric', 'min:0', 'max:999999999999'],
        ];
    }

    public function attributes(): array
    {
        return [
            'items.*.product_id' => 'barang',
            'items.*.quantity' => 'jumlah',
            'items.*.unit_cost' => 'harga satuan',
        ];
    }
}
