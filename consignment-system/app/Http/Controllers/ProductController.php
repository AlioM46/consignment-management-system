<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\Product\ProductService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(ProductService $service): Response
    {
        return Inertia::render('Products/Index', [
            'products' => $service->index(),
        ]);
    }

    public function store(StoreProductRequest $request, ProductService $service): RedirectResponse
    {
        $service->store($request->validated());
        return redirect()->route('products.index');
    }

    public function show(int $id, ProductService $service): Response
    {
        return Inertia::render('Products/Show', [
            'product' => $service->show($id),
        ]);
    }

    public function update(UpdateProductRequest $request, int $id, ProductService $service): RedirectResponse
    {
        $service->update($id, $request->validated());
        return redirect()->route('products.show', $id);
    }

    public function destroy(int $id, ProductService $service): RedirectResponse
    {
        $service->destroy($id);
        return redirect()->route('products.index');
    }
}
