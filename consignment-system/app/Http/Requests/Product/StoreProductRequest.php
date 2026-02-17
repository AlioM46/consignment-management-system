<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'sku' => ['required', 'string', 'max:100', Rule::unique('products', 'sku')],
            'default_price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'description.required' => 'Product description is required.',
            'sku.required' => 'SKU is required.',
            'sku.unique' => 'SKU already exists.',
            'default_price.required' => 'Default price is required.',
            'cost_price.required' => 'Cost price is required.',
        ];
    }
}
