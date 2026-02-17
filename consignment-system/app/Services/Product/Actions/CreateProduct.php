<?php

namespace App\Services\Product\Actions;

use App\Models\Product;

class CreateProduct
{
    public function handle(array $data): Product
    {
        return Product::create($data);
    }
}
