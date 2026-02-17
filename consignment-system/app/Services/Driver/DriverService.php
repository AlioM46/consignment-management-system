<?php

namespace App\Services\Driver;

use App\Services\Driver\Actions\CreateDriver;
use App\Services\Driver\Actions\DeleteDriver;
use App\Services\Driver\Actions\GetAllDrivers;
use App\Services\Driver\Actions\GetDriverById;
use App\Services\Driver\Actions\UpdateDriver;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Collection;

class DriverService
{
    public function __construct(
        private readonly GetAllDrivers $getAll,
        private readonly GetDriverById $getById,
        private readonly CreateDriver $create,
        private readonly UpdateDriver $update,
        private readonly DeleteDriver $delete
    ) {
    }

    public function index(): Collection
    {
        return $this->getAll->handle();
    }

    public function show(int $id): Driver
    {
        return $this->getById->handle($id);
    }

    public function store(array $data): Driver
    {
        return $this->create->handle($data);
    }

    public function update(int $id, array $data): Driver
    {
        return $this->update->handle($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->delete->handle($id);
    }
}
