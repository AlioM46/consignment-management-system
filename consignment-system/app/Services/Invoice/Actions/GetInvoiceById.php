<?php

namespace App\Services\Invoice\Actions;

use App\Models\Invoice;

class GetInvoiceById
{
    public function handle(int $id): Invoice
    {
        return Invoice::findOrFail($id);
    }
}
