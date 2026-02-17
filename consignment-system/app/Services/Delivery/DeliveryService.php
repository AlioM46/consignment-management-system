<?php

namespace App\Services\Delivery;

use App\Services\Delivery\Actions\CreateDelivery;
use App\Services\Delivery\Actions\DeleteDelivery;
use App\Services\Delivery\Actions\GetAllDeliverys;
use App\Services\Delivery\Actions\GetDeliveryById;
use App\Services\Delivery\Actions\UpdateDelivery;
use App\Models\Delivery;
use Illuminate\Database\Eloquent\Collection;

class DeliveryService
{
    public function __construct(
        private readonly GetAllDeliverys $getAll,
        private readonly GetDeliveryById $getById,
        private readonly CreateDelivery $create,
        private readonly UpdateDelivery $update,
        private readonly DeleteDelivery $delete
    ) {
    }

    public function index(): Collection
    {
        return $this->getAll->handle();
    }

    public function show(int $id): Delivery
    {
        return $this->getById->handle($id);
    }

    public function store(array $data): Delivery
    {
        return $this->create->handle($data);
    }

    public function update(int $id, array $data): Delivery
    {
        return $this->update->handle($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->delete->handle($id);
    }
}
