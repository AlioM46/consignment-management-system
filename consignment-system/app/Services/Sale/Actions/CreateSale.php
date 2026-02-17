<?php

namespace App\Services\Sale\Actions;

use App\Models\Sale;

class CreateSale
{
    public function handle(array $data): Sale
    {
        if (! array_key_exists('total_amount', $data)) {
            $data['total_amount'] = 0;
        }

        return Sale::create($data);
    }
}
