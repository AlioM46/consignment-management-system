<?php

namespace App\Http\Requests\SaleItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sale_id' => ['sometimes', 'required', 'integer', 'exists:sales,id'],
            'product_id' => ['sometimes', 'required', 'integer', 'exists:products,id'],
            'quantity' => ['sometimes', 'required', 'integer', 'min:1'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'sale_id.required' => 'Sale is required.',
            'sale_id.exists' => 'Sale not found.',
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'Product not found.',
            'quantity.required' => 'Quantity is required.',
            'price.required' => 'Price is required.',
        ];
    }
}
