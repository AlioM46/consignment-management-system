<?php

namespace App\Services\Invoice\Actions;

use App\Enums\enInvoiceStatus;
use App\Models\Delivery;
use App\Models\Invoice;
use App\Models\Sale;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class CreateInvoice
{
    public function handle(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $vehicleId = $data['vehicle_id'];
            $startDate = $data['start_date'];
            $endDate = $data['end_date'];
            $commissionRate = $data['commission_rate'];
            $expenses = $data['expenses'];

            $vehicle = Vehicle::findOrFail($vehicleId);

            // Calculate Total Sales
            $totalSales = Sale::where('vehicle_id', $vehicleId)
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->sum('total_amount');

            // Calculate Total Deliveries
            $totalDeliveries = Delivery::where('vehicle_id', $vehicleId)
                ->whereBetween('delivery_date', [$startDate, $endDate])
                ->sum('total_value'); // Use total_value, assuming it's available on Delivery model

            // Calculate Commission
            $commissionAmount = $totalSales * ($commissionRate / 100);

            // Calculate Net Amount
            // Formula: (Sales - Commission) - (Deliveries + Expenses)
            // Or typically: Sales - Commission - Deliveries - Expenses
            $netAmount = $totalSales - $commissionAmount - $expenses;

            $invoiceData = [
                'vehicle_id' => $vehicleId,
                'vendor_id' => $vehicle->vendor_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'commission_rate' => $commissionRate,
                'expenses' => $expenses,
                'total_sales' => $totalSales,
                'commission_amount' => $commissionAmount,
                'net_amount' => $netAmount,
                'status' => enInvoiceStatus::Pending->value,
                'invoice_date' => now(),
            ];

            return Invoice::create($invoiceData);
        });
    }
}
