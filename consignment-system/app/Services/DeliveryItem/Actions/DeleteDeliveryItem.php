<?php

namespace App\Services\DeliveryItem\Actions;

use App\Models\DeliveryItems;
use App\Services\Totals\ItemTotalsCalculator;

class DeleteDeliveryItem
{
    public function handle(int $id): bool
    {
        $item = DeliveryItems::findOrFail($id);
        $deliveryId = $item->delivery_id;

        $deleted = $item->delete();

        if ($deleted) {
            ItemTotalsCalculator::recalculateDelivery($deliveryId);
        }

        return $deleted;
    }
}
