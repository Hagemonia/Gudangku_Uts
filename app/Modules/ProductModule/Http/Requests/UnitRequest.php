<?php

namespace App\Modules\ProductModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('units', 'name')->ignore($this->route('unit'))],
            'symbol' => ['required', 'string', 'max:10'],
        ];
    }
}
