<?php

namespace App\Services\SaleItem\Actions;

use App\Models\SaleItems;
use App\Services\Totals\ItemTotalsCalculator;

class UpdateSaleItem
{
    public function handle(int $id, array $data): SaleItems
    {
        $item = SaleItems::findOrFail($id);
        $originalSaleId = $item->sale_id;

        $item->update($data);

        ItemTotalsCalculator::recalculateSale($originalSaleId);
        if (isset($data['sale_id']) && (int) $data['sale_id'] !== (int) $originalSaleId) {
            ItemTotalsCalculator::recalculateSale((int) $data['sale_id']);
        }

        return $item;
    }
}
