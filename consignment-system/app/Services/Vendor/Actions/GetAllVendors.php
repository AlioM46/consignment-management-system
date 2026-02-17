<?php

namespace App\Services\Vendor\Actions;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Collection;

class GetAllVendors
{
    public function handle(): Collection
    {
        return Vendor::all();
    }
}
