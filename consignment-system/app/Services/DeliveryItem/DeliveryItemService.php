<?php

namespace App\Services\DeliveryItem;

use App\Services\DeliveryItem\Actions\CreateDeliveryItem;
use App\Services\DeliveryItem\Actions\DeleteDeliveryItem;
use App\Services\DeliveryItem\Actions\GetAllDeliveryItems;
use App\Services\DeliveryItem\Actions\GetDeliveryItemById;
use App\Services\DeliveryItem\Actions\UpdateDeliveryItem;
use App\Models\DeliveryItems;
use Illuminate\Database\Eloquent\Collection;

class DeliveryItemService
{
    public function __construct(
        private readonly GetAllDeliveryItems $getAll,
        private readonly GetDeliveryItemById $getById,
        private readonly CreateDeliveryItem $create,
        private readonly UpdateDeliveryItem $update,
        private readonly DeleteDeliveryItem $delete
    ) {
    }

    public function index(): Collection
    {
        return $this->getAll->handle();
    }

    public function show(int $id): DeliveryItems
    {
        return $this->getById->handle($id);
    }

    public function store(array $data): DeliveryItems
    {
        return $this->create->handle($data);
    }

    public function update(int $id, array $data): DeliveryItems
    {
        return $this->update->handle($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->delete->handle($id);
    }
}
