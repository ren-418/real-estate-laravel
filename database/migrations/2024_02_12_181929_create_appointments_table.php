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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('property_id'); 
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('agent_id')->references('user_id')->on('users');
            $table->foreign('property_id')->references('property_id')->on('properties'); 
            $table->dateTime('appointment_date');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
