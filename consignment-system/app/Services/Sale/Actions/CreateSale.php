<?php

namespace App\Services\Sale\Actions;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItems;
use Illuminate\Support\Facades\DB;

class CreateSale
{
    public function handle(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $products = $data['products'] ?? [];
            $totalAmount = 0;
            $saleItemsData = [];

            foreach ($products as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $lineTotal = $product->default_price * $item['quantity'];
                    $totalAmount += $lineTotal;

                    $saleItemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->default_price,
                    ];
                }
            }

            $data['total_amount'] = $totalAmount;

            // Remove products from data before creating Sale
            $saleData = collect($data)->except('products')->toArray();

            $sale = Sale::create($saleData);

            foreach ($saleItemsData as $itemData) {
                $sale->items()->create($itemData);
            }

            return $sale;
        });
    }
}
