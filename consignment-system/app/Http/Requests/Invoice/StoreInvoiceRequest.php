<?php

namespace App\Http\Requests\Invoice;

use App\Enums\enInvoiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->has('status')) {
            return;
        }

        $status = $this->input('status');

        if (is_string($status)) {
            $map = [
                'pending' => 1,
                'paid' => 2,
                'cancelled' => 3,
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
        $statuses = array_map(fn ($case) => $case->value, enInvoiceStatus::cases());

        return [
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'expenses' => ['required', 'numeric', 'min:0'],

             // Optional fields that can be calculated or provided by the client but it will be 
             // ignored if provided by overriding values in CreateInvoice action            
             'vendor_id' => ['sometimes', 'integer', 'exists:vendors,id'],
            'invoice_date' => ['sometimes', 'date'],
            'total_sales' => ['sometimes', 'numeric', 'min:0'],
            'commission_amount' => ['sometimes', 'numeric', 'min:0'],
            'net_amount' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', 'integer', Rule::in($statuses)],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Vehicle is required.',
            'vehicle_id.exists' => 'Vehicle not found.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be on or after start date.',
            'commission_rate.required' => 'Commission rate is required.',
            'expenses.required' => 'Expenses is required.',
        ];
    }
}
