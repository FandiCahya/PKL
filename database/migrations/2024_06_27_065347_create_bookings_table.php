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
            $table->enum('booking_type',['room','class'])->nullable();
            $table->date('tgl');
            $table->foreignId('time_slot_id')->nullable()->constrained('time_slots')->onDelete('cascade');
            $table->integer('harga')->nullable();
            $table->string('qrcode',255)->nullable();
            $table->enum('status', ['Booked', 'Pending', 'Rejected'])->default('Pending');
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
