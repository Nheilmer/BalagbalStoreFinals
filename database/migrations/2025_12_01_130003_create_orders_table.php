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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // table names start
            $table->integer('customer_id');
            // Order date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('order_date')->useCurrent();
            // Order status status ENUM('pending', 'processing', 'completed', 'cancelled')
            $table->enum('order_status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 12, 2);
            // Payment status ENUM('pending', 'paid', 'failed')
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            // table names end
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
