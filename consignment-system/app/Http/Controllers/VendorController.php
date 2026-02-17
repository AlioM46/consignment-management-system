<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vendor\StoreVendorRequest;
use App\Http\Requests\Vendor\UpdateVendorRequest;
use Illuminate\Http\JsonResponse;
use App\Services\Vendor\VendorService;

class VendorController extends Controller
{
    public function index(VendorService $service): JsonResponse
    {
        return response()->json($service->index());
    }

    public function store(StoreVendorRequest $request, VendorService $service): JsonResponse
    {
        $item = $service->store($request->validated());
        return response()->json($item, 201);
    }

    public function show(int $id, VendorService $service): JsonResponse
    {
        return response()->json($service->show($id));
    }

    public function update(UpdateVendorRequest $request, int $id, VendorService $service): JsonResponse
    {
        $item = $service->update($id, $request->validated());

        return response()->json($item);
    }

    public function destroy(int $id, VendorService $service): JsonResponse
    {
        $service->destroy($id);

        return response()->json(null, 204);
    }
}
