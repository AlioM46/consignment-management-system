<?php

namespace App\Services\DeliveryItem\Actions;

use App\Models\DeliveryItems;

class GetDeliveryItemById
{
    public function handle(int $id): DeliveryItems
    {
        return DeliveryItems::findOrFail($id);
    }
}
