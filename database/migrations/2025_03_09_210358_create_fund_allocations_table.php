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
        Schema::create('fund_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_id')->constrained()->onDelete('cascade');
            $table->foreignId('fund_id')->constrained()->onDelete('restrict');
            $table->unsignedBigInteger('amount')->comment('Amount in pennies');
            $table->timestamps();

            $table->unique(['investment_id', 'fund_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_allocations');
    }
};
