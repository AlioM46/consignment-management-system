<?php

namespace App\Http\Requests\Invoice;

use App\Enums\enInvoiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
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
            'vendor_id' => ['sometimes', 'required', 'integer', 'exists:vendors,id'],
            'vehicle_id' => ['sometimes', 'required', 'integer', 'exists:vehicles,id'],
            'invoice_date' => ['sometimes', 'required', 'date'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'required', 'date', 'after_or_equal:start_date'],
            'total_sales' => ['sometimes', 'required', 'numeric', 'min:0'],
            'commission_rate' => ['sometimes', 'required', 'numeric', 'min:0', 'max:100'],
            'commission_amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'expenses' => ['sometimes', 'required', 'numeric', 'min:0'],
            'net_amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'required', 'integer', Rule::in($statuses)],
        ];
    }

    public function messages(): array
    {
        return [
            'vendor_id.required' => 'Vendor is required.',
            'vendor_id.exists' => 'Vendor not found.',
            'vehicle_id.required' => 'Vehicle is required.',
            'vehicle_id.exists' => 'Vehicle not found.',
            'invoice_date.required' => 'Invoice date is required.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be on or after start date.',
            'total_sales.required' => 'Total sales is required.',
            'commission_rate.required' => 'Commission rate is required.',
            'commission_amount.required' => 'Commission amount is required.',
            'expenses.required' => 'Expenses is required.',
            'net_amount.required' => 'Net amount is required.',
            'status.required' => 'Invoice status is required.',
            'status.integer' => 'Status must be an integer code: 1 (Pending), 2 (Paid), 3 (Cancelled).',
            'status.in' => 'Status must be one of: 1, 2, 3 (or legacy strings pending, paid, cancelled).',
        ];
    }
}
