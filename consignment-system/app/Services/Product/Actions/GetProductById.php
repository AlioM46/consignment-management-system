<?php

namespace App\Services\Product\Actions;

use App\Models\Product;

class GetProductById
{
    public function handle(int $id): Product
    {
        return Product::findOrFail($id);
    }
}
