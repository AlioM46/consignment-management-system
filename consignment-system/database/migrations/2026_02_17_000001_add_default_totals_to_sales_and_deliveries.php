<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('sales')) {
            DB::statement('ALTER TABLE sales MODIFY total_amount DECIMAL(18,2) NOT NULL DEFAULT 0');
        }

        if (Schema::hasTable('deliveries')) {
            DB::statement('ALTER TABLE deliveries MODIFY total_items INT NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE deliveries MODIFY total_value DECIMAL(18,2) NOT NULL DEFAULT 0');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sales')) {
            DB::statement('ALTER TABLE sales ALTER COLUMN total_amount DROP DEFAULT');
        }

        if (Schema::hasTable('deliveries')) {
            DB::statement('ALTER TABLE deliveries ALTER COLUMN total_items DROP DEFAULT');
            DB::statement('ALTER TABLE deliveries ALTER COLUMN total_value DROP DEFAULT');
        }
    }
};
