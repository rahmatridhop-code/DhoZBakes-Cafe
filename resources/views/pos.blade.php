<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $settings['store_name'] }} - POS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="logo">
          <span class="logo-icon">☕</span>
          <div>
            <h1>{{ $settings['store_name'] }}</h1>
            <p>Artisan Bakery & Cafe</p>
          </div>
        </div>
      </div>
      <nav class="sidebar-nav">
        <button class="nav-item active" data-view="menu">
          <span class="nav-icon">🍽️</span> Menu
        </button>
        <button class="nav-item" data-view="orders">
          <span class="nav-icon">📋</span> Pesanan Hari Ini
        </button>
        <a href="{{ route('admin.dashboard') }}" class="nav-item" style="text-decoration:none;">
          <span class="nav-icon">📊</span> Dashboard Admin
        </a>
      </nav>
      <div class="sidebar-footer">
        <div class="cashier-info">
          <div class="cashier-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
          <div>
            <p class="cashier-name">{{ auth()->user()->name }}</p>
            <p class="cashier-role">{{ ucfirst(auth()->user()->role) }} &bull; Online</p>
          </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" style="margin-top:8px;">
          @csrf
          <button type="submit" class="nav-item" style="font-size:12px;width:100%;background:none;border:none;color:rgba(255,255,255,0.5);cursor:pointer;text-align:left;padding:8px 16px;font-family:inherit;">
            🚪 Keluar
          </button>
        </form>
      </div>
    </aside>

    <main class="main-content">
      <header class="top-bar">
        <div class="search-bar">
          <span class="search-icon">🔍</span>
          <input type="text" id="searchInput" placeholder="Cari menu... (Ctrl+K)">
        </div>
        <div class="top-actions">
          <span class="current-time" id="currentTime"></span>
          <span class="date-display" id="currentDate"></span>
        </div>
      </header>

      <div class="content-area">
        <section class="menu-view" id="menuView">
          <div class="category-bar">
            <button class="category-btn active" data-category="semua">
              <span class="cat-emoji">✨</span> Semua
            </button>
            @foreach($categories as $cat)
            <button class="category-btn" data-category="{{ $cat->slug }}">
              <span class="cat-emoji">{{ $cat->icon }}</span> {{ $cat->name }}
            </button>
            @endforeach
          </div>
          <div class="products-grid" id="productsGrid"></div>
        </section>

        <section class="orders-view hidden" id="ordersView">
          <h2>Pesanan Hari Ini</h2>
          <div class="orders-stats" id="ordersStats"></div>
          <div class="orders-list" id="ordersList">
            @if($orders->isEmpty())
              <p class="empty-state">Belum ada pesanan hari ini.</p>
            @endif
          </div>
        </section>
      </div>
    </main>

    <aside class="cart-panel">
      <div class="cart-header">
        <h2>Keranjang</h2>
        <span class="cart-count" id="cartCount">0</span>
      </div>
      <div class="cart-items" id="cartItems">
        <div class="empty-cart">
          <span class="empty-icon">🛒</span>
          <p>Belum ada item</p>
        </div>
      </div>
      <div class="cart-summary">
        <div class="cart-summary-row">
          <span>Subtotal</span>
          <span id="subtotal">Rp 0</span>
        </div>
        <div class="cart-summary-row">
          <span>PPN (<span id="taxPercent">{{ $settings['tax_rate'] }}</span>%)</span>
          <span id="taxAmount">Rp 0</span>
        </div>
        <div class="cart-summary-row">
          <span>Biaya Layanan (<span id="servicePercent">{{ $settings['service_fee'] }}</span>%)</span>
          <span id="serviceAmount">Rp 0</span>
        </div>
        <div class="cart-divider"></div>
        <div class="cart-summary-row total">
          <span>Total</span>
          <span id="totalAmount">Rp 0</span>
        </div>
      </div>
      <div class="cart-actions">
        <div class="payment-method">
          <button class="pay-method-btn active" data-method="cash">💵 Tunai</button>
          <button class="pay-method-btn" data-method="card">💳 Kartu</button>
          <button class="pay-method-btn" data-method="qr">📱 QRIS</button>
        </div>
        <div class="cash-input-area hidden" id="cashInputArea">
          <label>Uang Diterima</label>
          <input type="text" id="cashReceived" placeholder="Masukkan jumlah..." inputmode="numeric">
        </div>
        <div class="change-display hidden" id="changeDisplay">
          <span>Kembali</span>
          <span id="changeAmount">Rp 0</span>
        </div>
        <button class="btn-checkout" id="btnCheckout" disabled>Bayar Sekarang</button>
        <button class="btn-clear" id="btnClear">Kosongkan Keranjang</button>
      </div>
    </aside>
  </div>

  <div class="modal-overlay hidden" id="receiptModal">
    <div class="receipt-modal">
      <div class="receipt" id="receiptContent">
        <div class="receipt-header">
          <h2>☕ {{ $settings['store_name'] }}</h2>
          <p>Artisan Bakery & Cafe</p>
          <p class="receipt-address">{{ $settings['store_address'] }}</p>
          <p>Telp: {{ $settings['store_phone'] }}</p>
        </div>
        <div class="receipt-divider">- - - - - - - - - - - - - - - -</div>
        <div class="receipt-info">
          <p id="receiptDate"></p>
          <p id="receiptCashier">Kasir: {{ auth()->user()->name }}</p>
          <p id="receiptNo"></p>
        </div>
        <div class="receipt-divider">- - - - - - - - - - - - - - - -</div>
        <div class="receipt-items" id="receiptItems"></div>
        <div class="receipt-divider">- - - - - - - - - - - - - - - -</div>
        <div class="receipt-totals">
          <div class="receipt-row"><span>Subtotal</span><span id="receiptSubtotal"></span></div>
          <div class="receipt-row"><span>PPN</span><span id="receiptTax"></span></div>
          <div class="receipt-row"><span>Layanan</span><span id="receiptService"></span></div>
          <div class="receipt-row total"><span>TOTAL</span><span id="receiptTotal"></span></div>
          <div class="receipt-row" id="receiptPaymentRow"><span>Tunai</span><span id="receiptPayment"></span></div>
          <div class="receipt-row" id="receiptChangeRow"><span>Kembali</span><span id="receiptChange"></span></div>
        </div>
        <div class="receipt-divider">- - - - - - - - - - - - - - - -</div>
        <div class="receipt-footer">
          <p>Terima kasih atas kunjungan Anda!</p>
          <p>Sampai jumpa lagi ✨</p>
        </div>
      </div>
      <div class="receipt-actions">
        <button class="btn-print" id="btnPrint">🖨️ Cetak Struk</button>
        <button class="btn-close-receipt" id="btnCloseReceipt">Tutup</button>
      </div>
    </div>
  </div>

  <div class="toast hidden" id="toast"></div>

  <script>
    const PRODUCTS_DATA = @json($products);
    const ORDERS_DATA = @json($orders);
    const SETTINGS_DATA = @json($settings);
    const CATEGORIES_DATA = @json($categories);
    const BASE_URL = "{{ url('/') }}";
    const API_URL = BASE_URL + "/api";
    const CSRF_TOKEN = "{{ csrf_token() }}";
    const USER_NAME = "{{ auth()->user()->name }}";
  </script>
  <script src="{{ asset('js/pos.js') }}"></script>
</body>
</html>
