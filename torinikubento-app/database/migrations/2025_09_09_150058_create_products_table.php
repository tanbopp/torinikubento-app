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
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('name_japanese')->nullable();
            $table->text('description')->nullable();
            $table->json('allergen_info')->nullable();
            $table->string('image_path')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('promo_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->decimal('margin_percentage', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_seasonal')->default(false);
            $table->boolean('is_limited_edition')->default(false);
            $table->integer('daily_limit')->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('preparation_time')->default(0); // in minutes
            $table->json('nutritional_info')->nullable();
            $table->integer('spice_level')->default(0); // 0-5
            $table->date('season_start_date')->nullable();
            $table->date('season_end_date')->nullable();
            $table->integer('popularity_score')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('category_id');
            $table->index('is_active');
            $table->index('is_available');
            $table->index('is_seasonal');
            $table->index(['season_start_date', 'season_end_date']);
            $table->index('popularity_score');
            $table->index('margin_percentage');
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
