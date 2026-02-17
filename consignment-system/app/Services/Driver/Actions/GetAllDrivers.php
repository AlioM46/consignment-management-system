<?php

namespace App\Services\Driver\Actions;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Collection;

class GetAllDrivers
{
    public function handle(): Collection
    {
        return Driver::all();
    }
}
