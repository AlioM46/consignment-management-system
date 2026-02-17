<?php

namespace App\Services\SaleItem\Actions;

use App\Models\Sale;
use App\Models\SaleItems;

class UpdateSaleItem
{
    public function handle(int $id, array $data): SaleItems
    {
        $item = SaleItems::findOrFail($id);
        $originalSaleId = $item->sale_id;

        $item->update($data);

        $this->recalculateSaleTotal($originalSaleId);
        if (isset($data['sale_id']) && (int) $data['sale_id'] !== (int) $originalSaleId) {
            $this->recalculateSaleTotal((int) $data['sale_id']);
        }

        return $item;
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
