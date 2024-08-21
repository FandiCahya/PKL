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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->string('deskripsi');
            $table->date('tgl')->nullable();
            $table->time('waktu')->nullable();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('instruktur_id');
            $table->timestamps();
            $table->softDeletes();

            // Adding foreign key constraints
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('instruktur_id')->references('id')->on('instrukturs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
