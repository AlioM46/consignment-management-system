<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleItem\StoreSaleItemRequest;
use App\Http\Requests\SaleItem\UpdateSaleItemRequest;
use App\Services\SaleItem\SaleItemService;
use Illuminate\Http\JsonResponse;

class SaleItemController extends Controller
{
    public function index(SaleItemService $service): JsonResponse
    {
        return response()->json($service->index());
    }

    public function store(StoreSaleItemRequest $request, SaleItemService $service): JsonResponse
    {
        $item = $service->store($request->validated());

        return response()->json($item, 201);
    }

    public function show(int $id, SaleItemService $service): JsonResponse
    {
        return response()->json($service->show($id));
    }

    public function update(UpdateSaleItemRequest $request, int $id, SaleItemService $service): JsonResponse
    {
        $item = $service->update($id, $request->validated());

        return response()->json($item);
    }

    public function destroy(int $id, SaleItemService $service): JsonResponse
    {
        $service->destroy($id);

        return response()->json(null, 204);
    }
}
