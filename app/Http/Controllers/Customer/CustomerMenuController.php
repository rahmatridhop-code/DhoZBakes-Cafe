<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class CustomerMenuController extends Controller
{
    public function index()
    {
        $products = Product::with('categoryRel')
            ->where('is_active', true)
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $settings = [
            'store_name' => \App\Models\Setting::get('store_name', 'DhozBakes'),
            'store_address' => \App\Models\Setting::get('store_address', 'Jl. Kenangan No. 123, Jakarta'),
            'store_phone' => \App\Models\Setting::get('store_phone', '0812-3456-7890'),
            'tax_rate' => (int) \App\Models\Setting::get('tax_rate', '10'),
            'service_fee' => (int) \App\Models\Setting::get('service_fee', '5'),
        ];

        return view('customer.menu.index', compact('products', 'categories', 'settings'));
    }
}
