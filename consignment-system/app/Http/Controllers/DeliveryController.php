<?php

namespace App\Http\Controllers;

use App\Http\Requests\Delivery\StoreDeliveryRequest;
use App\Http\Requests\Delivery\UpdateDeliveryRequest;
use App\Models\Vehicle;
use App\Services\Delivery\DeliveryService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DeliveryController extends Controller
{
    public function index(DeliveryService $service): Response
    {
        return Inertia::render('Deliveries/Index', [
            'deliveries' => $service->index(),
            'vehicles' => Vehicle::all(['id', 'plate_number']),
            'products' => \App\Models\Product::all(['id', 'name', 'default_price']),
        ]);
    }

    public function store(StoreDeliveryRequest $request, DeliveryService $service): RedirectResponse
    {
        $service->store($request->validated());
        return redirect()->route('deliveries.index');
    }

    public function show(int $id, DeliveryService $service): Response
    {
        $delivery = $service->show($id);
        $delivery->load('items.product');

        return Inertia::render('Deliveries/Show', [
            'delivery' => $delivery,
            'vehicles' => Vehicle::all(['id', 'plate_number']),
            'products' => \App\Models\Product::all(['id', 'name', 'default_price']),
        ]);
    }

    public function update(UpdateDeliveryRequest $request, int $id, DeliveryService $service): RedirectResponse
    {
        $service->update($id, $request->validated());
        return redirect()->route('deliveries.show', $id);
    }

    public function destroy(int $id, DeliveryService $service): RedirectResponse
    {
        $service->destroy($id);
        return redirect()->route('deliveries.index');
    }
}
