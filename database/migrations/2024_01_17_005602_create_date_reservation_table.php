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
        Schema::create('date_reservation', function (Blueprint $table) {
            $table->id();

            $table->foreignId('date_id')->references('id')->on('dates')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->foreignId('reservation_id')->references('id')->on('reservations')->onDelete('cascade')->onUpdate('cascade')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('date_reservation');
    }
};
