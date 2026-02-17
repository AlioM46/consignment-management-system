<?php

namespace App\Http\Requests\DeliveryItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'delivery_id' => ['required', 'integer', 'exists:deliveries,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'delivery_id.required' => 'Delivery is required.',
            'delivery_id.exists' => 'Delivery not found.',
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'Product not found.',
            'quantity.required' => 'Quantity is required.',
            'price.required' => 'Price is required.',
        ];
    }
}
