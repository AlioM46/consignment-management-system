<?php

namespace App\Services\Vendor\Actions;

use App\Models\Vendor;

class GetVendorById
{
    public function handle(int $id): Vendor
    {
        return Vendor::findOrFail($id);
    }
}
