<?php

namespace App\Services\DeliveryItem\Actions;

use App\Models\Delivery;
use App\Models\DeliveryItems;

class DeleteDeliveryItem
{
    public function handle(int $id): bool
    {
        $item = DeliveryItems::findOrFail($id);
        $deliveryId = $item->delivery_id;

        $deleted = $item->delete();

        if ($deleted) {
            $this->recalculateDeliveryTotals($deliveryId);
        }

        return $deleted;
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
