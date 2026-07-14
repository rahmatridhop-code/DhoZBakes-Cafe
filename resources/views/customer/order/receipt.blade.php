@extends('customer.layouts.app')
@section('title', 'Struk #' . $order->orderNumber() . ' - ' . $settings['store_name'])

@section('content')
@php $autoPrint = session('auto_print') || request('print'); @endphp
<div class="max-w-lg mx-auto py-10">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-brand text-white p-8 text-center">
            <div class="text-5xl mb-3">☕</div>
            <h1 class="text-2xl font-bold">{{ $settings['store_name'] }}</h1>
            <p class="text-sm text-green-200 mt-1">{{ $settings['store_address'] }}</p>
            <p class="text-sm text-green-200">Telp: {{ $settings['store_phone'] }}</p>
            <div class="mt-4 inline-block px-4 py-1 rounded-full text-sm font-semibold {{ $order->order_type === 'dine_in' ? 'bg-white/20' : 'bg-amber-500/30 text-amber-200' }}">
                {{ $order->order_type === 'dine_in' ? '🍽️ Dine In' : '📦 Take Away' }}
            </div>
        </div>

        <!-- Success Badge -->
        <div class="bg-green-50 text-center py-4 border-b">
            <div class="text-3xl mb-1">✅</div>
            <div class="font-bold text-green-700 text-lg">Pembayaran Berhasil!</div>
        </div>

        <!-- Body -->
        <div class="p-6 font-mono text-sm">
            <div class="text-center text-gray-400 mb-4 tracking-widest text-xs">- - - - - - - - - - - - - - - - -</div>

            <div class="space-y-1 mb-4 text-gray-600">
                <div>No. Pesanan: <strong class="text-gray-800">#{{ $order->orderNumber() }}</strong></div>
                <div>Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</div>
                <div>Pelanggan: {{ $order->customer_name ?? auth()->user()->name }}</div>
            </div>

            <div class="text-center text-gray-400 mb-4 tracking-widest text-xs">- - - - - - - - - - - - - - - - -</div>

            <div class="space-y-2 mb-4">
                @foreach($order->items as $item)
                <div class="flex justify-between">
                    <span>{{ $item->qty }}x {{ $item->product?->name ?? 'Produk' }}</span>
                    <span class="font-semibold">{{ 'Rp ' . number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="text-center text-gray-400 mb-4 tracking-widest text-xs">- - - - - - - - - - - - - - - - -</div>

            <div class="space-y-1 mb-4">
                <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span>{{ 'Rp ' . number_format($order->subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">PPN</span><span>{{ 'Rp ' . number_format($order->tax, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Layanan</span><span>{{ 'Rp ' . number_format($order->service_fee, 0, ',', '.') }}</span></div>
                <div class="flex justify-between font-bold text-xl border-t border-dashed pt-3 mt-3"><span>TOTAL</span><span class="text-brand">{{ 'Rp ' . number_format($order->total, 0, ',', '.') }}</span></div>

                @if($order->payment_method === 'cash')
                <div class="flex justify-between text-sm mt-2"><span class="text-gray-500">Tunai</span><span>{{ 'Rp ' . number_format($order->cash_received, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Kembali</span><span class="font-semibold">{{ 'Rp ' . number_format($order->change_amount, 0, ',', '.') }}</span></div>
                @else
                <div class="flex justify-between text-sm mt-2"><span class="text-gray-500">Pembayaran</span><span class="font-semibold">📱 QRIS</span></div>
                @endif
            </div>

            <div class="text-center text-gray-400 mt-4 tracking-widest text-xs">- - - - - - - - - - - - - - - - -</div>
            <div class="text-center text-gray-500 mt-4">
                <p>Terima kasih atas kunjungan Anda!</p>
                <p>Sampai jumpa lagi ☕✨</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-4 mt-6">
        <button onclick="printReceipt()" class="flex-1 bg-brand text-white py-3 rounded-full font-bold hover:bg-brand-dark transition shadow-lg flex items-center justify-center gap-2">
            🖨️ Cetak Struk
        </button>
        <a href="{{ route('customer.menu') }}" class="flex-1 border-2 border-brand text-brand py-3 rounded-full font-bold hover:bg-brand/5 transition text-center">← Menu</a>
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('customer.history') }}" class="text-sm text-brand hover:underline font-semibold">Lihat Riwayat Pesanan →</a>
    </div>
</div>

<script>
function printReceipt() {
    const content = document.querySelector('.bg-white.rounded-2xl').innerHTML;
    const win = window.open('', '_blank', 'width=420,height=700');
    win.document.write(`<html><head><title>Struk #{{ $order->orderNumber() }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; padding: 20px; max-width: 380px; margin: 0 auto; font-size: 12px; line-height: 1.6; }
        .text-center { text-align: center; }
        .mb-4 { margin-bottom: 16px; }
        .mt-4 { margin-top: 16px; }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .font-bold { font-weight: bold; }
        .text-xs { font-size: 10px; }
        .text-sm { font-size: 11px; }
        .tracking-widest { letter-spacing: 4px; }
        .text-gray-400 { color: #aaa; }
        .text-gray-500 { color: #888; }
        .border-t { border-top: 1px dashed #ddd; padding-top: 8px; margin-top: 8px; }
        .bg-brand { background: #1e3932; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .bg-green-50 { background: #e8f5e9; text-align: center; padding: 12px; }
        .bg-green-700 { color: #2e7d32; font-weight: bold; }
        .space-y-1 > div { margin-bottom: 4px; }
        .space-y-2 > div { margin-bottom: 8px; }
    </style></head><body>
    <div class="bg-brand">
        <div style="font-size:28px;">☕</div>
        <div style="font-size:16px;font-weight:bold;margin:4px 0;">{{ $settings['store_name'] }}</div>
        <div style="font-size:10px;opacity:0.7;">{{ $settings['store_address'] }}</div>
        <div style="font-size:10px;opacity:0.7;">Telp: {{ $settings['store_phone'] }}</div>
        <div style="margin-top:8px;display:inline-block;padding:2px 12px;background:rgba(255,255,255,0.2);border-radius:20px;font-size:10px;">{{ $order->order_type === 'dine_in' ? '🍽️ Dine In' : '📦 Take Away' }}</div>
    </div>
    <div class="bg-green-50">✅ Pembayaran Berhasil!</div>
    <div style="padding:16px 0;">
        <div class="text-center text-gray-400 mb-4 tracking-widest text-xs">- - - - - - - - - - - -</div>
        <div class="space-y-1 mb-4">
            <div>No. Pesanan: <strong>#{{ $order->orderNumber() }}</strong></div>
            <div>Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</div>
            <div>Pelanggan: {{ $order->customer_name ?? auth()->user()->name }}</div>
        </div>
        <div class="text-center text-gray-400 mb-4 tracking-widest text-xs">- - - - - - - - - - - -</div>
        @foreach($order->items as $item)
        <div class="flex justify-between"><span>{{ $item->qty }}x {{ $item->product?->name ?? 'Produk' }}</span><span class="font-bold">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span></div>
        @endforeach
        <div class="text-center text-gray-400 mb-4 mt-4 tracking-widest text-xs">- - - - - - - - - - - -</div>
        <div class="space-y-1">
            <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-500">PPN</span><span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-500">Layanan</span><span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span></div>
            <div class="flex justify-between font-bold border-t mt-2 pt-2"><span>TOTAL</span><span>Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
            @if($order->payment_method === 'cash')
            <div class="flex justify-between text-sm mt-2"><span class="text-gray-500">Tunai</span><span>Rp {{ number_format($order->cash_received, 0, ',', '.') }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-500">Kembali</span><span class="font-bold">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span></div>
            @else
            <div class="flex justify-between text-sm mt-2"><span class="text-gray-500">Pembayaran</span><span class="font-bold">📱 QRIS</span></div>
            @endif
        </div>
        <div class="text-center text-gray-400 mt-4 tracking-widest text-xs">- - - - - - - - - - - -</div>
        <div class="text-center mt-4 text-gray-500">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Sampai jumpa lagi ☕✨</p>
        </div>
    </div></body></html>`);
    win.document.close();
    setTimeout(() => win.print(), 500);
}

window.addEventListener('DOMContentLoaded', () => {
    localStorage.removeItem('cart');
    localStorage.removeItem('orderType');
    @if($autoPrint)
    setTimeout(() => printReceipt(), 800);
    @endif
});
</script>
@endsection
