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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // table name start
            // category name varchar(100) Unique
            $table->string('name', 100)->unique();
            // Description text nullable
            $table->text('description')->nullable();
            // Is active boolean default true
            $table->boolean('is_active')->default(true);
            // table name end
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
