<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('promotions')->insert([
            'image' => 'promo1.png',
            'name' => 'Yoga',
            'deskripsi' => 'Discount 50% for new members'
        ]);

        DB::table('promotions')->insert([
            'image' => 'promo2.png',
            'name' => 'Boxing',
            'deskripsi' => 'Free first class for all new users'
        ]);
    }
}
