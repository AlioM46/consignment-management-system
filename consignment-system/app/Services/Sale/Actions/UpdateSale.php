<?php

namespace App\Services\Sale\Actions;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItems;
use Illuminate\Support\Facades\DB;

class UpdateSale
{
    public function handle(int $id, array $data): Sale
    {
        return DB::transaction(function () use ($id, $data) {
            $sale = Sale::findOrFail($id);

            if (isset($data['products'])) {
                $products = $data['products'];
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

                // Remove products from data before updating Sale
                $saleData = collect($data)->except('products')->toArray();
                $sale->update($saleData);

                // Sync items: delete old, create new
                $sale->items()->delete();
                foreach ($saleItemsData as $itemData) {
                    $sale->items()->create($itemData);
                }
            } else {
                $sale->update($data);
            }

            return $sale->refresh();
        });
    }
}
