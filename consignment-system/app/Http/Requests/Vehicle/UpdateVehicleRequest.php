<?php

namespace App\Http\Requests\Vehicle;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plate_number' => ['sometimes', 'required', 'string', 'max:50'],
            'Vehicle_model' => ['sometimes', 'required', 'string', 'max:255'],
            'driver_id' => ['sometimes', 'required', 'integer', 'exists:drivers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'plate_number.required' => 'Plate number is required.',
            'Vehicle_model.required' => 'Vehicle model is required.',
            'driver_id.required' => 'Driver is required.',
            'driver_id.exists' => 'Driver not found.',
        ];
    }
}
