<?php

namespace App\Services\Vendor\Actions;

use App\Models\Vendor;

class CreateVendor
{
    public function handle(array $data): Vendor
    {
        return Vendor::create($data);
    }
}
