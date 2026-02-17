<?php

namespace App\Services\Product\Actions;

use App\Models\Product;

class UpdateProduct
{
    public function handle(int $id, array $data): Product
    {
        $item = Product::findOrFail($id);
        $item->update($data);

        return $item;
    }
}
