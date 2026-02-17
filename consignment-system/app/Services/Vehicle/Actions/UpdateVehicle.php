<?php

namespace App\Services\Vehicle\Actions;

use App\Models\Vehicle;

class UpdateVehicle
{
    public function handle(int $id, array $data): Vehicle
    {
        $item = Vehicle::findOrFail($id);
        $item->update($data);

        return $item;
    }
}
