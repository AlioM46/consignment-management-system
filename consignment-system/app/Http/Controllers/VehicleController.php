<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vehicle\StoreVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Models\Driver;
use App\Models\Vendor;
use App\Services\Vehicle\VehicleService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class VehicleController extends Controller
{
    public function index(VehicleService $service): Response
    {
        return Inertia::render('Vehicles/Index', [
            'vehicles' => $service->index(),
            'drivers' => Driver::all(['id', 'name']),
            'vendors' => Vendor::all(['id', 'name']),
        ]);
    }

    public function store(StoreVehicleRequest $request, VehicleService $service): RedirectResponse
    {
        $service->store($request->validated());
        return redirect()->route('vehicles.index');
    }

    public function show(int $id, VehicleService $service): Response
    {
        return Inertia::render('Vehicles/Show', [
            'vehicle' => $service->show($id),
            'drivers' => Driver::all(['id', 'name']),
            'vendors' => Vendor::all(['id', 'name']),
        ]);
    }

    public function update(UpdateVehicleRequest $request, int $id, VehicleService $service): RedirectResponse
    {
        $service->update($id, $request->validated());
        return redirect()->route('vehicles.show', $id);
    }

    public function destroy(int $id, VehicleService $service): RedirectResponse
    {
        $service->destroy($id);
        return redirect()->route('vehicles.index');
    }
}
