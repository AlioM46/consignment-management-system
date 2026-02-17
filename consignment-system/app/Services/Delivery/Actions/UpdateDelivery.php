<?php

namespace App\Services\Delivery\Actions;

use App\Models\Delivery;

class UpdateDelivery
{
    public function handle(int $id, array $data): Delivery
    {
        $item = Delivery::findOrFail($id);
        $item->update($data);

        return $item;
    }
}
