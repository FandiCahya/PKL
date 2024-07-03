<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::table('users')->pluck('id');
        $rooms = DB::table('rooms')->pluck('id');
        $promotions = DB::table('promotions')->pluck('id');

        for ($i = 0; $i < 10; $i++) {
            DB::table('bookings')->insert([
                'user_id' => $users->random(),
                'room_id' => $i % 2 == 0 ? $rooms->random() : null, // Nullable for room_id
                'promotion_id' => $i % 3 == 0 ? $promotions->random() : null, // Nullable for promotion_id
                'tgl' => now()->addDays(rand(1, 30)),
                'start_time' => now()->addHours(rand(1, 12))->format('H:i:s'),
                'end_time' => now()->addHours(rand(13, 24))->format('H:i:s'),
                'qrcode' => Str::random(10),
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
