<?php

namespace App\Modules\SupplierModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:30', Rule::unique('suppliers', 'code')->ignore($this->route('supplier'))],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'string', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
            'address' => ['nullable', 'string', 'max:500'],
            'payment_term_id' => ['required', 'integer', Rule::exists('payment_terms', 'id')],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
