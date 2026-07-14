<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DhoZ-Bakes')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --bs-body-font-size: 0.875rem; }
        body { background: #f8f9fa; font-family: 'Segoe UI', system-ui, sans-serif; }
        .sidebar { width: 250px; min-height: 100vh; background: #1a1a2e; color: #fff; position: fixed; top: 0; left: 0; z-index: 100; transition: width 0.3s; }
        .sidebar .brand { padding: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 12px; }
        .sidebar .brand h5 { margin: 0; font-weight: 700; font-size: 1.1rem; }
        .sidebar .nav-link { color: rgba(255,255,255,0.65); padding: 0.6rem 1.25rem; display: flex; align-items: center; gap: 10px; font-size: 0.875rem; border-radius: 0; transition: all 0.2s; }
        .sidebar .nav-link:hover { color: #fff; background: rgba(255,255,255,0.08); }
        .sidebar .nav-link.active { color: #fff; background: rgba(108,92,231,0.3); border-right: 3px solid #6c5ce7; }
        .sidebar .nav-link i { font-size: 1.1rem; width: 24px; text-align: center; }
        .sidebar .nav-section { padding: 0.75rem 1.25rem 0.3rem; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.35); }
        .sidebar .sidebar-footer { position: absolute; bottom: 0; width: 100%; padding: 1rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); }
        .main-wrapper { margin-left: 250px; min-height: 100vh; }
        .topbar { background: #fff; padding: 0.75rem 1.5rem; border-bottom: 1px solid #e9ecef; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .content { padding: 1.5rem; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-radius: 10px; }
        .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; font-weight: 600; }
        .stat-card { border-left: 4px solid; }
        .stat-card.blue { border-left-color: #0d6efd; }
        .stat-card.green { border-left-color: #198754; }
        .stat-card.purple { border-left-color: #6c5ce7; }
        .stat-card.orange { border-left-color: #fd7e14; }
        .stat-card .stat-value { font-size: 1.5rem; font-weight: 700; }
        .stat-card .stat-label { font-size: 0.8rem; color: #6c757d; }
        .table th { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; font-weight: 600; }
        .badge-status { font-size: 0.75rem; padding: 0.35em 0.65em; }
        @media (max-width: 768px) { .sidebar { width: 60px; } .sidebar .nav-link span, .sidebar .brand h5, .sidebar .nav-section, .sidebar .sidebar-footer .user-info { display: none; } .sidebar .nav-link i { margin: 0; } .main-wrapper { margin-left: 60px; } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="brand">
            <span style="font-size:1.5rem;">☕</span>
            <h5>DhoZ-Bakes</h5>
        </div>
        <nav class="mt-3">
            <div class="nav-section">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('pos') }}" class="nav-link {{ request()->routeIs('pos') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i> <span>POS</span>
            </a>

            <div class="nav-section">Manajemen</div>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i> <span>Kategori</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> <span>Produk</span>
            </a>
            <a href="{{ route('admin.tables.index') }}" class="nav-link {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap-fill"></i> <span>Meja</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt-cutoff"></i> <span>Pesanan</span>
            </a>

            <div class="nav-section">Sistem</div>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> <span>Pengguna</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width:36px;height:36px;font-size:0.85rem;font-weight:600;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="user-info">
                    <div style="font-size:0.85rem;font-weight:600;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.7rem;color:rgba(255,255,255,0.5);">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light w-100">
                    <i class="bi bi-box-arrow-left"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="topbar">
            <h6 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h6>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted" style="font-size:0.85rem;">
                    <i class="bi bi-person-fill"></i> {{ auth()->user()->name }}
                    <span class="badge bg-secondary ms-1">{{ ucfirst(auth()->user()->role) }}</span>
                </span>
            </div>
        </div>
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
