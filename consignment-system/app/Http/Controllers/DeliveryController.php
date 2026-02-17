<?php

namespace App\Http\Controllers;

use App\Http\Requests\Delivery\StoreDeliveryRequest;
use App\Http\Requests\Delivery\UpdateDeliveryRequest;
use App\Services\Delivery\DeliveryService;
use Illuminate\Http\JsonResponse;

class DeliveryController extends Controller
{
    public function index(DeliveryService $service): JsonResponse
    {
        return response()->json($service->index());
    }

    public function store(StoreDeliveryRequest $request, DeliveryService $service): JsonResponse
    {
        $item = $service->store($request->validated());

        return response()->json($item, 201);
    }

    public function show(int $id, DeliveryService $service): JsonResponse
    {
        return response()->json($service->show($id));
    }

    public function update(UpdateDeliveryRequest $request, int $id, DeliveryService $service): JsonResponse
    {
        $item = $service->update($id, $request->validated());

        return response()->json($item);
    }

    public function destroy(int $id, DeliveryService $service): JsonResponse
    {
        $service->destroy($id);

        return response()->json(null, 204);
    }
}
