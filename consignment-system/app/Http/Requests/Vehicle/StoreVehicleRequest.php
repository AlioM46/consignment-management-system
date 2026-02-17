<?php

namespace App\Http\Requests\Vehicle;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plate_number' => ['required', 'string', 'max:50'],
            'Vehicle_model' => ['required', 'string', 'max:255'],
            'driver_id' => ['required', 'integer', 'exists:drivers,id'],
            'vendor_id' => ['required', 'integer', 'exists:vendors,id'],    
        ];
    }

    public function messages(): array
    {
        return [
            'plate_number.required' => 'Plate number is required.',
            'Vehicle_model.required' => 'Vehicle model is required.',
            'driver_id.required' => 'Driver is required.',
            'driver_id.exists' => 'Driver not found.',
            'vendor_id.required' => 'Vendor is required.',
            'vendor_id.exists' => 'Vendor not found.',
        ];
    }
}
