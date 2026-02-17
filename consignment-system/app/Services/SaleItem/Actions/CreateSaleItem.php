<?php

namespace App\Services\SaleItem\Actions;

use App\Models\Sale;
use App\Models\SaleItems;

class CreateSaleItem
{
    public function handle(array $data): SaleItems
    {
        $item = SaleItems::create($data);

        $this->recalculateSaleTotal($item->sale_id);

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
