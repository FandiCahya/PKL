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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('room_id')->nullable()->constrained('rooms');
            $table->foreignId('promotion_id')->nullable()->constrained('promotions');
            $table->date('tgl');
            $table->time('start_time');
            // $table->time('estimasi')->nullable();
            $table->time('end_time')->nullable();
            $table->string('qrcode',255);
            $table->enum('status', ['Booked', 'Pending', 'Rejected','Finished'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
