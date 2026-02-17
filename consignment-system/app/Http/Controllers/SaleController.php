<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\StoreSaleRequest;
use App\Http\Requests\Sale\UpdateSaleRequest;
use App\Models\Vehicle;
use App\Services\Sale\SaleService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SaleController extends Controller
{
    public function index(SaleService $service): Response
    {
        return Inertia::render('Sales/Index', [
            'sales' => $service->index(),
            'vehicles' => Vehicle::all(['id', 'plate_number']),
            'products' => \App\Models\Product::all(['id', 'name', 'default_price']),
        ]);
    }

    public function store(StoreSaleRequest $request, SaleService $service): RedirectResponse
    {
        $service->store($request->validated());
        return redirect()->route('sales.index');
    }

    public function show(int $id, SaleService $service): Response
    {
        $sale = $service->show($id);
        $sale->load('items.product'); // Eager load items and products

        return Inertia::render('Sales/Show', [
            'sale' => $sale,
            'vehicles' => Vehicle::all(['id', 'plate_number']),
            'products' => \App\Models\Product::all(['id', 'name', 'default_price']),
        ]);
    }

    public function update(UpdateSaleRequest $request, int $id, SaleService $service): RedirectResponse
    {
        $service->update($id, $request->validated());
        return redirect()->route('sales.show', $id);
    }

    public function destroy(int $id, SaleService $service): RedirectResponse
    {
        $service->destroy($id);
        return redirect()->route('sales.index');
    }
}
