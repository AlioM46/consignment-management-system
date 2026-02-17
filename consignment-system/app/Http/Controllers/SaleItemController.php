<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleItem\StoreSaleItemRequest;
use App\Http\Requests\SaleItem\UpdateSaleItemRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleItem\SaleItemService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SaleItemController extends Controller
{
    public function index(SaleItemService $service): Response
    {
        return Inertia::render('SaleItems/Index', [
            'saleItems' => $service->index(),
            'sales' => Sale::all(['id', 'sale_date', 'vehicle_id']),
            'products' => Product::all(['id', 'name']),
        ]);
    }

    public function store(StoreSaleItemRequest $request, SaleItemService $service): RedirectResponse
    {
        $service->store($request->validated());
        return redirect()->route('sale-items.index');
    }

    public function show(int $id, SaleItemService $service): Response
    {
        return Inertia::render('SaleItems/Show', [
            'saleItem' => $service->show($id),
            'sales' => Sale::all(['id', 'sale_date', 'vehicle_id']),
            'products' => Product::all(['id', 'name']),
        ]);
    }

    public function update(UpdateSaleItemRequest $request, int $id, SaleItemService $service): RedirectResponse
    {
        $service->update($id, $request->validated());
        return redirect()->route('sale-items.show', $id);
    }

    public function destroy(int $id, SaleItemService $service): RedirectResponse
    {
        $service->destroy($id);
        return redirect()->route('sale-items.index');
    }
}
