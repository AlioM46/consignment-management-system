<?php

namespace App\Services\Totals;

use App\Models\Delivery;
use App\Models\DeliveryItems;
use App\Models\Sale;
use App\Models\SaleItems;

class ItemTotalsCalculator
{
    public static function recalculateSale(int $saleId): void
    {
        $total = SaleItems::query()
            ->where('sale_id', $saleId)
            ->selectRaw('COALESCE(SUM(quantity * price), 0) AS total')
            ->value('total');

        Sale::query()
            ->whereKey($saleId)
            ->update(['total_amount' => $total]);
    }

    public static function recalculateDelivery(int $deliveryId): void
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
