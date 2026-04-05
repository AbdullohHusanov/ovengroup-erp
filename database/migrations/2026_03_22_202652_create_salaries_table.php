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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('year')->default(date('Y'));
            $table->integer('month')->default(date('m'));
            $table->integer('product_count')->default(0);
            $table->integer('sum')->default(0);
            $table->integer('debt_sum')->default(0);
            $table->integer('payed_sum')->default(0);
            $table->integer('total_sum')->default(0);
            $table->enum('status', ['open','close']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
