<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'store_name' => Setting::get('store_name', 'DhozBakes'),
            'store_address' => Setting::get('store_address', 'Jl. Kenangan No. 123, Jakarta'),
            'store_phone' => Setting::get('store_phone', '0812-3456-7890'),
            'tax_rate' => Setting::get('tax_rate', '10'),
            'service_fee' => Setting::get('service_fee', '5'),
        ];

        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'nullable|string',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string',
            'tax_rate' => 'nullable|integer|min:0|max:100',
            'service_fee' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return response()->json(['message' => 'Pengaturan disimpan']);
    }
}
