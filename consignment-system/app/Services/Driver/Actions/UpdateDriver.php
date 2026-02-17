<?php

namespace App\Services\Driver\Actions;

use App\Models\Driver;

class UpdateDriver
{
    public function handle(int $id, array $data): Driver
    {
        $item = Driver::findOrFail($id);
        $item->update($data);

        return $item;
    }
}
