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
        Schema::create('transactions', function (Blueprint $table) {
            // Primary key
            $table->id();

            // UUID for external identification
            $table->uuid('uuid')->unique();

            // Type of transaction (credit or debit)
            $table->enum('dr_or_cr', ['credit', 'debit']);

            // Currency, default is 'USDC'
            $table->string('currency')->default('USDC');

            // Transaction amount
            $table->decimal('amount', 10, 2);

            // Wallet balance after the transaction
            $table->decimal('wallet_balance', 10, 2);

            // Charge ID
            $table->string('charge_id');

            // Chargeable type (e.g., 'order', 'subscription')
            $table->string('chargeable_type');

            // Transaction description
            $table->string('description');

            // Transaction status (default, pending, successful)
            $table->enum('status', ['default', 'pending', 'successful'])->default('default');

            // Charges applied
            $table->string('charges');

            // Transaction reference
            $table->string('reference');

            // Gateway used for the transaction
            $table->string('gateway')->default('wallet');

            // Extra data (nullable)
            $table->text('extra_data')->nullable();

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};