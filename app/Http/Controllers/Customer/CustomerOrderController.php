<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function cart()
    {
        $settings = [
            'store_name' => Setting::get('store_name', 'DhozBakes'),
            'tax_rate' => (int) Setting::get('tax_rate', '10'),
            'service_fee' => (int) Setting::get('service_fee', '5'),
        ];
        return view('customer.order.cart', compact('settings'));
    }

    public function checkout(Request $request)
    {
        $settings = [
            'store_name' => Setting::get('store_name', 'DhozBakes'),
            'store_address' => Setting::get('store_address', 'Jl. Kenangan No. 123, Jakarta'),
            'store_phone' => Setting::get('store_phone', '0812-3456-7890'),
            'tax_rate' => (int) Setting::get('tax_rate', '10'),
            'service_fee' => (int) Setting::get('service_fee', '5'),
        ];

        $subtotal = (int) $request->query('subtotal', 0);
        $tax = (int) $request->query('tax', 0);
        $serviceFee = (int) $request->query('service_fee', 0);
        $total = (int) $request->query('total', 0);
        $orderType = $request->query('order_type', 'dine_in');
        $itemsJson = $request->query('items', '[]');

        $decoded = json_decode($itemsJson, true);
        if (!is_array($decoded) || empty($decoded)) {
            return redirect()->route('customer.cart')->with('error', 'Data keranjang tidak valid. Silakan pilih ulang menu.');
        }

        return view('customer.order.payment', compact('settings', 'subtotal', 'tax', 'serviceFee', 'total', 'orderType', 'itemsJson'));
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'subtotal' => 'required|integer',
            'tax' => 'required|integer',
            'service_fee' => 'required|integer',
            'total' => 'required|integer',
            'payment_method' => 'required|string|in:cash,qris',
            'cash_received' => 'nullable|integer',
            'change_amount' => 'nullable|integer',
            'order_type' => 'required|string|in:dine_in,takeaway',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|integer',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_type' => $validated['order_type'],
            'customer_name' => auth()->user()->name,
            'customer_phone' => auth()->user()->phone ?? null,
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

        return redirect()->route('customer.receipt', ['order' => $order, 'print' => 1]);
    }

    public function receipt(Order $order)
    {
        if ((int) $order->user_id !== (int) auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'cafeTable']);

        $settings = [
            'store_name' => Setting::get('store_name', 'DhozBakes'),
            'store_address' => Setting::get('store_address', 'Jl. Kenangan No. 123, Jakarta'),
            'store_phone' => Setting::get('store_phone', '0812-3456-7890'),
        ];

        return view('customer.order.receipt', compact('order', 'settings'));
    }

    public function orderHistory()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.order.history', compact('orders'));
    }
}
