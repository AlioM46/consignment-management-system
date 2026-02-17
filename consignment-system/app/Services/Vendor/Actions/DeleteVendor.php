<?php

namespace App\Services\Vendor\Actions;

use App\Models\Vendor;

class DeleteVendor
{
    public function handle(int $id): bool
    {
        return Vendor::findOrFail($id)->delete();
    }
}
