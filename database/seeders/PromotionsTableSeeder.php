<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromotionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('promotions')->insert([
            [
                'name' => 'Yoga Class',
                'image' => 'early_bird_discount.jpg',
                'deskripsi' => 'Yoga Class',
                'tgl' => now(),
                'waktu' => now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Boxing Class',
                'image' => 'weekend_special.jpg',
                'deskripsi' => 'Boxing Class',
                'tgl' => now(),
                'waktu' => now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Taekwondo Class',
                'image' => 'happy_hour.jpg',
                'deskripsi' => 'Taekwondo Class',
                'tgl' => now(),
                'waktu' => now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
