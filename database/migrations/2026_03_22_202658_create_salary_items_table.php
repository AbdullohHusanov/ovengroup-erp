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
        Schema::create('salary_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_id')->constrained('salaries');
            $table->integer('amount')->default(0);
            $table->enum('type', ['salary', 'debt']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_items');
    }
};
