@extends('customer.layouts.app')
@section('title', 'Riwayat Pesanan - ' . \App\Models\Setting::get('store_name', 'DhoZ-Bakes'))

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-3xl font-bold text-brand mb-8">📋 Riwayat Pesanan Saya</h1>

    @forelse($orders as $order)
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-5 mb-4 hover:shadow-xl transition">
        <div class="flex justify-between items-start mb-3">
            <div class="flex items-center gap-3">
                <span class="font-bold text-lg">#{{ $order->orderNumber() }}</span>
                <span class="px-3 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Selesai</span>
                <span class="px-3 py-0.5 rounded-full text-xs font-semibold {{ $order->order_type === 'dine_in' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $order->order_type === 'dine_in' ? '🍽️ Dine In' : '📦 Take Away' }}
                </span>
            </div>
            <span class="text-sm text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</span>
        </div>
        <div class="text-sm text-gray-600 mb-3">
            @foreach($order->items as $item)
                {{ $item->qty }}x {{ $item->product?->name ?? 'Produk' }}{{ !$loop->last ? ', ' : '' }}
            @endforeach
        </div>
        <div class="flex justify-between items-center border-t pt-3">
            <div class="text-sm text-gray-500">
                @if($order->payment_method === 'cash') 💵 Tunai @else 📱 QRIS @endif
            </div>
            <div class="font-bold text-lg text-brand">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
        </div>
    </div>
    @empty
    <div class="text-center py-16 text-gray-400">
        <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        <p class="font-medium text-lg">Belum ada riwayat pesanan</p>
        <a href="{{ route('customer.menu') }}" class="inline-block mt-4 bg-brand text-white px-6 py-2 rounded-full font-semibold hover:bg-brand-dark transition">Pesan Sekarang →</a>
    </div>
    @endforelse
</div>
@endsection
