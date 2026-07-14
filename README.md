# DhoZBakes Cafe - Sistem Point of Sale

Aplikasi Point of Sale (POS) berbasis web untuk cafe dengan 3 role pengguna: Admin, Kasir, dan Customer. Dibangun dengan Laravel 12 + SQLite.

## Fitur Utama

### Admin
- Dashboard real-time (polling setiap 5 detik)
- Laporan penjualan per rentang tanggal (cetak)
- Manajemen User, Kategori, Produk, Meja, Pesanan

### Kasir (POS)
- Halaman POS single-page (vanilla JS)
- Keranjang, pencarian menu, filter kategori
- Pembayaran Tunai / Kartu / QRIS
- Cetak struk thermal (popup print)

### Customer (Self-Service)
- Login & Register khusus customer
- Jelajahi menu, keranjang (localStorage)
- Checkout: Dine In / Take Away
- Pembayaran Tunai / QRIS (QR code)
- Struk digital + auto-print
- Riwayat pesanan

## Kredensial Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@poscafe.com | password |
| Kasir | kasir@poscafe.com | password |
| Customer | customer@poscafe.com | password |

## Instalasi

### Prasyarat

- PHP 8.2+
- Composer
- SQLite (default) atau MySQL

### Langkah Instalasi

```bash
git clone https://github.com/rahmatridhop-code/DhoZBakes-Cafe.git
cd DhoZBakes-Cafe

composer install
cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate
php artisan db:seed

php artisan serve
```

Buka `http://127.0.0.1:8000` di browser.

### Menggunakan MySQL

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_cafe
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate:fresh --seed
```

## Struktur Direktori

```
pos-cafe/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php    # Dashboard + laporan
│   │   │   │   ├── OrderController.php        # Kelola pesanan
│   │   │   │   ├── ProductController.php      # CRUD produk
│   │   │   │   ├── CategoryController.php     # CRUD kategori
│   │   │   │   ├── CafeTableController.php    # CRUD meja
│   │   │   │   └── UserController.php         # CRUD pengguna
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php         # Login/Register staff
│   │   │   ├── Customer/
│   │   │   │   ├── CustomerAuthController.php # Login/Register customer
│   │   │   │   ├── CustomerMenuController.php # Menu browsing
│   │   │   │   └── CustomerOrderController.php # Cart, checkout, bayar, struk
│   │   │   ├── OrderController.php            # API orders (POS)
│   │   │   ├── PosController.php              # Halaman POS
│   │   │   ├── ProductController.php          # API produk (POS)
│   │   │   └── SettingController.php          # API settings
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php             # Role-based access
│   │   └── Requests/                          # Form Request validation
│   ├── Models/
│   │   ├── User.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── CafeTable.php
│   │   └── Setting.php
│   └── Providers/
├── database/
│   ├── migrations/                            # 14 migration files
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── UserSeeder.php
│   │   ├── CategorySeeder.php
│   │   ├── ProductSeeder.php
│   │   ├── CategoryProductSeeder.php
│   │   ├── CafeTableSeeder.php
│   │   ├── SettingSeeder.php
│   │   └── CustomerSeeder.php
│   └── database.sqlite
├── public/
│   ├── css/
│   │   └── pos.css                            # CSS khusus halaman POS
│   ├── js/
│   │   └── pos.js                             # JS khusus halaman POS
│   └── index.php
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── dashboard/
│       │   │   ├── index.blade.php            # Dashboard admin
│       │   │   └── report.blade.php           # Laporan cetak
│       │   ├── orders/
│       │   ├── products/
│       │   ├── categories/
│       │   ├── tables/
│       │   └── users/
│       ├── auth/
│       │   ├── login.blade.php                # Login staff
│       │   └── register.blade.php             # Register staff
│       ├── customer/
│       │   ├── auth/
│       │   │   ├── login.blade.php            # Login customer
│       │   │   └── register.blade.php         # Register customer
│       │   ├── layouts/
│       │   │   └── app.blade.php              # Layout customer (Tailwind)
│       │   ├── menu/
│       │   │   └── index.blade.php            # Menu browsing
│       │   └── order/
│       │       ├── cart.blade.php             # Keranjang
│       │       ├── payment.blade.php          # Halaman bayar
│       │       ├── receipt.blade.php          # Struk digital
│       │       └── history.blade.php          # Riwayat pesanan
│       ├── layouts/
│       │   └── app.blade.php                  # Layout admin (Bootstrap)
│       └── pos.blade.php                      # Halaman POS (SPA)
├── routes/
│   └── web.php                                # Semua route
├── .env.example
├── composer.json
├── vite.config.js
└── README.md
```

## Database Schema

### ERD

```
users ─────────────┐
  │ hasMany        │
  ▼                │
