<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
    'name' => 'shubham aswal',
    'gender' => 'Male',
    'email' => 'shubhamaswal048@gmail.com',
    'mobile' => '9548102836',
    'role' => 'admin',
    'address' => 'ramnagar nainital',
    'branch' => 'Nainital',
    'password' => Hash::make('aswal@17'),
    'salary' => 1,
    'designation' => 'Boss',
    'approval' => 'approve',
    'joining_date' => '2026-06-13 12:02:55',
    'status' => 'active',
]);

    }
}
