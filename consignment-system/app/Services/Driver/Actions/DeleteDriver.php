<?php

namespace App\Services\Driver\Actions;

use App\Models\Driver;

class DeleteDriver
{
    public function handle(int $id): bool
    {
        return Driver::findOrFail($id)->delete();
    }
}
