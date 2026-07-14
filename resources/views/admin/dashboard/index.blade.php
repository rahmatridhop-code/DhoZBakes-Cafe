@extends('layouts.app')
@section('title', 'Dashboard - DhoZ-Bakes')
@section('page-title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Ringkasan Hari Ini</h5>
        <small class="text-muted" id="lastUpdate">Memperbarui...</small>
    </div>
    <div class="d-flex gap-2">
        <button onclick="openReportModal()" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-printer-fill"></i> Cetak Laporan
        </button>
        <span class="badge bg-success align-self-center" id="liveIndicator">
            <i class="bi bi-circle-fill" style="font-size:6px;vertical-align:middle;"></i> Live
        </span>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card blue">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value text-primary" id="statOrders">-</div>
                        <div class="stat-label">Pesanan Hari Ini</div>
                    </div>
                    <div class="text-primary opacity-25" style="font-size:2rem;"><i class="bi bi-receipt"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card green">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value text-success" id="statRevenue">-</div>
                        <div class="stat-label">Pendapatan Hari Ini</div>
                    </div>
                    <div class="text-success opacity-25" style="font-size:2rem;"><i class="bi bi-cash-stack"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card purple">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value" style="color:#6c5ce7;" id="statProducts">-</div>
                        <div class="stat-label">Total Produk</div>
                    </div>
                    <div style="color:#6c5ce7;opacity:25;font-size:2rem;"><i class="bi bi-box-seam"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card orange">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value text-warning" id="statUsers">-</div>
                        <div class="stat-label">Total Pengguna</div>
                    </div>
                    <div class="text-warning opacity-25" style="font-size:2rem;"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders & Top Products -->
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt-cutoff"></i> Pesanan Terbaru</span>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="recentOrdersBody">
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>#{{ $order->orderNumber() }}</strong></td>
                                <td>{{ $order->user?->name ?? '-' }}</td>
                                <td class="fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-status bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ $order->status === 'completed' ? 'Selesai' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $order->created_at->format('H:i') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pesanan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-trophy"></i> Produk Terlaris</div>
            <div class="card-body">
                @forelse($topProducts as $product)
                <div class="d-flex justify-content-between align-items-center {{ !$loop->last ? 'border-bottom py-2' : 'pt-2' }}">
                    <div>
                        <div class="fw-semibold">{{ $product->name }}</div>
                        <small class="text-muted">{{ $product->categoryRel?->name ?? $product->category }}</small>
                    </div>
                    <span class="badge bg-light text-dark">{{ $product->order_items_count }} terjual</span>
                </div>
                @empty
                <p class="text-muted text-center">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-printer-fill"></i> Cetak Laporan Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Dari Tanggal</label>
                        <input type="date" id="reportFrom" class="form-control" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Sampai Tanggal</label>
                        <input type="date" id="reportTo" class="form-control" value="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted">Laporan akan mencakup total penjualan, detail pesanan, produk terlaris, dan rekap metode pembayaran dalam rentang tanggal yang dipilih.</small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="generateReport()">
                    <i class="bi bi-printer-fill"></i> Generate & Cetak
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function formatRp(n) { return 'Rp ' + parseInt(n).toLocaleString('id-ID'); }

function updateStats(data) {
    const el = (id, val) => { document.getElementById(id).textContent = val; };
    el('statOrders', data.total_orders_today);
    el('statRevenue', formatRp(data.revenue_today));
    el('statProducts', data.total_products);
    el('statUsers', data.total_users);
    document.getElementById('lastUpdate').textContent = 'Update: ' + new Date().toLocaleTimeString('id-ID');
}

// Initial load
fetch('{{ route("admin.dashboard.realtime") }}')
    .then(r => r.json())
    .then(updateStats)
    .catch(() => {});

// Poll every 5 seconds
setInterval(() => {
    fetch('{{ route("admin.dashboard.realtime") }}')
        .then(r => r.json())
        .then(updateStats)
        .catch(() => {});
}, 5000);

// Report
function openReportModal() {
    new bootstrap.Modal(document.getElementById('reportModal')).show();
}

function generateReport() {
    const from = document.getElementById('reportFrom').value;
    const to = document.getElementById('reportTo').value;
    window.open('{{ route("admin.dashboard.report") }}?from=' + from + '&to=' + to, '_blank');
}
</script>
@endpush
