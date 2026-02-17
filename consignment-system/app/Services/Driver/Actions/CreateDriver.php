<?php

namespace App\Services\Driver\Actions;

use App\Models\Driver;

class CreateDriver
{
    public function handle(array $data): Driver
    {
        return Driver::create($data);
    }
}
