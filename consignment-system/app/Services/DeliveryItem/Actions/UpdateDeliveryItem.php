<?php

namespace App\Services\DeliveryItem\Actions;

use App\Models\DeliveryItems;
use App\Services\Totals\ItemTotalsCalculator;

class UpdateDeliveryItem
{
    public function handle(int $id, array $data): DeliveryItems
    {
        $item = DeliveryItems::findOrFail($id);
        $originalDeliveryId = $item->delivery_id;

        $item->update($data);

        ItemTotalsCalculator::recalculateDelivery($originalDeliveryId);
        if (isset($data['delivery_id']) && (int) $data['delivery_id'] !== (int) $originalDeliveryId) {
            ItemTotalsCalculator::recalculateDelivery((int) $data['delivery_id']);
        }

        return $item;
    }
}
