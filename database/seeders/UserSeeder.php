<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        DB::table('users')->insert([
            'id' => 1,
            'first_name' => 'Raven',
            'middle_initial' => 'C',
            'last_name' => 'Campus',
            'email' => 'admin@gmail.com',
            'role' => 2,
            'activate' => 1,
            'password' => bcrypt('admin'),
        ]);
    }
}
