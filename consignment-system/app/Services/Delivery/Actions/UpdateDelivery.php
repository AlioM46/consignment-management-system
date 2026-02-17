<?php

namespace App\Services\Delivery\Actions;

use App\Models\Delivery;
use App\Models\DeliveryItems;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class UpdateDelivery
{
    public function handle(int $id, array $data): Delivery
    {
        return DB::transaction(function () use ($id, $data) {
            $delivery = Delivery::findOrFail($id);

            if (isset($data['products'])) {
                $products = $data['products'];
                $totalValue = 0;
                $totalItems = 0;
                $deliveryItemsData = [];

                foreach ($products as $item) {
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $itemQty = $item['quantity'];
                        $lineTotal = $product->default_price * $itemQty;
                        $totalValue += $lineTotal;
                        $totalItems += $itemQty;

                        $deliveryItemsData[] = [
                            'product_id' => $product->id,
                            'quantity' => $itemQty,
                            'price' => $product->default_price,
                        ];
                    }
                }

                $data['total_value'] = $totalValue;
                $data['total_items'] = $totalItems;

                // Remove products from data before updating Delivery
                $deliveryData = collect($data)->except('products')->toArray();
                $delivery->update($deliveryData);

                // Sync items: delete old, create new
                $delivery->items()->delete();
                foreach ($deliveryItemsData as $itemData) {
                    $delivery->items()->create($itemData);
                }
            } else {
                $delivery->update($data);
            }

            return $delivery->refresh();
        });
    }
}
