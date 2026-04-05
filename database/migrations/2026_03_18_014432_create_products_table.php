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
            $table->string('model_name')->unique();
            $table->string('image')->nullable();
            $table->boolean('is_stamped');
            $table->integer('total_product_count')->default(0);
            $table->integer('total_semi_finished_product_count')->default(0);
            $table->integer('total_complated_product_count')->default(0);
            $table->integer('total_sold_product_count')->default(0);
            $table->integer('welded_product_count')->default(0);
            $table->integer('checked_product_count')->default(0);
            $table->integer('cleaned_product_count')->default(0);
            $table->integer('stamped_product_count')->default(0);
            $table->integer('semi_finished_product_price')->default(0);
            $table->integer('welder_price')->default(0);
            $table->integer('inspector_price')->default(0);
            $table->integer('cleaner_price')->default(0);
            $table->integer('stamper_price')->default(0);
            $table->integer('selling_price')->default(0);

            $table->timestamps();
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
