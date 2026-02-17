<?php

namespace Database\Seeders;

use App\Enums\enDeliveryStatus;
use App\Enums\enInvoiceStatus;
use App\Models\Delivery;
use App\Models\DeliveryItems;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItems;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'ali@example.com',
            'password' => Hash::make('123456'),
        ]);

        $vendors = collect([
            Vendor::create([
                'name' => 'Acme Supplies',
                'email' => 'vendor@example.com',
                'phone' => '555-100-2000',
                'address' => '100 Market St',
            ]),
            Vendor::create([
                'name' => 'Northline Traders',
                'email' => 'northline@example.com',
                'phone' => '555-200-3000',
                'address' => '200 River Rd',
            ]),
        ]);

        $drivers = collect([
            Driver::create([
                'name' => 'Jordan Miles',
                'phone' => '555-333-8888',
            ]),
            Driver::create([
                'name' => 'Casey Park',
                'phone' => '555-444-9999',
            ]),
        ]);

        $vehicles = collect([
            Vehicle::create([
                'plate_number' => 'ABC-1234',
                'Vehicle_model' => 'Ford Transit',
                'driver_id' => 1,
                'vendor_id' => 1,
            ]),
            Vehicle::create([
                'plate_number' => 'XYZ-5678',
                'Vehicle_model' => 'Mercedes Sprinter',
                'driver_id' => 2,
                'vendor_id' => 2,
            ]),
            Vehicle::create([
                'plate_number' => 'QRS-2468',
                'Vehicle_model' => 'Ram ProMaster',
                'driver_id' => 1,
                'vendor_id' => 2,
            ]),
        ]);

        $products = collect([
            Product::create([
                'name' => 'Energy Bar',
                'description' => 'High-protein snack bar',
                'sku' => 'BAR-1001',
                'default_price' => 10.00,
                'cost_price' => 6.00,
            ]),
            Product::create([
                'name' => 'Sparkling Water',
                'description' => '12oz citrus sparkling water',
                'sku' => 'WTR-2001',
                'default_price' => 20.00,
                'cost_price' => 12.00,
            ]),
            Product::create([
                'name' => 'Trail Mix',
                'description' => 'Mixed nuts and dried fruit',
                'sku' => 'MIX-3001',
                'default_price' => 15.00,
                'cost_price' => 9.00,
            ]),
            Product::create([
                'name' => 'Cold Brew',
                'description' => '12oz cold brew coffee',
                'sku' => 'CBR-4001',
                'default_price' => 25.00,
                'cost_price' => 14.00,
            ]),
        ]);

        foreach ($vehicles as $vehicle) {
            // Deliveries + items
            for ($i = 0; $i < 2; $i++) {
                $delivery = Delivery::create([
                    'name' => 'Delivery ' . ($i + 1) . ' - ' . $vehicle->plate_number,
                    'delivery_date' => now()->subDays(7 - $i)->toDateString(),
                    'status' => enDeliveryStatus::Arrived->value,
                    'total_items' => 0,
                    'total_value' => 0,
                    'vehicle_id' => $vehicle->id,
                ]);

                $totalItems = 0;
                $totalValue = 0;

                foreach ($products->random(2) as $product) {
                    $quantity = 1 + $i;
                    $price = $product->cost_price;

                    DeliveryItems::create([
                        'delivery_id' => $delivery->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);

                    $totalItems += $quantity;
                    $totalValue += $quantity * $price;
                }

                Delivery::whereKey($delivery->id)->update([
                    'total_items' => $totalItems,
                    'total_value' => $totalValue,
                ]);
            }

            // Sales + items
            for ($i = 0; $i < 3; $i++) {
                $sale = Sale::create([
                    'vehicle_id' => $vehicle->id,
                    'sale_date' => now()->subDays(5 - $i)->toDateString(),
                    'total_amount' => 0,
                    'payment_method' => $i % 2 === 0 ? 'cash' : 'card',
                ]);

                $totalAmount = 0;

                foreach ($products->random(2) as $product) {
                    $quantity = 1 + $i;
                    $price = $product->default_price;

                    SaleItems::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);

                    $totalAmount += $quantity * $price;
                }

                Sale::whereKey($sale->id)->update([
                    'total_amount' => $totalAmount,
                ]);
            }
        }

        foreach ($vehicles as $vehicle) {
            $startDate = now()->subDays(14)->toDateString();
            $endDate = now()->toDateString();

            $totalSales = (float) Sale::query()
                ->where('vehicle_id', $vehicle->id)
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->sum('total_amount');

            $commissionRate = 10.00;
            $commissionAmount = $totalSales * ($commissionRate / 100);
            $expenses = 5.00;
            $netAmount = $totalSales - $commissionAmount - $expenses;

            Invoice::create([
                'vendor_id' => $vehicle->vendor_id,
                'vehicle_id' => $vehicle->id,
                'invoice_date' => now()->toDateString(),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_sales' => round($totalSales, 2),
                'commission_rate' => $commissionRate,
                'commission_amount' => round($commissionAmount, 2),
                'expenses' => $expenses,
                'net_amount' => round($netAmount, 2),
                'status' => enInvoiceStatus::Pending->value,
            ]);
        }
    }
}
