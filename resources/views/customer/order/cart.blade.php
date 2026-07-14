@extends('customer.layouts.app')
@section('title', 'Keranjang - ' . \App\Models\Setting::get('store_name', 'DhoZ-Bakes'))

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-3xl font-bold text-brand mb-8">🛒 Keranjang Belanja</h1>

    <div id="cartEmpty" class="text-center py-16 text-gray-400">
        <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path></svg>
        <p class="font-medium text-lg">Keranjang kosong</p>
        <a href="{{ route('customer.menu') }}" class="inline-block mt-4 bg-brand text-white px-6 py-2 rounded-full font-semibold hover:bg-brand-dark transition">← Kembali ke Menu</a>
    </div>

    <div id="cartPage" class="hidden">
        <h2 class="text-3xl font-bold mb-8 text-brand">🛒 Keranjang Belanja</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div id="cartItemsList" class="bg-white rounded-xl shadow-lg border border-gray-100 divide-y"></div>

                <div class="mt-6 bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">Tipe Pesanan</h3>
                    <div class="flex gap-4">
                        <button id="typeDine" onclick="setType('dine_in')" class="flex-1 py-4 rounded-xl border-2 border-brand bg-brand/10 text-center transition hover:shadow-md">
                            <div class="text-3xl mb-1">🍽️</div>
                            <div class="font-bold text-brand">Dine In</div>
                            <div class="text-xs text-gray-500">Makan di tempat</div>
                        </button>
                        <button id="typeTake" onclick="setType('takeaway')" class="flex-1 py-4 rounded-xl border-2 border-gray-200 text-center transition hover:shadow-md">
                            <div class="text-3xl mb-1">📦</div>
                            <div class="font-bold text-gray-600">Take Away</div>
                            <div class="text-xs text-gray-500">Bawa pulang</div>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-lg mb-4">Ringkasan</h3>
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span id="subtotal" class="font-medium"></span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500">PPN ({{ $settings['tax_rate'] }}%)</span><span id="taxAmt" class="font-medium"></span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Layanan ({{ $settings['service_fee'] }}%)</span><span id="svcAmt" class="font-medium"></span></div>
                        <div class="flex justify-between font-bold text-xl border-t pt-3"><span>Total</span><span id="totalAmt" class="text-brand"></span></div>
                    </div>
                    <button onclick="goToPayment()" class="w-full bg-brand text-white py-3 rounded-full font-bold text-lg hover:bg-brand-dark transition shadow-lg">Lanjut ke Pembayaran →</button>
                    <a href="{{ route('customer.menu') }}" class="block text-center mt-3 text-sm text-brand hover:underline">← Kembali ke Menu</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = JSON.parse(localStorage.getItem('cart') || '[]');
let orderType = localStorage.getItem('orderType') || 'dine_in';
const TAX = {{ $settings['tax_rate'] }};
const SVC = {{ $settings['service_fee'] }};

function formatRp(n) { return 'Rp ' + n.toLocaleString('id-ID'); }

function render() {
    const page = document.getElementById('cartPage');
    const empty = document.getElementById('cartPage').previousElementSibling;

    if (cart.length === 0) {
        page.classList.add('hidden');
        empty.classList.remove('hidden');
        return;
    }
    page.classList.remove('hidden');
    empty.classList.add('hidden');

    const list = document.getElementById('cartItemsList');
    list.innerHTML = cart.map((item, i) => `
        <div class="flex items-center gap-4 p-4">
            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center text-3xl flex-shrink-0">${item.emoji}</div>
            <div class="flex-1">
                <div class="font-bold">${item.name}</div>
                <div class="text-brand font-semibold text-sm">${formatRp(item.price)}</div>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="changeQty(${i}, -1)" class="w-8 h-8 rounded-full border-2 border-gray-200 flex items-center justify-center hover:border-brand transition">−</button>
                <span class="font-bold text-lg w-6 text-center">${item.qty}</span>
                <button onclick="changeQty(${i}, 1)" class="w-8 h-8 rounded-full border-2 border-gray-200 flex items-center justify-center hover:border-brand transition">+</button>
            </div>
            <div class="font-bold w-24 text-right">${formatRp(item.price * item.qty)}</div>
            <button onclick="removeItem(${i})" class="text-red-400 hover:text-red-600 p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
        </div>
    `).join('');

    const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const tax = Math.round(subtotal * TAX / 100);
    const svc = Math.round(subtotal * SVC / 100);
    const total = subtotal + tax + svc;

    document.getElementById('subtotal').textContent = formatRp(subtotal);
    document.getElementById('taxAmt').textContent = formatRp(tax);
    document.getElementById('svcAmt').textContent = formatRp(svc);
    document.getElementById('totalAmt').textContent = formatRp(total);

    setType(orderType);
}

function changeQty(i, d) {
    cart[i].qty += d;
    if (cart[i].qty <= 0) cart.splice(i, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    render();
}

function removeItem(i) { cart.splice(i, 1); localStorage.setItem('cart', JSON.stringify(cart)); render(); }

function setType(t) {
    orderType = t;
    localStorage.setItem('orderType', t);
    document.getElementById('typeDine').className = t === 'dine_in'
        ? 'flex-1 py-4 rounded-xl border-2 border-brand bg-brand/10 text-center transition hover:shadow-md'
        : 'flex-1 py-4 rounded-xl border-2 border-gray-200 text-center transition hover:shadow-md';
    document.getElementById('typeTake').className = t === 'takeaway'
        ? 'flex-1 py-4 rounded-xl border-2 border-brand bg-brand/10 text-center transition hover:shadow-md'
        : 'flex-1 py-4 rounded-xl border-2 border-gray-200 text-center transition hover:shadow-md';
}

function goToPayment() {
    if (cart.length === 0) return;
    const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const tax = Math.round(subtotal * TAX / 100);
    const svc = Math.round(subtotal * SVC / 100);
    const total = subtotal + tax + svc;
    const items = encodeURIComponent(JSON.stringify(cart.map(i => ({ product_id: i.id, qty: i.qty, price: i.price }))));
    window.location.href = `/customer/checkout?subtotal=${subtotal}&tax=${tax}&service_fee=${svc}&total=${total}&order_type=${orderType}&items=${items}`;
}

render();
</script>
@endsection
