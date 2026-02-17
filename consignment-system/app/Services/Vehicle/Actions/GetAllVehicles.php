<?php

namespace App\Services\Vehicle\Actions;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;

class GetAllVehicles
{
    public function handle(): Collection
    {
        return Vehicle::all();
    }
}
