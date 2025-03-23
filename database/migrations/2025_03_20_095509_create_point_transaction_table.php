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
        Schema::create('point_transactions', function (Blueprint $table) {
            // Primary key
            $table->id();

            // UUID for external identification
            $table->uuid('uuid')->unique();

            // Transaction type (credit or debit)
            $table->enum('dr_or_cr', ['credit', 'debit']);

            // Wallet relationship
            $table->unsignedBigInteger('wallet_id');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');

            // User relationship
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Transaction amounts
            $table->decimal('amount', 10, 2); // Transaction amount
            $table->decimal('point_balance', 10, 2); // Point balance after transaction

            // Charge details
            $table->string('charge_id'); // Charge ID
            $table->string('chargeable_type'); // Chargeable type (e.g., order, subscription)

            // Transaction description
            $table->text('description'); // Description of the transaction

            // Transaction status
            $table->enum('status', ['default', 'pending', 'successful'])->default('default');

            // Transaction reference
            $table->string('reference'); // Reference for the transaction

            // Extra data (nullable)
            $table->text('extra_data')->nullable(); // Additional data for the transaction

            // Currency
            $table->string('currency')->default('USDC'); // Currency of the transaction

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
        Schema::dropIfExists('point_transactions');
    }
};