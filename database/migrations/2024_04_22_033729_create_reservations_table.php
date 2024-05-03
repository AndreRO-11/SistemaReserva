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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->boolean('used')->default(false);
            $table->text('comment');
            $table->string('activity');
            $table->string('associated_project');
            $table->integer('assistants');
            $table->string('status')->default('PENDIENTE');
            $table->boolean('active')->default(true);
            $table->foreignId('client_id')->references('id')->on('clients');
            $table->foreignId('email_id')->references('id')->on('emails')->onDelete('cascade');
            $table->foreignId('place_id')->references('id')->on('places');
            $table->foreignId('user_id')->nullable()->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
