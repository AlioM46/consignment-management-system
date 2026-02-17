<?php

namespace App\Http\Controllers;

use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Services\Driver\DriverService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DriverController extends Controller
{
    public function index(DriverService $service): Response
    {
        return Inertia::render('Drivers/Index', [
            'drivers' => $service->index(),
        ]);
    }

    public function store(StoreDriverRequest $request, DriverService $service): RedirectResponse
    {
        $service->store($request->validated());
        return redirect()->route('drivers.index');
    }

    public function show(int $id, DriverService $service): Response
    {
        return Inertia::render('Drivers/Show', [
            'driver' => $service->show($id),
        ]);
    }

    public function update(UpdateDriverRequest $request, int $id, DriverService $service): RedirectResponse
    {
        $service->update($id, $request->validated());
        return redirect()->route('drivers.show', $id);
    }

    public function destroy(int $id, DriverService $service): RedirectResponse
    {
        $service->destroy($id);
        return redirect()->route('drivers.index');
    }
}
