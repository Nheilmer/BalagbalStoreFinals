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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            // table names start
            $table->integer('product_id')->unique();
            // Stock Quantity integer DEFAULT 0
            $table->integer('stock_quantity')->default(0);
            // reorder level integer DEFAULT 10
            $table->integer('reorder_level')->default(10);
            // max stock level integer
            $table->integer('max_stock_level')->default(100);
            // Last Restocked TIMESTAMP Nullable
            $table->timestamp('last_restocked')->nullable();
            // table names end
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
