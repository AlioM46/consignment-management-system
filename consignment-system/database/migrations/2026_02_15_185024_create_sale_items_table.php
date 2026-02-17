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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            // TODO : add sale_id and product_id foreign keys + quantity and price
             $table->foreignId('sale_id')->constrained()->onDelete('cascade');
             $table->foreignId('product_id')->constrained()->onDelete('cascade');
             $table->integer('quantity')->default(1);
             $table->decimal('price', 18, 2); // price at time of sale
             
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
