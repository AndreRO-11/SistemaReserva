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
        Schema::create('places', function (Blueprint $table) {
            $table->id();

            $table->string('code');
            $table->integer('capacity');
            $table->integer('floor');
            $table->boolean('active');

            $table->unsignedBigInteger('types_id');
            $table->unsignedBigInteger('buildings_id');
            $table->unsignedBigInteger('seats_id');

            $table->timestamps();

            $table->foreign('types_id')->references('id')->on('types');
            $table->foreign('buildings_id')->references('id')->on('buildings');
            $table->foreign('seats_id')->references('id')->on('seats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
