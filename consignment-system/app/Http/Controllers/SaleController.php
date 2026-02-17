<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\StoreSaleRequest;
use App\Http\Requests\Sale\UpdateSaleRequest;
use App\Services\Sale\SaleService;
use Illuminate\Http\JsonResponse;

class SaleController extends Controller
{
    public function index(SaleService $service): JsonResponse
    {
        return response()->json($service->index());
    }

    public function store(StoreSaleRequest $request, SaleService $service): JsonResponse
    {
        $item = $service->store($request->validated());

        return response()->json($item, 201);
    }

    public function show(int $id, SaleService $service): JsonResponse
    {
        return response()->json($service->show($id));
    }

    public function update(UpdateSaleRequest $request, int $id, SaleService $service): JsonResponse
    {
        $item = $service->update($id, $request->validated());

        return response()->json($item);
    }

    public function destroy(int $id, SaleService $service): JsonResponse
    {
        $service->destroy($id);

        return response()->json(null, 204);
    }
}
