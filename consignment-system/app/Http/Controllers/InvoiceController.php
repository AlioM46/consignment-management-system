<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Models\Vehicle;
use App\Models\Vendor;
use App\Services\Invoice\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(InvoiceService $service): Response
    {
        return Inertia::render('Invoices/Index', [
            'invoices' => $service->index(),
            'vehicles' => Vehicle::all(['id', 'plate_number']),
            'vendors' => Vendor::all(['id', 'name']),
        ]);
    }

    public function createInvoicePerVehicle(StoreInvoiceRequest $request, InvoiceService $service): RedirectResponse
    {
        $service->store($request->validated());
        return redirect()->route('invoices.index');
    }

    public function show(int $id, InvoiceService $service): Response
    {
        return Inertia::render('Invoices/Show', [
            'invoice' => $service->show($id),
            'vehicles' => Vehicle::all(['id', 'plate_number']),
            'vendors' => Vendor::all(['id', 'name']),
        ]);
    }

    public function update(UpdateInvoiceRequest $request, int $id, InvoiceService $service): RedirectResponse
    {
        $service->update($id, $request->validated());
        return redirect()->route('invoices.show', $id);
    }

    public function destroy(int $id, InvoiceService $service): RedirectResponse
    {
        $service->destroy($id);
        return redirect()->route('invoices.index');
    }
}
