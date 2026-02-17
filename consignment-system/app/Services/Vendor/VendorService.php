<?php

namespace App\Services\Vendor;

use App\Services\Vendor\Actions\CreateVendor;
use App\Services\Vendor\Actions\DeleteVendor;
use App\Services\Vendor\Actions\GetAllVendors;
use App\Services\Vendor\Actions\GetVendorById;
use App\Services\Vendor\Actions\UpdateVendor;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Collection;

class VendorService
{
    public function __construct(
        private readonly GetAllVendors $getAll,
        private readonly GetVendorById $getById,
        private readonly CreateVendor $create,
        private readonly UpdateVendor $update,
        private readonly DeleteVendor $delete
    ) {
    }

    public function index(): Collection
    {
        return $this->getAll->handle();
    }

    public function show(int $id): Vendor
    {
        return $this->getById->handle($id);
    }

    public function store(array $data): Vendor
    {
        return $this->create->handle($data);
    }

    public function update(int $id, array $data): Vendor
    {
        return $this->update->handle($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->delete->handle($id);
    }
}
