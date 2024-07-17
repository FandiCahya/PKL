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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promotions_id');
            $table->unsignedBigInteger('instrukturs_id');
            $table->unsignedBigInteger('rooms_id');
            $table->date('tgl');
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('promotions_id')->references('id')->on('promotions')->onDelete('cascade');
            $table->foreign('instrukturs_id')->references('id')->on('instrukturs')->onDelete('cascade');
            $table->foreign('rooms_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['promotions_id']);
            $table->dropForeign(['instrukturs_id']);
            $table->dropForeign(['rooms_id']);
        });

        Schema::dropIfExists('schedules');
    }
};
