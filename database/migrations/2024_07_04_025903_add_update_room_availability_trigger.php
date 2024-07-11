<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the trigger
        DB::unprepared('
            CREATE TRIGGER update_room_availability AFTER UPDATE ON rooms
            FOR EACH ROW
            BEGIN
                IF NEW.kapasitas = 0 THEN
                    UPDATE rooms
                    SET availability = FALSE
                    WHERE id = NEW.id;
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the trigger
        DB::unprepared('DROP TRIGGER IF EXISTS update_room_availability');
    }
};
