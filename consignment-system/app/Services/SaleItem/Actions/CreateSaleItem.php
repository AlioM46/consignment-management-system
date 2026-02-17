<?php

namespace App\Services\SaleItem\Actions;

use App\Models\SaleItems;
use App\Services\Totals\ItemTotalsCalculator;

class CreateSaleItem
{
    public function handle(array $data): SaleItems
    {
        $item = SaleItems::create($data);

        ItemTotalsCalculator::recalculateSale($item->sale_id);

        return $item;
    }
}
