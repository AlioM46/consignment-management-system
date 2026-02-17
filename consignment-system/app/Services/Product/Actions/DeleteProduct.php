<?php

namespace App\Services\Product\Actions;

use App\Models\Product;

class DeleteProduct
{
    public function handle(int $id): bool
    {
        return Product::findOrFail($id)->delete();
    }
}
