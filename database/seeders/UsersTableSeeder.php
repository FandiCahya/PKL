<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'alamat' => 'Kediri',
            'no_hp' => '1234567890',
            'image' => 'default.png',
            'password' => Hash::make('1'),
            // 'confirm_pass' => Hash::make('1'),
            'role' => 'admin'
        ]);

        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'alamat' => 'Kediri',
            'no_hp' => '0987654321',
            'image' => 'default.png',
            'password' => Hash::make('1'),
            // 'confirm_pass' => Hash::make('1'),
            'role' => 'users'
        ]);
    }
}
