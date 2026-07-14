<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getStats();
        $recentOrders = Order::with(['user', 'items.product'])->latest()->take(10)->get();
        $topProducts = Product::withCount('orderItems')->orderByDesc('order_items_count')->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentOrders', 'topProducts'));
    }

    public function realtimeStats()
    {
        return response()->json($this->getStats());
    }

    public function report(Request $request)
    {
        $dateFrom = $request->query('from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->query('to', now()->format('Y-m-d'));

        $orders = Order::with(['user', 'items.product'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->orderBy('created_at', 'asc')
            ->get();

        $stats = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total'),
            'total_items_sold' => $orders->sum(fn($o) => $o->items->sum('qty')),
            'cash_payments' => $orders->where('payment_method', 'cash')->count(),
            'qris_payments' => $orders->where('payment_method', 'qris')->count(),
            'dine_in' => $orders->where('order_type', 'dine_in')->count(),
            'takeaway' => $orders->where('order_type', 'takeaway')->count(),
        ];

        $dailyRevenue = $orders->groupBy(fn($o) => $o->created_at->format('d M Y'))
            ->map(fn($group) => [
                'date' => $group->first()->created_at->format('d M Y'),
                'orders' => $group->count(),
                'revenue' => $group->sum('total'),
                'items' => $group->sum(fn($o) => $o->items->sum('qty')),
            ])->values();

        $productStats = $orders->flatMap(fn($o) => $o->items)
            ->groupBy('product_id')
            ->map(fn($items) => [
                'name' => $items->first()->product?->name ?? 'Produk',
                'qty' => $items->sum('qty'),
                'revenue' => $items->sum(fn($i) => $i->price * $i->qty),
            ])->sortByDesc('qty')->values();

        $settings = [
            'store_name' => Setting::get('store_name', 'DhoZ-Bakes'),
            'store_address' => Setting::get('store_address', ''),
            'store_phone' => Setting::get('store_phone', ''),
        ];

        return view('admin.dashboard.report', compact('orders', 'stats', 'dailyRevenue', 'productStats', 'settings', 'dateFrom', 'dateTo'));
    }

    private function getStats()
    {
        return [
            'total_orders_today' => Order::whereDate('created_at', today())->count(),
            'revenue_today' => Order::whereDate('created_at', today())->sum('total'),
            'total_products' => Product::count(),
            'total_users' => User::count(),
            'total_categories' => Category::count(),
        ];
    }
}
