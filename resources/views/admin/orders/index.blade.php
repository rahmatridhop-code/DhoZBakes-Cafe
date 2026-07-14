@extends('layouts.app')
@section('title', 'Pesanan - DhoZ-Bakes')
@section('page-title', 'Daftar Pesanan')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-receipt-cutoff"></i> Semua Pesanan
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kasir</th>
                        <th>Meja</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->orderNumber() }}</strong></td>
                        <td>{{ $order->user?->name ?? '-' }}</td>
                        <td>{{ $order->cafeTable?->name ?? '-' }}</td>
                        <td>{{ $order->items->count() }} item</td>
                        <td class="fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            @if($order->payment_method === 'cash') Tunai
                            @elseif($order->payment_method === 'card') Kartu
                            @else QRIS @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                {{ $order->status === 'completed' ? 'Selesai' : 'Pending' }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Toggle Status">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">Belum ada pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
