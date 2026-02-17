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
            'total_amount' => ['sometimes', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Vehicle is required.',
            'vehicle_id.exists' => 'Vehicle not found.',
            'sale_date.required' => 'Sale date is required.',
            'payment_method.required' => 'Payment method is required.',
        ];
    }
}
