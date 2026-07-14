<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subtotal' => 'required|integer',
            'tax' => 'required|integer',
            'service_fee' => 'required|integer',
            'total' => 'required|integer',
            'payment_method' => 'required|string',
            'cash_received' => 'nullable|integer',
            'change_amount' => 'nullable|integer',
            'cafe_table_id' => 'nullable|exists:cafe_tables,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|integer',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(),
            'cafe_table_id' => $validated['cafe_table_id'] ?? null,
            'subtotal' => $validated['subtotal'],
            'tax' => $validated['tax'],
            'service_fee' => $validated['service_fee'],
            'total' => $validated['total'],
            'payment_method' => $validated['payment_method'],
            'cash_received' => $validated['cash_received'] ?? null,
            'change_amount' => $validated['change_amount'] ?? null,
            'status' => 'completed',
        ]);

        foreach ($validated['items'] as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
            ]);
        }

        return response()->json($order->load('items.product'), 201);
    }

    public function show(Order $order)
    {
        return response()->json($order->load('items.product'));
    }

    public function todayStats()
    {
        $today = Order::whereDate('created_at', today());
        $totalOrders = $today->count();
        $totalRevenue = $today->sum('total');

        return response()->json([
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
        ]);
    }
}
