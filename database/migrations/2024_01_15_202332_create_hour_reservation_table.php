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
        Schema::create('hour_reservation', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hour_id')->references('id')->on('hours')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hour_reservation');
    }
};
