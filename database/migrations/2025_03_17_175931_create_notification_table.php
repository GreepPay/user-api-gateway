<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('auth_user_id');
            $table->string('type'); // 'email' or 'push'
            $table->string('title');
            $table->text('content');
            $table->string('email')->nullable();
            $table->boolean('is_read')->default(false);
            $table->string('delivery_status'); // 'pending', 'sent', 'delivered', 'failed'
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
