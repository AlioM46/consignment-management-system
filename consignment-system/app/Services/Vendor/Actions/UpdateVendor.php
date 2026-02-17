<?php

namespace App\Services\Vendor\Actions;

use App\Models\Vendor;

class UpdateVendor
{
    public function handle(int $id, array $data): Vendor
    {
        $item = Vendor::findOrFail($id);
        $item->update($data);

        return $item;
    }
}
