<?php

namespace App\Services\Invoice\Actions;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;

class GetAllInvoices
{
    public function handle(): Collection
    {
        return Invoice::all();
    }
}
