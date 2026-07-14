<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DhoZ-Bakes')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#1e3932', light: '#2d5a4e', dark: '#15302a' },
                    }
                }
            }
        }
    </script>
    <style>
        .blob1 { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
        .blob2 { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
        .blob3 { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
        .cart-sidebar { transition: transform 0.3s ease; }
        .cart-sidebar.closed { transform: translateX(100%); }
        .cart-sidebar.open { transform: translateX(0); }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-8">
                    <a href="{{ route('customer.menu') }}" class="flex-shrink-0 flex items-center gap-2">
                        <div class="w-10 h-10 bg-brand rounded-full flex items-center justify-center text-white font-bold text-xl">☕</div>
                        <span class="font-bold text-xl tracking-tight text-brand">{{ \App\Models\Setting::get('store_name', 'DhoZ-Bakes') }}</span>
                    </a>
                    <div class="hidden md:block relative">
                        <input type="text" id="searchInput" placeholder="Cari menu favorit..." class="bg-gray-100 rounded-full py-2 px-4 pl-10 w-64 focus:outline-none focus:ring-2 focus:ring-brand text-sm text-gray-700">
                        <svg class="w-4 h-4 absolute left-4 top-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('customer.menu') }}" class="text-sm font-semibold hover:text-brand transition">Menu</a>
                    <a href="{{ route('customer.history') }}" class="text-sm font-semibold hover:text-brand transition">Pesanan Saya</a>
                    <div class="relative">
                        <button onclick="toggleCart()" class="relative p-2 hover:bg-gray-100 rounded-full transition">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                            <span id="navCartBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold hidden">0</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-sm font-semibold text-gray-600">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <form action="{{ route('customer.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition">Keluar</button>
                        </form>
                    </div>
                </div>
                <button onclick="toggleCart()" class="md:hidden relative p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                    <span id="mobileCartBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold hidden">0</span>
                </button>
            </div>
        </div>
    </nav>

    @if($errors->any())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                <span>Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside ml-7 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    </div>
    @endif

    @yield('content')

    <div id="cartOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-[60] hidden" onclick="toggleCart()"></div>
    <aside id="cartSidebar" class="cart-sidebar closed fixed right-0 top-0 h-full w-full sm:w-96 bg-white shadow-2xl z-[70] flex flex-col">
        <div class="p-5 border-b flex justify-between items-center bg-brand text-white">
            <h3 class="font-bold text-lg">🛒 Keranjang</h3>
            <button onclick="toggleCart()" class="p-1 hover:bg-white/20 rounded-lg transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div id="cartItems" class="flex-1 overflow-y-auto p-4"></div>
        <div id="cartFooter" class="border-t p-4 bg-gray-50 hidden">
            <div class="space-y-2 mb-3">
                <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span id="cartSubtotal">Rp 0</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">PPN (10%)</span><span id="cartTax">Rp 0</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Layanan (5%)</span><span id="cartSvc">Rp 0</span></div>
                <div class="flex justify-between font-bold text-lg border-t pt-2"><span>Total</span><span id="cartTotal" class="text-brand">Rp 0</span></div>
            </div>
            <div class="flex gap-2 mb-3">
                <button id="btnDineIn" onclick="setOrderType('dine_in')" class="flex-1 py-2 rounded-full text-sm font-semibold border-2 border-brand bg-brand text-white transition">🍽️ Dine In</button>
                <button id="btnTakeaway" onclick="setOrderType('takeaway')" class="flex-1 py-2 rounded-full text-sm font-semibold border-2 border-gray-300 text-gray-600 transition">📦 Take Away</button>
            </div>
            <a href="{{ route('customer.cart') }}" class="block w-full bg-brand text-white text-center py-3 rounded-full font-bold hover:bg-brand-dark transition">Bayar Sekarang</a>
        </div>
        <div id="cartEmpty" class="flex-1 flex flex-col items-center justify-center text-gray-400">
            <svg class="w-16 h-16 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
            <p class="font-medium">Keranjang kosong</p>
            <p class="text-sm">Pesan sesuatu yang enak!</p>
        </div>
    </aside>

    <footer class="bg-brand text-white pt-16 pb-8 border-t-4 border-green-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="w-12 h-12 border-2 border-white rounded-full flex items-center justify-center font-bold text-2xl mb-4">☕</div>
                    <p class="text-sm text-gray-300">Artisan Cafe & Patisserie menyajikan kopi dan makanan berkualitas tinggi sejak 2020.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4 text-lg">Menu</h4>
                    <ul class="space-y-3 text-sm text-gray-300">
                        <li><a href="#menu" class="hover:text-white transition">Kopi</a></li>
                        <li><a href="#menu" class="hover:text-white transition">Non Kopi</a></li>
                        <li><a href="#menu" class="hover:text-white transition">Pastry</a></li>
                        <li><a href="#menu" class="hover:text-white transition">Makanan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4 text-lg">Kontak</h4>
                    <ul class="space-y-3 text-sm text-gray-300">
                        <li>{{ \App\Models\Setting::get('store_address', 'Jl. Kenangan No. 123') }}</li>
                        <li>Telp: {{ \App\Models\Setting::get('store_phone', '0812-3456-7890') }}</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4 text-lg">Jam Buka</h4>
                    <ul class="space-y-3 text-sm text-gray-300">
                        <li>Senin - Jumat: 08:00 - 22:00</li>
                        <li>Sabtu - Minggu: 09:00 - 23:00</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-600 pt-8 text-center text-xs text-gray-400">
                <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('store_name', 'DhoZ-Bakes') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    let orderType = localStorage.getItem('orderType') || 'dine_in';
    const TAX = {{ $settings['tax_rate'] ?? 10 }};
    const SVC = {{ $settings['service_fee'] ?? 5 }};

    function formatRp(n) { return 'Rp ' + n.toLocaleString('id-ID'); }

    function saveCart() {
        localStorage.setItem('cart', JSON.stringify(cart));
        localStorage.setItem('orderType', orderType);
        updateBadge();
        renderCartSidebar();
    }

    function updateBadge() {
        const count = cart.reduce((s, i) => s + i.qty, 0);
        ['navCartBadge', 'mobileCartBadge'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.textContent = count;
                el.classList.toggle('hidden', count === 0);
            }
        });
    }

    function addToCart(id, name, price, emoji) {
        const ex = cart.find(i => i.id === id);
        if (ex) { ex.qty += 1; } else { cart.push({ id, name, price, emoji, qty: 1 }); }
        saveCart();
        showToast(name + ' ditambahkan!');
    }

    function changeQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (!item) return;
        item.qty += delta;
        if (item.qty <= 0) cart = cart.filter(i => i.id !== id);
        saveCart();
    }

    function removeItem(id) {
        cart = cart.filter(i => i.id !== id);
        saveCart();
    }

    function setOrderType(type) {
        orderType = type;
        localStorage.setItem('orderType', type);
        document.getElementById('btnDineIn').className = type === 'dine_in'
            ? 'flex-1 py-2 rounded-full text-sm font-semibold border-2 border-brand bg-brand text-white transition'
            : 'flex-1 py-2 rounded-full text-sm font-semibold border-2 border-gray-300 text-gray-600 transition';
        document.getElementById('btnTakeaway').className = type === 'takeaway'
            ? 'flex-1 py-2 rounded-full text-sm font-semibold border-2 border-brand bg-brand text-white transition'
            : 'flex-1 py-2 rounded-full text-sm font-semibold border-2 border-gray-300 text-gray-600 transition';
    }

    function toggleCart() {
        const sidebar = document.getElementById('cartSidebar');
        const overlay = document.getElementById('cartOverlay');
        const isOpen = sidebar.classList.contains('open');
        if (isOpen) {
            sidebar.classList.remove('open'); sidebar.classList.add('closed');
            overlay.classList.add('hidden');
        } else {
            sidebar.classList.remove('closed'); sidebar.classList.add('open');
            overlay.classList.remove('hidden');
            renderCartSidebar();
        }
    }

    function renderCartSidebar() {
        const itemsEl = document.getElementById('cartItems');
        const footerEl = document.getElementById('cartFooter');
        const emptyEl = document.getElementById('cartEmpty');

        if (cart.length === 0) {
            itemsEl.innerHTML = '';
            footerEl.classList.add('hidden');
            emptyEl.classList.remove('hidden');
            return;
        }

        emptyEl.classList.add('hidden');
        footerEl.classList.remove('hidden');

        itemsEl.innerHTML = cart.map(item => `
            <div class="flex items-center gap-3 py-3 border-b">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">${item.emoji}</div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm truncate">${item.name}</div>
                    <div class="text-brand font-bold text-sm">${formatRp(item.price)}</div>
                    <div class="flex items-center gap-2 mt-1">
                        <button onclick="changeQty(${item.id}, -1)" class="w-6 h-6 rounded border flex items-center justify-center text-xs hover:bg-gray-100">−</button>
                        <span class="text-sm font-bold w-5 text-center">${item.qty}</span>
                        <button onclick="changeQty(${item.id}, 1)" class="w-6 h-6 rounded border flex items-center justify-center text-xs hover:bg-gray-100">+</button>
                        <button onclick="removeItem(${item.id})" class="ml-2 text-red-400 hover:text-red-600 text-xs">✕</button>
                    </div>
                </div>
                <div class="font-bold text-sm">${formatRp(item.price * item.qty)}</div>
            </div>
        `).join('');

        const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
        const tax = Math.round(subtotal * TAX / 100);
        const svc = Math.round(subtotal * SVC / 100);
        const total = subtotal + tax + svc;

        document.getElementById('cartSubtotal').textContent = formatRp(subtotal);
        document.getElementById('cartTax').textContent = formatRp(tax);
        document.getElementById('cartSvc').textContent = formatRp(svc);
        document.getElementById('cartTotal').textContent = formatRp(total);
    }

    function showToast(msg) {
        const t = document.createElement('div');
        t.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-brand text-white px-5 py-3 rounded-full shadow-lg z-[100] text-sm font-medium transition-opacity';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 2000);
    }

    setOrderType(orderType);
    updateBadge();
    renderCartSidebar();
    </script>
    @stack('scripts')
</body>
</html>
