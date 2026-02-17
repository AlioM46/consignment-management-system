<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeliveryItem\StoreDeliveryItemRequest;
use App\Http\Requests\DeliveryItem\UpdateDeliveryItemRequest;
use App\Services\DeliveryItem\DeliveryItemService;
use Illuminate\Http\JsonResponse;

class DeliveryItemController extends Controller
{
    public function index(DeliveryItemService $service): JsonResponse
    {
        return response()->json($service->index());
    }

    public function store(StoreDeliveryItemRequest $request, DeliveryItemService $service): JsonResponse
    {
        $item = $service->store($request->validated());

        return response()->json($item, 201);
    }

    public function show(int $id, DeliveryItemService $service): JsonResponse
    {
        return response()->json($service->show($id));
    }

    public function update(UpdateDeliveryItemRequest $request, int $id, DeliveryItemService $service): JsonResponse
    {
        $item = $service->update($id, $request->validated());

        return response()->json($item);
    }

    public function destroy(int $id, DeliveryItemService $service): JsonResponse
    {
        $service->destroy($id);

        return response()->json(null, 204);
    }
}
