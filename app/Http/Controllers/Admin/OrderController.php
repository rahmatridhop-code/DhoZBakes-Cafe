<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product', 'cafeTable'])
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'cafeTable']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Order $order)
    {
        $newStatus = $order->status === 'completed' ? 'pending' : 'completed';
        $order->update(['status' => $newStatus]);

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
