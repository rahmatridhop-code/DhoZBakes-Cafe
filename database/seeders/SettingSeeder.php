<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('store_name', 'DhozBakes');
        Setting::set('store_address', 'Jl. Kenangan No. 123, Jakarta');
        Setting::set('store_phone', '0812-3456-7890');
        Setting::set('tax_rate', '10');
        Setting::set('service_fee', '5');
    }
}
