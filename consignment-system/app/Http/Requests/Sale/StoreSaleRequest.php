<?php

namespace App\Http\Requests\Sale;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'sale_date' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:50'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Vehicle is required.',
            'vehicle_id.exists' => 'Vehicle not found.',
            'sale_date.required' => 'Sale date is required.',
            'payment_method.required' => 'Payment method is required.',
            'products.required' => 'At least one product is required.',
            'products.min' => 'At least one product is required.',
            'products.*.product_id.required' => 'Product is required.',
            'products.*.product_id.exists' => 'Product not found.',
            'products.*.quantity.required' => 'Quantity is required.',
            'products.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
