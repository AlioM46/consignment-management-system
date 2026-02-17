<?php

namespace App\Services\DeliveryItem\Actions;

use App\Models\DeliveryItems;
use Illuminate\Database\Eloquent\Collection;

class GetAllDeliveryItems
{
    public function handle(): Collection
    {
        return DeliveryItems::all();
    }
}
