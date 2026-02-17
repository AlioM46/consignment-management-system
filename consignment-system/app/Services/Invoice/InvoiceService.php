<?php

namespace App\Services\Invoice;

use App\Services\Invoice\Actions\CreateInvoice;
use App\Services\Invoice\Actions\DeleteInvoice;
use App\Services\Invoice\Actions\GetAllInvoices;
use App\Services\Invoice\Actions\GetInvoiceById;
use App\Services\Invoice\Actions\UpdateInvoice;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;

class InvoiceService
{
    public function __construct(
        private readonly GetAllInvoices $getAll,
        private readonly GetInvoiceById $getById,
        private readonly CreateInvoice $create,
        private readonly UpdateInvoice $update,
        private readonly DeleteInvoice $delete
    ) {}

    public function index(): Collection
    {
        return $this->getAll->handle();
    }

    public function show(int $id): Invoice
    {
        return $this->getById->handle($id);
    }

    public function store(array $data): Invoice
    {


        return $this->create->handle($data);
    }

    public function update(int $id, array $data): Invoice
    {
        return $this->update->handle($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->delete->handle($id);
    }
}
