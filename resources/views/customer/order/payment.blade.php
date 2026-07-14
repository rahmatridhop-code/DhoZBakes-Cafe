@extends('customer.layouts.app')
@section('title', 'Pembayaran - ' . \App\Models\Setting::get('store_name', 'DhoZ-Bakes'))

@section('content')
<div class="max-w-lg mx-auto py-10">
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium mb-6">
        <div class="flex items-center gap-2 mb-1">
            <span>⚠️ Gagal memproses pembayaran:</span>
        </div>
        <ul class="list-disc list-inside space-y-1 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <h1 class="text-3xl font-bold text-brand mb-2">💳 Pembayaran</h1>
    <p class="text-gray-500 mb-8">Pilih metode pembayaran dan selesaikan pesanan.</p>

    <!-- Order Summary -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-lg mb-4 text-gray-800">Ringkasan Pesanan</h3>
        <div id="orderItems" class="text-sm text-gray-600 space-y-1 mb-4"></div>
        <div class="border-t pt-4 space-y-2">
            <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span id="dSub" class="font-medium"></span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-500">PPN</span><span id="dTax" class="font-medium"></span></div>
            <div class="flex justify-between text-sm"><span class="text-gray-500">Layanan</span><span id="dSvc" class="font-medium"></span></div>
            <div class="flex justify-between font-bold text-xl border-t pt-3"><span>Total</span><span id="dTotal" class="text-brand"></span></div>
            <div class="flex justify-between text-sm pt-1"><span class="text-gray-500">Tipe</span><span id="dType" class="font-semibold"></span></div>
        </div>
    </div>

    <!-- Payment Method -->
    <h3 class="font-bold text-lg mb-4 text-gray-800">Metode Pembayaran</h3>
    <div class="grid grid-cols-2 gap-4 mb-6">
        <button onclick="selectPay('cash')" id="payCash" class="py-5 rounded-xl border-2 border-brand bg-brand/10 text-center transition hover:shadow-md">
            <div class="text-3xl mb-2">💵</div>
            <div class="font-bold text-brand">Tunai</div>
            <div class="text-xs text-gray-500">Bayar dengan uang</div>
        </button>
        <button onclick="selectPay('qris')" id="payQris" class="py-5 rounded-xl border-2 border-gray-200 text-center transition hover:shadow-md">
            <div class="text-3xl mb-2">📱</div>
            <div class="font-bold text-gray-600">QRIS</div>
            <div class="text-xs text-gray-500">Scan QR code</div>
        </button>
    </div>

    <!-- Cash Section -->
    <div id="cashSection" class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-lg mb-4">Uang Diterima</h3>
        <input type="text" id="cashInput" class="w-full text-right text-2xl font-bold border-2 border-gray-200 rounded-xl p-4 focus:border-brand focus:outline-none" placeholder="0" oninput="updateChange()">
        <div id="changeRow" class="flex justify-between items-center mt-3 bg-green-50 rounded-lg p-3 hidden">
            <span class="text-green-700 font-medium">Kembali</span>
            <span id="changeVal" class="text-green-700 font-bold text-lg">Rp 0</span>
        </div>
    </div>

    <!-- QRIS Section -->
    <div id="qrisSection" class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6 text-center hidden">
        <h3 class="font-bold text-lg mb-2">Scan QRIS untuk Bayar</h3>
        <div id="qrisAmount" class="text-2xl font-bold text-brand my-3"></div>
        <div class="flex justify-center my-4" id="qrBox"></div>
        <p class="text-sm text-gray-500">Buka aplikasi mobile banking atau e-wallet,<br>lalu scan QR code di atas</p>
    </div>

    <!-- Submit -->
    <form id="payForm" action="{{ route('customer.pay') }}" method="POST">
        @csrf
        <input type="hidden" name="subtotal" id="fSub">
        <input type="hidden" name="tax" id="fTax">
        <input type="hidden" name="service_fee" id="fSvc">
        <input type="hidden" name="total" id="fTotal">
        <input type="hidden" name="payment_method" id="fMethod" value="cash">
        <input type="hidden" name="cash_received" id="fCash">
        <input type="hidden" name="change_amount" id="fChange">
        <input type="hidden" name="order_type" id="fOrderType">
        <div id="itemsContainer"></div>
    </form>

    <button onclick="confirmPayment()" id="btnConfirm" class="w-full bg-brand text-white py-4 rounded-full font-bold text-lg hover:bg-brand-dark transition shadow-lg">Bayar Tunai</button>
    <a href="{{ route('customer.cart') }}" class="block text-center mt-3 text-sm text-brand hover:underline">← Kembali ke Keranjang</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
const subtotal = parseInt('{{ $subtotal }}');
const tax = parseInt('{{ $tax }}');
const svc = parseInt('{{ $serviceFee }}');
const total = parseInt('{{ $total }}');
const orderType = '{{ $orderType }}';
const itemsArr = {!! $itemsJson !!};
let payMethod = 'cash';
let qrMade = false;

function formatRp(n) { return 'Rp ' + n.toLocaleString('id-ID'); }

function formatCurrencyInput(e) {
    let v = e.target.value.replace(/[^0-9]/g, '');
    if (v) v = parseInt(v).toLocaleString('id-ID');
    e.target.value = v;
}

document.getElementById('cashInput').addEventListener('input', function(e) { formatCurrencyInput(e); updateChange(); });

