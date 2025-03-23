<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBanksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_banks', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique(); // UUID for external identification
            $table->string('bank_code'); // Bank code
            $table->string('bank_name'); // Bank name
            $table->string('account_no'); // Bank account number
            $table->string('currency')->default('USD'); // Currency of the bank account
            $table->boolean('is_verified')->default(false); // Verification status
            $table->json('meta_data')->nullable(); // Additional metadata (optional)
            $table->timestamps(); // created_at and updated_at
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_banks');
    }
}