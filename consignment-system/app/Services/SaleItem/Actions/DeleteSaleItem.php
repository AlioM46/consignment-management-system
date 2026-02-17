<?php

namespace App\Services\SaleItem\Actions;

use App\Models\SaleItems;
use App\Services\Totals\ItemTotalsCalculator;

class DeleteSaleItem
{
    public function handle(int $id): bool
    {
        $item = SaleItems::findOrFail($id);
        $saleId = $item->sale_id;

        $deleted = $item->delete();

        if ($deleted) {
            ItemTotalsCalculator::recalculateSale($saleId);
        }

        return $deleted;
    }
}
