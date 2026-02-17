<?php

namespace App\Services\Vehicle;

use App\Services\Vehicle\Actions\CreateVehicle;
use App\Services\Vehicle\Actions\DeleteVehicle;
use App\Services\Vehicle\Actions\GetAllVehicles;
use App\Services\Vehicle\Actions\GetVehicleById;
use App\Services\Vehicle\Actions\UpdateVehicle;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;

class VehicleService
{
    public function __construct(
        private readonly GetAllVehicles $getAll,
        private readonly GetVehicleById $getById,
        private readonly CreateVehicle $create,
        private readonly UpdateVehicle $update,
        private readonly DeleteVehicle $delete
    ) {
    }

    public function index(): Collection
    {
        return $this->getAll->handle();
    }

    public function show(int $id): Vehicle
    {
        return $this->getById->handle($id);
    }

    public function store(array $data): Vehicle
    {
        return $this->create->handle($data);
    }

    public function update(int $id, array $data): Vehicle
    {
        return $this->update->handle($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->delete->handle($id);
    }
}
