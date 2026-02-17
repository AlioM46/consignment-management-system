<?php

namespace App\Services\Delivery\Actions;

use App\Models\Delivery;

class GetDeliveryById
{
    public function handle(int $id): Delivery
    {
        return Delivery::findOrFail($id);
    }
}
