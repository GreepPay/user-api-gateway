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
        Schema::create('wallets', function (Blueprint $table) {
            // Primary key
            $table->id();

            // UUID for external identification
            $table->uuid('uuid')->unique();

            // Wallet balances and amounts
            $table->decimal('total_balance', 10, 2)->default(0);
            $table->decimal('point_balance', 10, 2)->default(0);
            $table->decimal('credited_amount', 10, 2)->default(0);
            $table->decimal('debited_amount', 10, 2)->default(0);
            $table->decimal('locked_balance', 10, 2)->default(0);
            $table->decimal('credited_point_amount', 10, 2)->default(0);
            $table->decimal('debited_point_amount', 10, 2)->default(0);
            $table->decimal('cash_point_balance', 10, 2)->default(0);
            $table->decimal('cash_per_point', 10, 2)->default(0);

            // User relationship
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Wallet account and currency
            $table->string('wallet_account')->nullable();
            $table->string('currency')->default('USDC');

            // Wallet state
            $table->enum('state', ['active', 'archived'])->default('active');

            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // For soft deletion support
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};