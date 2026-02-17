<?php

namespace App\Services\Sale\Actions;

use App\Models\Sale;

class GetSaleById
{
    public function handle(int $id): Sale
    {
        return Sale::findOrFail($id);
    }
}
