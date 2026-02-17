<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('vendors', 'email')],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vendor name is required.',
            'email.required' => 'Vendor email is required.',
            'email.email' => 'Vendor email must be a valid email address.',
            'email.unique' => 'Vendor already exists with this email.',
            'phone.required' => 'Vendor phone is required.',
            'address.required' => 'Vendor address is required.',
        ];
    }
}
