<?php

namespace App\Services\SaleItem\Actions;

use App\Models\SaleItems;
use Illuminate\Database\Eloquent\Collection;

class GetAllSaleItems
{
    public function handle(): Collection
    {
        return SaleItems::all();
    }
}
