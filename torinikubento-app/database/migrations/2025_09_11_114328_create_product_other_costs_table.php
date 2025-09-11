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
        Schema::create('product_other_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('other_cost_id')->constrained()->onDelete('cascade');
            $table->decimal('custom_value', 8, 4)->nullable(); // override default value if needed
            $table->timestamps();
            
            $table->unique(['product_id', 'other_cost_id']);
            $table->index('product_id');
            $table->index('other_cost_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_other_costs');
    }
};
