# POS Cafe - Sistem Point of Sale untuk Cafe

Aplikasi Point of Sale (POS) berbasis web untuk manajemen pesanan, produk, dan transaksi cafe. Dibangun dengan Laravel 12 dan Bootstrap 5.

## Fitur Utama

- **Autentikasi Pengguna** - Login, Register, Logout dengan role-based access
- **2 Level Role** - Admin (full access) dan Kasir (POS access)
- **CRUD Modul**:
  - Manajemen Kategori Produk
  - Manajemen Produk
  - Manajemen Meja
  - Manajemen Pesanan
  - Manajemen Pengguna (Admin only)
  - Dashboard & Laporan
- **Validasi Input** - Menggunakan Laravel Form Request
- **Relasi Eloquent ORM** - hasMany, belongsTo, belongsToMany
- **Middleware** - Proteksi rute berdasarkan role
- **Tampilan Responsif** - Bootstrap 5 + custom CSS untuk POS

## Kredensial Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@poscafe.com | password |
| Kasir | kasir@poscafe.com | password |

## Instalasi

### Prasyarat

- PHP 8.2 atau lebih tinggi
- Composer
- SQLite (default) atau MySQL

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/username/pos-cafe.git
cd pos-cafe

# 2. Install dependency PHP
composer install

# 3. Copy file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Buat database SQLite (jika menggunakan SQLite)
touch database/database.sqlite

# 6. Jalankan migrasi
php artisan migrate

# 7. Jalankan seeder
php artisan db:seed

# 8. Jalankan aplikasi
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`.

### Menggunakan MySQL

Jika ingin menggunakan MySQL, edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_cafe
DB_USERNAME=root
DB_PASSWORD=
```

Lalu buat database `pos_cafe` di MySQL dan jalankan migrasi + seeder:

```bash
php artisan migrate:fresh --seed
```

## Struktur Database

### ERD (Entity Relationship Diagram)

```
users ──────────┐
  │ hasMany     │
  ▼             │
orders ──────┐  │
  │ hasMany  │  │
  ▼          │  │
order_items  │  │
  │ belongsTo│  │
  ▼          │  │
products ────┘  │
  │ belongsTo   │
  ▼             │
categories      │
                │
cafe_tables ────┘
```

### Tabel

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Pengguna sistem (admin, kasir) |
| `categories` | Kategori produk |
| `products` | Menu/produk cafe |
| `cafe_tables` | Meja cafe |
| `orders` | Pesanan/transaksi |
| `order_items` | Item dalam pesanan |
| `settings` | Pengaturan aplikasi |

### Relasi Eloquent

- `User` hasMany `Order`
- `Order` belongsTo `User`
- `Order` belongsTo `CafeTable`
- `Order` hasMany `OrderItem`
- `OrderItem` belongsTo `Order`
- `OrderItem` belongsTo `Product`
- `Product` belongsTo `Category`
- `Category` hasMany `Product`
- `CafeTable` hasMany `Order`

## Route

### Auth Routes
| Method | URI | Fungsi |
|--------|-----|--------|
| GET | `/login` | Halaman login |
| POST | `/login` | Proses login |
| GET | `/register` | Halaman register |
| POST | `/register` | Proses register |
| POST | `/logout` | Logout |

### Admin Routes (Middleware: auth, role:admin)
| Method | URI | Fungsi |
|--------|-----|--------|
| GET | `/admin` | Dashboard admin |
| GET/POST | `/admin/users` | CRUD pengguna |
| GET/POST | `/admin/categories` | CRUD kategori |
| GET/POST | `/admin/products` | CRUD produk |
| GET/POST | `/admin/tables` | CRUD meja |
| GET | `/admin/orders` | Daftar pesanan |
| GET | `/admin/orders/{order}` | Detail pesanan |

### POS Routes (Middleware: auth, role:admin|cashier)
| Method | URI | Fungsi |
|--------|-----|--------|
| GET | `/pos` | Halaman POS |

## Teknologi

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade Templating + Bootstrap 5 + Custom CSS
- **Database:** SQLite (default) / MySQL
- **ORM:** Eloquent

## License

MIT
