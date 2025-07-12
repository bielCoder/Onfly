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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('travelling_id');
            $table->timestamp('departure_date')->nullable(false);
            $table->timestamp('return_date')->nullable(false);
            $table->string('status');
            $table->foreign('user_id') -> references('id') -> on('users');
            $table->foreign('travelling_id') -> references('id') -> on('travellings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