orders ──────────┐ │
  │ belongsTo    │ │
  │ hasMany      │ │
  ▼              ▼ │
order_items    cafe_tables
  │ belongsTo
  ▼
products ────────┐
  │ belongsTo    │
  ▼              │
categories       │
                 │
settings ────────┘
```

### Tabel

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Pengguna (admin, kasir, customer) |
| `categories` | Kategori produk (Kopi, Non Kopi, Pastry, Makanan) |
| `products` | Menu/produk cafe |
| `cafe_tables` | Meja cafe |
| `orders` | Pesanan/transaksi |
| `order_items` | Item dalam pesanan |
| `settings` | Pengaturan (store_name, tax_rate, service_fee) |

## Route

### Auth Routes

| Method | URI | Fungsi |
|--------|-----|--------|
| GET/POST | `/login` | Login staff |
| GET/POST | `/register` | Register staff |
| POST | `/logout` | Logout |

### Admin Routes (`auth` + `role:admin`)

| Method | URI | Fungsi |
|--------|-----|--------|
| GET | `/admin` | Dashboard |
| GET | `/admin/dashboard/realtime` | Stats real-time (JSON) |
| GET | `/admin/dashboard/report` | Laporan penjualan |
| CRUD | `/admin/users` | Manajemen pengguna |
| CRUD | `/admin/categories` | Manajemen kategori |
| CRUD | `/admin/products` | Manajemen produk |
| CRUD | `/admin/tables` | Manajemen meja |
| GET | `/admin/orders` | Daftar pesanan |
| GET | `/admin/orders/{order}` | Detail pesanan |
| PATCH | `/admin/orders/{order}/status` | Ubah status pesanan |

### POS Routes (`auth` + `role:admin,cashier`)

| Method | URI | Fungsi |
|--------|-----|--------|
| GET | `/pos` | Halaman POS |

### API Routes (`auth`) - untuk POS

| Method | URI | Fungsi |
|--------|-----|--------|
| GET | `/api/orders` | Daftar pesanan hari ini |
| POST | `/api/orders` | Buat pesanan baru |
| GET | `/api/orders/stats` | Statistik hari ini |
| GET | `/api/orders/{order}` | Detail pesanan |

### Customer Routes (`auth` + `role:customer`)

| Method | URI | Fungsi |
|--------|-----|--------|
| GET/POST | `/customer/login` | Login customer |
| GET/POST | `/customer/register` | Register customer |
| GET | `/customer/menu` | Menu browsing |
| GET | `/customer/cart` | Keranjang |
| GET | `/customer/checkout` | Halaman pembayaran |
| POST | `/customer/pay` | Proses pembayaran |
| GET | `/customer/receipt/{order}` | Struk digital |
| GET | `/customer/history` | Riwayat pesanan |

## Teknologi

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend Admin/Kasir:** Blade + Bootstrap 5 + Bootstrap Icons + Custom CSS
- **Frontend Customer:** Blade + Tailwind CSS (CDN)
- **POS:** Vanilla JavaScript SPA + Custom CSS
- **Database:** SQLite (default) / MySQL
- **ORM:** Eloquent
- **Cetak Struk:** Browser `window.print()` (thermal receipt style)

## License

MIT
