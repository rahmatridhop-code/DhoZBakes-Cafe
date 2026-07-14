<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Category;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with('categoryRel')->where('is_active', true)->get();
        $orders = Order::with('items.product')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $settings = [
            'store_name' => Setting::get('store_name', 'DhozBakes'),
            'store_address' => Setting::get('store_address', 'Jl. Kenangan No. 123, Jakarta'),
            'store_phone' => Setting::get('store_phone', '0812-3456-7890'),
            'tax_rate' => (int) Setting::get('tax_rate', '10'),
            'service_fee' => (int) Setting::get('service_fee', '5'),
        ];

        return view('pos', compact('products', 'orders', 'settings', 'categories'));
    }
}
