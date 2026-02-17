<?php

namespace App\Services\Invoice\Actions;

use App\Models\Invoice;
use App\Models\Sale;
use App\Models\Delivery;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class UpdateInvoice
{
    public function handle(int $id, array $data): Invoice
    {
        return DB::transaction(function () use ($id, $data) {
            $invoice = Invoice::findOrFail($id);

            // Check if any calculation-related fields are being updated
            $needsRecalculation = false;
            $calcFields = ['vehicle_id', 'start_date', 'end_date', 'commission_rate', 'expenses'];

            foreach ($calcFields as $field) {
                if (array_key_exists($field, $data) && $data[$field] != $invoice->$field) {
                    $needsRecalculation = true;
                    break;
                }
            }

            if ($needsRecalculation) {
                // Use new values if present, else fall back to existing invoice values
                $vehicleId = $data['vehicle_id'] ?? $invoice->vehicle_id;
                $startDate = $data['start_date'] ?? $invoice->start_date;
                $endDate = $data['end_date'] ?? $invoice->end_date;
                $commissionRate = $data['commission_rate'] ?? $invoice->commission_rate;
                $expenses = $data['expenses'] ?? $invoice->expenses;

                // Update vendor if vehicle changed
                if (isset($data['vehicle_id'])) {
                    $vehicle = Vehicle::find($vehicleId);
                    if ($vehicle) {
                        $data['vendor_id'] = $vehicle->vendor_id;
                    }
                }

                // Recalculate totals
                $data['total_sales'] = Sale::where('vehicle_id', $vehicleId)
                    ->whereBetween('sale_date', [$startDate, $endDate])
                    ->sum('total_amount');



                $data['commission_amount'] = $data['total_sales'] * ($commissionRate / 100);

                // Net Amount = (Sales - Commission) - (Deliveries + Expenses)
                $data['net_amount'] = ($data['total_sales'] - $data['commission_amount'] - $expenses);
            }

            $invoice->update($data);

            return $invoice->refresh();
        });
    }
}
