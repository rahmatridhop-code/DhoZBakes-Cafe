let cart = [];
let paymentMethod = "cash";
let settings = SETTINGS_DATA;

const formatRupiah = (n) => "Rp " + n.toLocaleString("id-ID");

function updateClock() {
  const now = new Date();
  const time = now.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" });
  const date = now.toLocaleDateString("id-ID", { weekday: "long", day: "numeric", month: "long", year: "numeric" });
  document.getElementById("currentTime").textContent = time;
  document.getElementById("currentDate").textContent = date;
}

function getCategoryLabel(slug) {
  const cat = CATEGORIES_DATA.find(c => c.slug === slug);
  return cat ? cat.name : slug;
}

function renderProducts(filter = "semua", search = "") {
  const grid = document.getElementById("productsGrid");
  const filtered = PRODUCTS_DATA.filter((p) => {
    const pSlug = p.category_rel ? p.category_rel.slug : p.category;
    const matchCategory = filter === "semua" || pSlug === filter || p.category === filter;
    const matchSearch = p.name.toLowerCase().includes(search.toLowerCase());
    return matchCategory && matchSearch && p.is_active;
  });

  grid.innerHTML = filtered.map((p) => `
    <div class="product-card" onclick="addToCart(${p.id})">
      <div class="product-image">
        ${p.emoji}
        ${p.badge ? `<span class="product-badge">${p.badge}</span>` : ""}
      </div>
      <div class="product-info">
        <div class="product-name">${p.name}</div>
        <div class="product-category">${getCategoryLabel(p.category)}</div>
        <div class="product-price">${formatRupiah(p.price)}</div>
      </div>
      <button class="product-add-btn" onclick="event.stopPropagation(); addToCart(${p.id})">+</button>
    </div>
  `).join("");
}

function addToCart(id) {
  const product = PRODUCTS_DATA.find((p) => p.id === id);
  if (!product) return;
  const existing = cart.find((item) => item.id === id);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ ...product, qty: 1 });
  }
  renderCart();
  showToast(`${product.name} ditambahkan ke keranjang`);
}

function removeFromCart(id) {
  const idx = cart.findIndex((item) => item.id === id);
  if (idx === -1) return;
  if (cart[idx].qty > 1) {
    cart[idx].qty -= 1;
  } else {
    cart.splice(idx, 1);
  }
  renderCart();
}

function deleteFromCart(id) {
  cart = cart.filter((item) => item.id !== id);
  renderCart();
}

function renderCart() {
  const container = document.getElementById("cartItems");
  const countEl = document.getElementById("cartCount");
  const totalItems = cart.reduce((sum, i) => sum + i.qty, 0);
  countEl.textContent = totalItems;

  if (cart.length === 0) {
    container.innerHTML = `<div class="empty-cart"><span class="empty-icon">🛒</span><p>Belum ada item</p></div>`;
    document.getElementById("btnCheckout").disabled = true;
  } else {
    container.innerHTML = cart.map((item) => `
      <div class="cart-item">
        <div class="cart-item-emoji">${item.emoji}</div>
        <div class="cart-item-details">
          <div class="cart-item-name">${item.name}</div>
          <div class="cart-item-price">${formatRupiah(item.price)}</div>
          <div class="cart-item-controls">
            <button class="qty-btn remove" onclick="deleteFromCart(${item.id})">✕</button>
            <button class="qty-btn" onclick="removeFromCart(${item.id})">−</button>
            <span class="cart-item-qty">${item.qty}</span>
            <button class="qty-btn" onclick="addToCart(${item.id})">+</button>
          </div>
          <div class="cart-item-total">${formatRupiah(item.price * item.qty)}</div>
        </div>
      </div>
    `).join("");
    document.getElementById("btnCheckout").disabled = false;
  }
  updateSummary();
}

function updateSummary() {
  const subtotal = cart.reduce((sum, i) => sum + i.price * i.qty, 0);
  const tax = Math.round(subtotal * (settings.tax_rate / 100));
  const service = Math.round(subtotal * (settings.service_fee / 100));
  const total = subtotal + tax + service;

  document.getElementById("subtotal").textContent = formatRupiah(subtotal);
  document.getElementById("taxPercent").textContent = settings.tax_rate;
  document.getElementById("taxAmount").textContent = formatRupiah(tax);
  document.getElementById("servicePercent").textContent = settings.service_fee;
  document.getElementById("serviceAmount").textContent = formatRupiah(service);
  document.getElementById("totalAmount").textContent = formatRupiah(total);

  updateChange(total);
}

