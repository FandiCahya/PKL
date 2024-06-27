<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rooms')->insert([
            'nama' => 'Room 1',
            'kapasitas' => 20,
            'availability' => true,
            'harga' => 100000
        ]);

        DB::table('rooms')->insert([
            'nama' => 'Room 2',
            'kapasitas' => 15,
            'availability' => true,
            'harga' => 120000
        ]);
    }
}
