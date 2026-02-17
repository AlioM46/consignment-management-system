<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vehicle\StoreVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Services\Vehicle\VehicleService;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    public function index(VehicleService $service): JsonResponse
    {
        return response()->json($service->index());
    }

    public function store(StoreVehicleRequest $request, VehicleService $service): JsonResponse
    {
        $item = $service->store($request->validated());

        return response()->json($item, 201);
    }

    public function show(int $id, VehicleService $service): JsonResponse
    {
        return response()->json($service->show($id));
    }

    public function update(UpdateVehicleRequest $request, int $id, VehicleService $service): JsonResponse
    {
        $item = $service->update($id, $request->validated());

        return response()->json($item);
    }

    public function destroy(int $id, VehicleService $service): JsonResponse
    {
        $service->destroy($id);

        return response()->json(null, 204);
    }
}