function updateChange() {
    const raw = document.getElementById('cashInput').value.replace(/[^0-9]/g, '');
    const cash = parseInt(raw) || 0;
    const change = cash - total;
    const row = document.getElementById('changeRow');
    const val = document.getElementById('changeVal');

    if (cash > 0) {
        row.classList.remove('hidden');
        if (change < 0) {
            row.className = 'flex justify-between items-center mt-3 bg-red-50 rounded-lg p-3';
            val.className = 'text-red-600 font-bold text-lg';
            val.textContent = '- ' + formatRp(Math.abs(change));
        } else {
            row.className = 'flex justify-between items-center mt-3 bg-green-50 rounded-lg p-3';
            val.className = 'text-green-700 font-bold text-lg';
            val.textContent = formatRp(change);
        }
    } else {
        row.classList.add('hidden');
    }

    document.getElementById('btnConfirm').disabled = (payMethod === 'cash' && cash < total);
    document.getElementById('btnConfirm').className = payMethod === 'cash'
        ? (cash >= total ? 'w-full bg-brand text-white py-4 rounded-full font-bold text-lg hover:bg-brand-dark transition shadow-lg' : 'w-full bg-gray-300 text-gray-500 py-4 rounded-full font-bold text-lg cursor-not-allowed')
        : 'w-full bg-brand text-white py-4 rounded-full font-bold text-lg hover:bg-brand-dark transition shadow-lg';
}

function selectPay(method) {
    payMethod = method;
    document.getElementById('payCash').className = method === 'cash'
        ? 'py-5 rounded-xl border-2 border-brand bg-brand/10 text-center transition hover:shadow-md'
        : 'py-5 rounded-xl border-2 border-gray-200 text-center transition hover:shadow-md';
    document.getElementById('payQris').className = method === 'qris'
        ? 'py-5 rounded-xl border-2 border-brand bg-brand/10 text-center transition hover:shadow-md'
        : 'py-5 rounded-xl border-2 border-gray-200 text-center transition hover:shadow-md';

    document.getElementById('cashSection').classList.toggle('hidden', method !== 'cash');
    document.getElementById('qrisSection').classList.toggle('hidden', method !== 'qris');
    document.getElementById('fMethod').value = method;

    const btn = document.getElementById('btnConfirm');
    if (method === 'qris') {
        btn.textContent = '✅ Konfirmasi Pembayaran QRIS';
        btn.disabled = false;
        btn.className = 'w-full bg-brand text-white py-4 rounded-full font-bold text-lg hover:bg-brand-dark transition shadow-lg';
        if (!qrMade) generateQR();
    } else {
        btn.textContent = 'Bayar Tunai';
        updateChange();
    }
}

function generateQR() {
    const box = document.getElementById('qrBox');
    box.innerHTML = '';
    document.getElementById('qrisAmount').textContent = formatRp(total);
    const data = 'https://dhozbakes.com/pay?amount=' + total + '&type=' + orderType + '&ts=' + Date.now();
    new QRCode(box, { text: data, width: 200, height: 200, colorDark: '#1e3932', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.H });
    qrMade = true;
}

function confirmPayment() {
    if (payMethod === 'cash') {
        const raw = document.getElementById('cashInput').value.replace(/[^0-9]/g, '');
        const cash = parseInt(raw) || 0;
        if (cash < total) { alert('Uang tidak cukup!'); return; }
        document.getElementById('fCash').value = cash;
        document.getElementById('fChange').value = cash - total;
    } else {
        document.getElementById('fCash').value = total;
        document.getElementById('fChange').value = 0;
    }

    document.getElementById('fSub').value = subtotal;
    document.getElementById('fTax').value = tax;
    document.getElementById('fSvc').value = svc;
    document.getElementById('fTotal').value = total;
    document.getElementById('fOrderType').value = orderType;

    var container = document.getElementById('itemsContainer');
    container.innerHTML = '';
    itemsArr.forEach(function(item, i) {
        var pid = document.createElement('input');
        pid.type = 'hidden';
        pid.name = 'items[' + i + '][product_id]';
        pid.value = item.product_id;
        container.appendChild(pid);

        var qty = document.createElement('input');
        qty.type = 'hidden';
        qty.name = 'items[' + i + '][qty]';
        qty.value = item.qty;
        container.appendChild(qty);

        var price = document.createElement('input');
        price.type = 'hidden';
        price.name = 'items[' + i + '][price]';
        price.value = item.price;
        container.appendChild(price);
    });

    document.getElementById('payForm').submit();
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('dSub').textContent = formatRp(subtotal);
    document.getElementById('dTax').textContent = formatRp(tax);
    document.getElementById('dSvc').textContent = formatRp(svc);
    document.getElementById('dTotal').textContent = formatRp(total);
    document.getElementById('dType').textContent = orderType === 'dine_in' ? '🍽️ Dine In' : '📦 Take Away';
    document.getElementById('fOrderType').value = orderType;

    try {
        var cartData = JSON.parse(localStorage.getItem('cart') || '[]');
        if (cartData.length > 0) {
            document.getElementById('orderItems').innerHTML = cartData.map(function(i) {
                return '<div class="flex justify-between"><span>' + i.qty + 'x ' + i.name + '</span><span class="font-medium">' + formatRp(i.price * i.qty) + '</span></div>';
            }).join('');
        }
    } catch(e) {}
});
</script>
@endsection
