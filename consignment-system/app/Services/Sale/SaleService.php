<?php

namespace App\Services\Sale;

use App\Services\Sale\Actions\CreateSale;
use App\Services\Sale\Actions\DeleteSale;
use App\Services\Sale\Actions\GetAllSales;
use App\Services\Sale\Actions\GetSaleById;
use App\Services\Sale\Actions\UpdateSale;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;

class SaleService
{
    public function __construct(
        private readonly GetAllSales $getAll,
        private readonly GetSaleById $getById,
        private readonly CreateSale $create,
        private readonly UpdateSale $update,
        private readonly DeleteSale $delete
    ) {
    }

    public function index(): Collection
    {
        return $this->getAll->handle();
    }

    public function show(int $id): Sale
    {
        return $this->getById->handle($id);
    }

    public function store(array $data): Sale
    {
        return $this->create->handle($data);
    }

    public function update(int $id, array $data): Sale
    {
        return $this->update->handle($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->delete->handle($id);
    }
}
