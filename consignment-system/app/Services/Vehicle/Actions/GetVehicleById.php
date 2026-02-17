<?php

namespace App\Services\Vehicle\Actions;

use App\Models\Vehicle;

class GetVehicleById
{
    public function handle(int $id): Vehicle
    {
        return Vehicle::findOrFail($id);
    }
}
