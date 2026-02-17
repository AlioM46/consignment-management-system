<?php

namespace App\Services\Sale\Actions;

use App\Models\Sale;

class DeleteSale
{
    public function handle(int $id): bool
    {
        return Sale::findOrFail($id)->delete();
    }
}
