<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(ProductService $service): JsonResponse
    {
        return response()->json($service->index());
    }

    public function store(StoreProductRequest $request, ProductService $service): JsonResponse
    {
        $item = $service->store($request->validated());

        return response()->json($item, 201);
    }

    public function show(int $id, ProductService $service): JsonResponse
    {
        return response()->json($service->show($id));
    }

    public function update(UpdateProductRequest $request, int $id, ProductService $service): JsonResponse
    {
        $item = $service->update($id, $request->validated());

        return response()->json($item);
    }

    public function destroy(int $id, ProductService $service): JsonResponse
    {
        $service->destroy($id);

        return response()->json(null, 204);
    }
}
