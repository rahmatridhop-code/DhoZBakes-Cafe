<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Ridho',
            'email' => 'ridho@customer.com',
            'phone' => '0812-3456-7890',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@customer.com',
            'phone' => '0856-7890-1234',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);
    }
}
