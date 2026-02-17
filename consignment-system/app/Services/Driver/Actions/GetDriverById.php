<?php

namespace App\Services\Driver\Actions;

use App\Models\Driver;

class GetDriverById
{
    public function handle(int $id): Driver
    {
        return Driver::findOrFail($id);
    }
}
