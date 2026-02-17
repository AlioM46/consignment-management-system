<?php

namespace App\Services\SaleItem\Actions;

use App\Models\Sale;
use App\Models\SaleItems;

class DeleteSaleItem
{
    public function handle(int $id): bool
    {
        $item = SaleItems::findOrFail($id);
        $saleId = $item->sale_id;

        $deleted = $item->delete();

        if ($deleted) {
            $this->recalculateSaleTotal($saleId);
        }

        return $deleted;
    }

    private function recalculateSaleTotal(int $saleId): void
    {
        $total = SaleItems::query()
            ->where('sale_id', $saleId)
            ->selectRaw('COALESCE(SUM(quantity * price), 0) AS total')
            ->value('total');

        Sale::query()
            ->whereKey($saleId)
            ->update(['total_amount' => $total]);
    }
}