function updateChange(total) {
  if (paymentMethod !== "cash") {
    document.getElementById("changeDisplay").classList.add("hidden");
    document.getElementById("cashInputArea").classList.add("hidden");
    return;
  }
  document.getElementById("cashInputArea").classList.remove("hidden");
  const raw = document.getElementById("cashReceived").value.replace(/[^0-9]/g, "");
  const cash = parseInt(raw) || 0;
  const change = cash - total;
  const changeDisplay = document.getElementById("changeDisplay");
  const changeAmount = document.getElementById("changeAmount");

  if (cash > 0) {
    changeDisplay.classList.remove("hidden");
    changeDisplay.classList.toggle("negative", change < 0);
    changeAmount.textContent = (change < 0 ? "- " : "") + formatRupiah(Math.abs(change));
  } else {
    changeDisplay.classList.add("hidden");
  }
}

async function checkout() {
  if (cart.length === 0) return;

  const subtotal = cart.reduce((sum, i) => sum + i.price * i.qty, 0);
  const tax = Math.round(subtotal * (settings.tax_rate / 100));
  const service = Math.round(subtotal * (settings.service_fee / 100));
  const total = subtotal + tax + service;

  if (paymentMethod === "cash") {
    const raw = document.getElementById("cashReceived").value.replace(/[^0-9]/g, "");
    const cash = parseInt(raw) || 0;
    if (cash < total) { showToast("Uang tidak cukup!"); return; }
  }

  const payload = {
    subtotal, tax, service_fee: service, total,
    payment_method: paymentMethod,
    cash_received: paymentMethod === "cash" ? parseInt(document.getElementById("cashReceived").value.replace(/[^0-9]/g, "")) || 0 : null,
    change_amount: paymentMethod === "cash" ? (parseInt(document.getElementById("cashReceived").value.replace(/[^0-9]/g, "")) || 0) - total : null,
    items: cart.map((item) => ({ product_id: item.id, qty: item.qty, price: item.price })),
  };

  try {
    const resp = await fetch(`${API_URL}/orders`, {
      method: "POST",
      headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": CSRF_TOKEN },
      body: JSON.stringify(payload),
    });
    if (!resp.ok) {
      const err = await resp.json().catch(() => ({}));
      const msg = err.message || "Gagal menyimpan pesanan";
      showToast("Error: " + msg);
      return;
    }
    const order = await resp.json();
    showReceipt({ ...order, items: order.items || cart.map(i => ({ ...i, qty: i.qty, price: i.price })) });
    cart = [];
    document.getElementById("cashReceived").value = "";
    renderCart();
    renderOrdersFromServer();
    showToast("Pesanan berhasil!");
  } catch (e) {
    showReceipt({
      id: Date.now(), subtotal, tax, service_fee: service, total,
      payment_method: paymentMethod, cash_received: payload.cash_received, change_amount: payload.change_amount,
      items: cart.map(i => ({ ...i, qty: i.qty, price: i.price })),
      created_at: new Date().toISOString(),
    });
    cart = [];
    document.getElementById("cashReceived").value = "";
    renderCart();
    showToast("Pesanan tersimpan (offline)");
  }
}

async function renderOrdersFromServer() {
  try {
    const resp = await fetch(`${API_URL}/orders`, { headers: { "Accept": "application/json" } });
    const orders = await resp.json();
    renderOrdersList(orders);
  } catch (e) {
    console.log("Offline mode");
  }
}

function renderOrdersList(orders) {
  const list = document.getElementById("ordersList");
  if (!orders || orders.length === 0) {
    list.innerHTML = '<p class="empty-state">Belum ada pesanan hari ini.</p>';
    return;
  }
  list.innerHTML = orders.map((order) => {
    const orderNum = String(order.id).padStart(4, "0");
    const time = new Date(order.created_at).toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" });
    const itemsHtml = (order.items || []).map((i) => `${i.qty}x ${i.product?.name || 'Item'} - ${formatRupiah(i.price * i.qty)}`).join("<br>");
    const payLabel = order.payment_method === "cash" ? "Tunai" : order.payment_method === "card" ? "Kartu" : "QRIS";
    return `
      <div class="order-card">
        <div class="order-card-header">
          <span class="order-number">Pesanan #${orderNum}</span>
          <span class="order-status ${order.status}">${order.status === "completed" ? "Selesai" : "Pending"}</span>
        </div>
        <div class="order-items-list">${itemsHtml}</div>
        <div class="order-total">
          <span>${time} - ${payLabel}</span>
          <span>${formatRupiah(order.total)}</span>
        </div>
      </div>`;
  }).join("");
}

