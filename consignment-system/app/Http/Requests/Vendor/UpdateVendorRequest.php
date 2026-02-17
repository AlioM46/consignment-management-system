<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('vendors', 'email')->ignore($this->route('id'))],
            'phone' => ['sometimes', 'required', 'string', 'max:50'],
            'address' => ['sometimes', 'required', 'string', 'max:255'],
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
