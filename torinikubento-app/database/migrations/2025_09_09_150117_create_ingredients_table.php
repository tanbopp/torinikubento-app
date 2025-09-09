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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_japanese')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['fresh', 'dry', 'frozen', 'liquid']);
            $table->enum('unit', ['gram', 'kg', 'ml', 'liter', 'pcs']);
            $table->decimal('cost_per_unit', 10, 4);
            $table->decimal('current_stock', 10, 2)->default(0);
            $table->decimal('minimum_stock', 10, 2)->default(0);
            $table->decimal('maximum_stock', 10, 2)->nullable();
            $table->json('supplier_info')->nullable();
            $table->integer('shelf_life_days')->nullable();
            $table->text('storage_instruction')->nullable();
            $table->json('allergen_info')->nullable();
            $table->json('nutritional_value')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('type');
            $table->index('is_active');
            $table->index(['current_stock', 'minimum_stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
