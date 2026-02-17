<?php

namespace App\Services\Vehicle\Actions;

use App\Models\Vehicle;

class DeleteVehicle
{
    public function handle(int $id): bool
    {
        return Vehicle::findOrFail($id)->delete();
    }
}
