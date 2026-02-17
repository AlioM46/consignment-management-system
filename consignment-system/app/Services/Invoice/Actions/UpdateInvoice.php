<?php

namespace App\Services\Invoice\Actions;

use App\Models\Invoice;

class UpdateInvoice
{
    public function handle(int $id, array $data): Invoice
    {
        $item = Invoice::findOrFail($id);
        $item->update($data);

        return $item;
    }
}
