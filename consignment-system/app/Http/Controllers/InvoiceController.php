<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Services\Invoice\Actions\CreateInvoice;
use App\Services\Invoice\InvoiceService;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function index(InvoiceService $service): JsonResponse
    {
        return response()->json($service->index());
    }

    public function createInvoicePerVehicle(StoreInvoiceRequest $request, InvoiceService $service): JsonResponse
    {
        $item = $service->store($request->validated());

        return response()->json($item, 201);
    }

    public function show(int $id, InvoiceService $service): JsonResponse
    {
        return response()->json($service->show($id));
    }

    public function update(UpdateInvoiceRequest $request, int $id, InvoiceService $service): JsonResponse
    {
        $item = $service->update($id, $request->validated());

        return response()->json($item);
    }

    public function destroy(int $id, InvoiceService $service): JsonResponse
    {
        $service->destroy($id);

        return response()->json(null, 204);
    }

}
