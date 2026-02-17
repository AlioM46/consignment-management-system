<?php

namespace App\Services\Delivery\Actions;

use App\Models\Delivery;

class CreateDelivery
{
    public function handle(array $data): Delivery
    {
        if (! array_key_exists('total_items', $data)) {
            $data['total_items'] = 0;
        }

        if (! array_key_exists('total_value', $data)) {
            $data['total_value'] = 0;
        }

        return Delivery::create($data);
    }
}
