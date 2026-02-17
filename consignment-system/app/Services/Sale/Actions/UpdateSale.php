<?php

namespace App\Services\Sale\Actions;

use App\Models\Sale;

class UpdateSale
{
    public function handle(int $id, array $data): Sale
    {
        $item = Sale::findOrFail($id);
        $item->update($data);

        return $item;
    }
}
