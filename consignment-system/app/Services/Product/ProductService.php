<?php

namespace App\Services\Product;

use App\Services\Product\Actions\CreateProduct;
use App\Services\Product\Actions\DeleteProduct;
use App\Services\Product\Actions\GetAllProducts;
use App\Services\Product\Actions\GetProductById;
use App\Services\Product\Actions\UpdateProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function __construct(
        private readonly GetAllProducts $getAll,
        private readonly GetProductById $getById,
        private readonly CreateProduct $create,
        private readonly UpdateProduct $update,
        private readonly DeleteProduct $delete
    ) {
    }

    public function index(): Collection
    {
        return $this->getAll->handle();
    }

    public function show(int $id): Product
    {
        return $this->getById->handle($id);
    }

    public function store(array $data): Product
    {
        return $this->create->handle($data);
    }

    public function update(int $id, array $data): Product
    {
        return $this->update->handle($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->delete->handle($id);
    }
}
