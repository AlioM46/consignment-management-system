<?php

namespace App\Services\SaleItem;

use App\Services\SaleItem\Actions\CreateSaleItem;
use App\Services\SaleItem\Actions\DeleteSaleItem;
use App\Services\SaleItem\Actions\GetAllSaleItems;
use App\Services\SaleItem\Actions\GetSaleItemById;
use App\Services\SaleItem\Actions\UpdateSaleItem;
use App\Models\SaleItems;
use Illuminate\Database\Eloquent\Collection;

class SaleItemService
{
    public function __construct(
        private readonly GetAllSaleItems $getAll,
        private readonly GetSaleItemById $getById,
        private readonly CreateSaleItem $create,
        private readonly UpdateSaleItem $update,
        private readonly DeleteSaleItem $delete
    ) {
    }

    public function index(): Collection
    {
        return $this->getAll->handle();
    }

    public function show(int $id): SaleItems
    {
        return $this->getById->handle($id);
    }

    public function store(array $data): SaleItems
    {
        return $this->create->handle($data);
    }

    public function update(int $id, array $data): SaleItems
    {
        return $this->update->handle($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->delete->handle($id);
    }
}
