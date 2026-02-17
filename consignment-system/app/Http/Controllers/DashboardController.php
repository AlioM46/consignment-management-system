<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Vehicle;
use App\Models\Vendor;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'vendors' => Vendor::count(),
                'drivers' => Driver::count(),
                'vehicles' => Vehicle::count(),
                'products' => Product::count(),
                'deliveries' => Delivery::count(),
                'sales' => Sale::count(),
                'invoices' => Invoice::count(),
                'totalSales' => Sale::sum('total_amount'),
            ],
            'recentDeliveries' => Delivery::with('vehicle')
                ->latest()
                ->take(5)
                ->get(),
            'recentSales' => Sale::with('vehicle')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
