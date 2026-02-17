<?php

namespace App\Services\Invoice\Actions;

use App\Models\Invoice;
use App\Models\Sale;
use App\Models\Vehicle;

class CreateInvoice
{
    public function handle(array $data): Invoice
    {
        $totalSales = (float) Sale::query()
            ->where('vehicle_id', $data['vehicle_id'])
            ->whereBetween('sale_date', [$data['start_date'], $data['end_date']])
            ->sum('total_amount');

        $commissionRate = (float) $data['commission_rate'];
        $commissionAmount = $totalSales * ($commissionRate / 100);
        $expenses = isset($data['expenses']) ? (float) $data['expenses'] : 0.0;
        $netAmount = $totalSales - $commissionAmount - $expenses;

        $data['total_sales'] = round($totalSales, 2);
        $data['commission_amount'] = round($commissionAmount, 2);
        $data['net_amount'] = round($netAmount, 2);
        $data['invoice_date'] = now()->toDateString();  
        $data['status'] = 1; // Default to 'pending'    
        
        $data['vendor_id'] = Vehicle::query()
            ->whereKey($data['vehicle_id'])
            ->value('vendor_id') ?? $data['vendor_id'] ?? null;

        return Invoice::create($data);
    }
}
