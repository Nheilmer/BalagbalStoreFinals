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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // table names start
            $table->string('name', 200);
            $table->text('description');
            $table->integer('category_id');
            $table->decimal('cost_price', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->boolean('is_active')->default(true);
            // table names end
            $table->timestamps();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
