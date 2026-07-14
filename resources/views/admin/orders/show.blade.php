@extends('layouts.app')
@section('title', 'Detail Pesanan - DhoZ-Bakes')
@section('page-title', 'Detail Pesanan #' . $order->orderNumber())

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-receipt"></i> Detail Pesanan #{{ $order->orderNumber() }}</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <span style="font-size:1.3rem;">{{ $item->product?->emoji ?? '📦' }}</span>
                                    {{ $item->product?->name ?? 'Produk dihapus' }}
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->qty }}</td>
                                <td class="fw-bold">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle"></i> Ringkasan</div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted">Kasir</td><td class="fw-semibold">{{ $order->user?->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Meja</td><td>{{ $order->cafeTable?->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Waktu</td><td>{{ $order->created_at->format('d M Y H:i') }}</td></tr>
                    <tr><td class="text-muted">Status</td>
                        <td><span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">{{ $order->status === 'completed' ? 'Selesai' : 'Pending' }}</span></td>
                    </tr>
                </table>
                <hr>
                <div class="d-flex justify-content-between"><span class="text-muted">Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">PPN</span><span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Biaya Layanan</span><span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span>Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
                @if($order->payment_method === 'cash')
                <hr>
                <div class="d-flex justify-content-between"><span class="text-muted">Tunai</span><span>Rp {{ number_format($order->cash_received, 0, ',', '.') }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Kembali</span><span>Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span></div>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100 mt-2"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>
@endsection
