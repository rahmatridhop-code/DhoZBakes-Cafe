<?php

namespace Database\Seeders;

use App\Models\CafeTable;
use Illuminate\Database\Seeder;

class CafeTableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['name' => 'Meja 1', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Meja 2', 'capacity' => 2, 'status' => 'available'],
            ['name' => 'Meja 3', 'capacity' => 4, 'status' => 'available'],
            ['name' => 'Meja 4', 'capacity' => 4, 'status' => 'available'],
            ['name' => 'Meja 5', 'capacity' => 6, 'status' => 'available'],
            ['name' => 'Meja 6', 'capacity' => 8, 'status' => 'available'],
            ['name' => 'Meja VIP 1', 'capacity' => 10, 'status' => 'available'],
            ['name' => 'Outdoor 1', 'capacity' => 4, 'status' => 'available'],
            ['name' => 'Outdoor 2', 'capacity' => 4, 'status' => 'available'],
            ['name' => 'Bar 1', 'capacity' => 1, 'status' => 'available'],
        ];

        foreach ($tables as $table) {
            CafeTable::create($table);
        }
    }
}
