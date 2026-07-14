<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DhoZ-Bakes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; color: #fff; }
        .hero { text-align: center; max-width: 600px; }
        .hero h1 { font-size: 3rem; font-weight: 700; margin-bottom: 1rem; }
        .hero p { font-size: 1.1rem; opacity: 0.8; margin-bottom: 2rem; }
        .btn-gold { background: #b8860b; color: #fff; border: none; padding: 12px 32px; font-size: 1rem; border-radius: 8px; }
        .btn-gold:hover { background: #9a7209; color: #fff; }
    </style>
</head>
<body>
    <div class="hero">
        <div style="font-size:4rem;margin-bottom:1rem;">☕</div>
        <h1>DhoZ-Bakes</h1>
        <p>Sistem Point of Sale untuk cafe Anda. Kelola pesanan, produk, dan transaksi dengan mudah.</p>
        <div class="d-flex gap-3 justify-content-center">
            @if(Route::has('login'))
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('pos') }}" class="btn btn-gold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-gold">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light">Daftar</a>
                @endauth
            @endif
        </div>
    </div>
</body>
</html>
