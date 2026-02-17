<?php

namespace App\Http\Controllers;

use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Services\Driver\DriverService;
use Illuminate\Http\JsonResponse;

class DriverController extends Controller
{
    public function index(DriverService $service): JsonResponse
    {
        return response()->json($service->index());
    }

    public function store(StoreDriverRequest $request, DriverService $service): JsonResponse
    {
        $item = $service->store($request->validated());

        return response()->json($item, 201);
    }

    public function show(int $id, DriverService $service): JsonResponse
    {
        return response()->json($service->show($id));
    }

    public function update(UpdateDriverRequest $request, int $id, DriverService $service): JsonResponse
    {
        $item = $service->update($id, $request->validated());

        return response()->json($item);
    }

    public function destroy(int $id, DriverService $service): JsonResponse
    {
        $service->destroy($id);

        return response()->json(null, 204);
    }
}
