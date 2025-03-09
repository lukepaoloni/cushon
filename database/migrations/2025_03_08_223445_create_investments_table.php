<?php

use App\CustomerType;
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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('amount')->comment('Amount in pennies');
            $table->enum('customer_type', array_column(CustomerType::cases(), 'value'));
            $table->timestamps();

            // Create an index for faster lookup by customer type
            $table->index('customer_type');

            // Add a composite index for common queries
            $table->index(['user_id', 'customer_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
