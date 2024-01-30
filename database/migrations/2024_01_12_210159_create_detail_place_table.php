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
        Schema::create('detail_place', function (Blueprint $table) {
            $table->id();

            $table->foreignId('detail_id')->references('id')->on('details')->onDelete('cascade')->onUpdate('cascade')->nullable();
            $table->foreignId('place_id')->references('id')->on('places')->onDelete('cascade')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_place');
    }
};
