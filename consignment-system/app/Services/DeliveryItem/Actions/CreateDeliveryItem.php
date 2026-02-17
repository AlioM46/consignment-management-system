<?php

namespace App\Services\DeliveryItem\Actions;

use App\Models\DeliveryItems;
use App\Services\Totals\ItemTotalsCalculator;

class CreateDeliveryItem
{
    public function handle(array $data): DeliveryItems
    {
        $item = DeliveryItems::create($data);

        ItemTotalsCalculator::recalculateDelivery($item->delivery_id);

        return $item;
    }
}
