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
                'date' => Carbon::parse('2024-07-01'),
                'time' => Carbon::parse('06:00:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Boxing Class',
                'image' => 'weekend_special.jpg',
                'deskripsi' => 'Boxing Class',
                'date' => Carbon::parse('2024-07-05'),
                'time' => Carbon::parse('00:00:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Taekwondo Class',
                'image' => 'happy_hour.jpg',
                'deskripsi' => 'Taekwondo Class',
                'date' => Carbon::parse('2024-07-01'),
                'time' => Carbon::parse('14:00:00'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
