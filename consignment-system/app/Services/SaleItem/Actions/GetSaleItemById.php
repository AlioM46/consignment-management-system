<?php

namespace App\Services\SaleItem\Actions;

use App\Models\SaleItems;

class GetSaleItemById
{
    public function handle(int $id): SaleItems
    {
        return SaleItems::findOrFail($id);
    }
}
