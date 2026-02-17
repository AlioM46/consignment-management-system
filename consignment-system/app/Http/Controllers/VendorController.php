<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vendor\StoreVendorRequest;
use App\Http\Requests\Vendor\UpdateVendorRequest;
use App\Services\Vendor\VendorService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class VendorController extends Controller
{
    public function index(VendorService $service): Response
    {
        return Inertia::render('Vendors/Index', [
            'vendors' => $service->index(),
        ]);
    }

    public function store(StoreVendorRequest $request, VendorService $service): RedirectResponse
    {
        $service->store($request->validated());

        return redirect()->route('vendors.index');
    }

    public function show(int $id, VendorService $service): Response
    {
        return Inertia::render('Vendors/Show', [
            'vendor' => $service->show($id),
        ]);
    }

    public function update(UpdateVendorRequest $request, int $id, VendorService $service): RedirectResponse
    {
        $service->update($id, $request->validated());

        return redirect()->route('vendors.show', $id);
    }

    public function destroy(int $id, VendorService $service): RedirectResponse
    {
        $service->destroy($id);

        return redirect()->route('vendors.index');
    }
}
