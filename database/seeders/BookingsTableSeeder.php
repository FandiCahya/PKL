<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('booking_rooms')->insert([
            [
                'user_id' => 1, // Pastikan ID pengguna dan ruangan sudah ada
                'room_id' => 1,
                'tgl' => Carbon::parse('2024-07-01'),
                'start_time' => Carbon::parse('08:00:00'),
                'end_time' => Carbon::parse('10:00:00'),
                'status' => 'Booked',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'room_id' => 1,
                'tgl' => Carbon::parse('2024-07-02'),
                'start_time' => Carbon::parse('14:00:00'),
                'end_time' => Carbon::parse('16:00:00'),
                'status' => 'Pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 1,
                'room_id' => 2,
                'tgl' => Carbon::parse('2024-07-03'),
                'start_time' => Carbon::parse('10:00:00'),
                'end_time' => Carbon::parse('12:00:00'),
                'status' => 'Rejected',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
