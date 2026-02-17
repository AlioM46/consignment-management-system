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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            // TODO : add vendor_id and vehicle_id foreign keys + invoice_date + start_date + end_date + total_sales + commision_rate + commision_amount + expenses + net_amount + invoice_number, total_amount, and status
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->date('invoice_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_sales', 18, 2);
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('commission_amount', 18, 2);
            $table->decimal('expenses', 18, 2);
            $table->decimal('net_amount', 18, 2);
            $table->unsignedTinyInteger('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
