<?php

namespace App\Services\Product\Actions;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class GetAllProducts
{
    public function handle(): Collection
    {
        return Product::all();
    }
}
