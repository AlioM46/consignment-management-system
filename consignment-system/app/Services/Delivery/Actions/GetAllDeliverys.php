<?php

namespace App\Services\Delivery\Actions;

use App\Models\Delivery;
use Illuminate\Database\Eloquent\Collection;

class GetAllDeliverys
{
    public function handle(): Collection
    {
        return Delivery::all();
    }
}