function showReceipt(order) {
  const dateStr = order.created_at ? new Date(order.created_at).toLocaleDateString("id-ID") : new Date().toLocaleDateString("id-ID");
  const timeStr = order.created_at ? new Date(order.created_at).toLocaleTimeString("id-ID") : new Date().toLocaleTimeString("id-ID");
  document.getElementById("receiptDate").textContent = `${dateStr} ${timeStr}`;
  document.getElementById("receiptNo").textContent = `No. ${String(order.id).padStart(4, "0")}`;

  const itemsEl = document.getElementById("receiptItems");
  itemsEl.innerHTML = (order.items || []).map((item) => `
    <div class="receipt-item">
      <span class="receipt-item-name">${item.qty}x ${item.product?.name || item.name || 'Item'}</span>
      <span>${formatRupiah(item.price * item.qty)}</span>
    </div>
  `).join("");

  document.getElementById("receiptSubtotal").textContent = formatRupiah(order.subtotal);
  document.getElementById("receiptTax").textContent = formatRupiah(order.tax);
  document.getElementById("receiptService").textContent = formatRupiah(order.service_fee);
  document.getElementById("receiptTotal").textContent = formatRupiah(order.total);

  const paymentRow = document.getElementById("receiptPaymentRow");
  const changeRow = document.getElementById("receiptChangeRow");
  if (order.payment_method === "cash") {
    paymentRow.style.display = "flex";
    document.getElementById("receiptPayment").textContent = formatRupiah(order.cash_received);
    changeRow.style.display = "flex";
    document.getElementById("receiptChange").textContent = formatRupiah(order.change_amount);
  } else {
    paymentRow.style.display = "none";
    changeRow.style.display = "none";
  }
  document.getElementById("receiptModal").classList.remove("hidden");
}

function showToast(msg) {
  const toast = document.getElementById("toast");
  toast.textContent = msg;
  toast.classList.remove("hidden");
  setTimeout(() => toast.classList.add("show"), 10);
  setTimeout(() => { toast.classList.remove("show"); setTimeout(() => toast.classList.add("hidden"), 300); }, 2000);
}

function formatCurrencyInput(e) {
  let val = e.target.value.replace(/[^0-9]/g, "");
  if (val) val = parseInt(val).toLocaleString("id-ID");
  e.target.value = val;
}

document.addEventListener("DOMContentLoaded", () => {
  updateClock();
  setInterval(updateClock, 1000);
  renderProducts();
  renderOrdersFromServer();

  document.querySelectorAll(".category-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".category-btn").forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      renderProducts(btn.dataset.category, document.getElementById("searchInput").value);
    });
  });

  document.getElementById("searchInput").addEventListener("input", (e) => {
    const activeCategory = document.querySelector(".category-btn.active").dataset.category;
    renderProducts(activeCategory, e.target.value);
  });

  document.addEventListener("keydown", (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === "k") { e.preventDefault(); document.getElementById("searchInput").focus(); }
  });

  document.querySelectorAll(".nav-item").forEach((nav) => {
    if (nav.dataset && nav.dataset.view) {
      nav.addEventListener("click", () => {
        document.querySelectorAll(".nav-item").forEach((n) => n.classList.remove("active"));
        nav.classList.add("active");
        const view = nav.dataset.view;
        document.getElementById("menuView").classList.toggle("hidden", view !== "menu");
        document.getElementById("ordersView").classList.toggle("hidden", view !== "orders");
        if (view === "orders") renderOrdersFromServer();
      });
    }
  });

  document.querySelectorAll(".pay-method-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".pay-method-btn").forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      paymentMethod = btn.dataset.method;
      updateSummary();
    });
  });

  document.getElementById("cashReceived").addEventListener("input", (e) => { formatCurrencyInput(e); updateSummary(); });
  document.getElementById("btnCheckout").addEventListener("click", checkout);

  document.getElementById("btnClear").addEventListener("click", () => {
    if (cart.length === 0) return;
    cart = [];
    document.getElementById("cashReceived").value = "";
    renderCart();
    showToast("Keranjang dikosongkan");
  });

  document.getElementById("btnPrint").addEventListener("click", () => {
    const content = document.getElementById("receiptContent").innerHTML;
    const win = window.open("", "_blank", "width=400,height=600");
    win.document.write(`<html><head><title>Struk</title><style>body{font-family:'Courier New',monospace;font-size:13px;line-height:1.6;padding:20px}h2{font-size:18px;text-align:center;margin-bottom:4px}p{text-align:center;margin:2px 0;font-size:12px;color:#666}.receipt-divider{text-align:center;margin:10px 0;color:#999;font-size:11px}.receipt-item,.receipt-row{display:flex;justify-content:space-between;font-size:12px;margin-bottom:3px}.receipt-row.total{font-size:14px;font-weight:bold;margin-top:6px}.receipt-footer{text-align:center;margin-top:8px;font-size:12px;color:#999}</style></head><body>${content}</body></html>`);
    win.document.close(); win.print();
  });

  document.getElementById("btnCloseReceipt").addEventListener("click", () => { document.getElementById("receiptModal").classList.add("hidden"); });
  document.getElementById("receiptModal").addEventListener("click", (e) => { if (e.target === e.currentTarget) document.getElementById("receiptModal").classList.add("hidden"); });
});
