<?php

namespace App\Services\Invoice\Actions;

use App\Models\Invoice;

class DeleteInvoice
{
    public function handle(int $id): bool
    {
        return Invoice::findOrFail($id)->delete();
    }
}
