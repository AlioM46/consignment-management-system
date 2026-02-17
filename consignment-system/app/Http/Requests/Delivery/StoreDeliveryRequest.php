<?php

namespace App\Http\Requests\Delivery;

use App\Enums\enDeliveryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDeliveryRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (!$this->has('status')) {
            return;
        }

        $status = $this->input('status');

        if (is_string($status)) {
            $map = [
                'arrived' => 1,
                'cancelled' => 2,
                'refunded' => 3,
            ];

            $normalized = $map[strtolower(trim($status))] ?? $status;
            $this->merge(['status' => $normalized]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $statuses = array_map(fn($case) => $case->value, enDeliveryStatus::cases());

        return [
            'name' => ['required', 'string', 'max:255'],
            'delivery_date' => ['required', 'date'],
            'status' => ['required', 'integer', Rule::in($statuses)],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Delivery name is required.',
            'delivery_date.required' => 'Delivery date is required.',
            'status.required' => 'Delivery status is required.',
            'status.integer' => 'Status must be an integer code: 1 (Arrived), 2 (Cancelled), 3 (Refunded).',
            'status.in' => 'Status must be one of: 1, 2, 3 (or legacy strings arrived, cancelled, refunded).',
            'vehicle_id.required' => 'Vehicle is required.',
            'vehicle_id.exists' => 'Vehicle not found.',
            'products.required' => 'At least one product is required.',
            'products.min' => 'At least one product is required.',
            'products.*.product_id.required' => 'Product is required.',
            'products.*.product_id.exists' => 'Product not found.',
            'products.*.quantity.required' => 'Quantity is required.',
            'products.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
