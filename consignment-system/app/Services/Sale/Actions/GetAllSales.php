<?php

namespace App\Services\Sale\Actions;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;

class GetAllSales
{
    public function handle(): Collection
    {
        return Sale::all();
    }
}
