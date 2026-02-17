<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->index('sale_date', 'sales_sale_date_idx');
            $table->index(['vehicle_id', 'sale_date'], 'sales_vehicle_sale_date_idx');
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->index('delivery_date', 'deliveries_delivery_date_idx');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->index('vehicle_id', 'invoices_vehicle_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('sales_sale_date_idx');
            $table->dropIndex('sales_vehicle_sale_date_idx');
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropIndex('deliveries_delivery_date_idx');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('invoices_vehicle_id_idx');
        });
    }
};
