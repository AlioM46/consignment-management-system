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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->date("delivery_date");
            $table->unsignedTinyInteger('status')->default(1);
            $table->integer("total_items");
            $table->decimal("total_value", 18, 2);
            $table->foreignId('vehicle_id')->constrained('vehicles');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
