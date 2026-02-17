<?php

namespace App\Services\DeliveryItem\Actions;

use App\Models\Delivery;
use App\Models\DeliveryItems;

class UpdateDeliveryItem
{
    public function handle(int $id, array $data): DeliveryItems
    {
        $item = DeliveryItems::findOrFail($id);
        $originalDeliveryId = $item->delivery_id;

        $item->update($data);

        $this->recalculateDeliveryTotals($originalDeliveryId);
        if (isset($data['delivery_id']) && (int) $data['delivery_id'] !== (int) $originalDeliveryId) {
            $this->recalculateDeliveryTotals((int) $data['delivery_id']);
        }

        return $item;
    }

    private function recalculateDeliveryTotals(int $deliveryId): void
    {
        $totals = DeliveryItems::query()
            ->where('delivery_id', $deliveryId)
            ->selectRaw('COALESCE(SUM(quantity), 0) AS total_items, COALESCE(SUM(quantity * price), 0) AS total_value')
            ->first();

        Delivery::query()
            ->whereKey($deliveryId)
            ->update([
                'total_items' => (int) ($totals->total_items ?? 0),
                'total_value' => $totals->total_value ?? 0,
            ]);
    }
}
