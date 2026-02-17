<?php

namespace App\Services\Delivery\Actions;

use App\Models\Delivery;

class DeleteDelivery
{
    public function handle(int $id): bool
    {
        return Delivery::findOrFail($id)->delete();
    }
}
