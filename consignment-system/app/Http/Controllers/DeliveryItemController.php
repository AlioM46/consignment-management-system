<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeliveryItem\StoreDeliveryItemRequest;
use App\Http\Requests\DeliveryItem\UpdateDeliveryItemRequest;
use App\Models\Delivery;
use App\Models\Product;
use App\Services\DeliveryItem\DeliveryItemService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DeliveryItemController extends Controller
{
    public function index(DeliveryItemService $service): Response
    {
        return Inertia::render('DeliveryItems/Index', [
            'deliveryItems' => $service->index(),
            'deliveries' => Delivery::all(['id', 'name']),
            'products' => Product::all(['id', 'name']),
        ]);
    }

    public function store(StoreDeliveryItemRequest $request, DeliveryItemService $service): RedirectResponse
    {
        $service->store($request->validated());
        return redirect()->route('delivery-items.index');
    }

    public function show(int $id, DeliveryItemService $service): Response
    {
        return Inertia::render('DeliveryItems/Show', [
            'deliveryItem' => $service->show($id),
            'deliveries' => Delivery::all(['id', 'name']),
            'products' => Product::all(['id', 'name']),
        ]);
    }

    public function update(UpdateDeliveryItemRequest $request, int $id, DeliveryItemService $service): RedirectResponse
    {
        $service->update($id, $request->validated());
        return redirect()->route('delivery-items.show', $id);
    }

    public function destroy(int $id, DeliveryItemService $service): RedirectResponse
    {
        $service->destroy($id);
        return redirect()->route('delivery-items.index');
    }
}
