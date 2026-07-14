# Panduan Deploy ke InfinityFree

## Persiapan Lokal

### 1. Jalankan Build Script
```powershell
.\deploy_infinitefree.ps1
```

### 2. Export Database
```powershell
php export_db.php
```
File SQL akan ada di `database/poscafe_export.sql`

---

## Setup InfinityFree

### 3. Buat Akun
1. Daftar di https://infinityfree.com
2. **Create Account** → pilih domain gratis (contoh: `dhobakes.epizy.com`)
3. Catat **MySQL credentials** dari control panel:
   - MySQL Host (bukan localhost!)
   - Database Name
   - Username
   - Password

### 4. Upload Files
1. Login **File Manager** di control panel
2. Masuk folder `htdocs/`
3. Upload **seluruh isi folder `deploy/`** ke `htdocs/`

### 5. Buat .env
1. Copy file `env_production` ke `deploy/.env`
2. Edit `.env` → isi MySQL credentials dari InfinityFree:
```env
DB_CONNECTION=mysql
DB_HOST=sql123.infinityfree.com
DB_DATABASE=epizy_xxx_dhobakes
DB_USERNAME=epizy_xxx
DB_PASSWORD=xxxxxx

APP_URL=https://dhobakes.epizy.net
APP_KEY=base64:xxxxx  # generate dulu: php artisan key:generate --show
```

### 6. Import Database
1. Login **phpMyAdmin** dari control panel
2. Pilih database yang dibuat
3. Klik **Import** → pilih file `database/poscafe_export.sql`
4. Klik **Go**

### 7. Fix Permissions
Di File Manager, set permissions folder:
- `storage/` → **755** atau **777**
- `bootstrap/cache/` → **755** atau **777**

---

## Troubleshooting

### Error 500
- Cek file `.env` sudah diisi dengan benar
- Pastikan `APP_KEY` sudah di-generate

### Storage tidak bisa diakses
- Pastikan `.htaccess` di `public/` sudah ada rule untuk storage

### Database error
- Pastikan MySQL host bukan `localhost` (gunakan host dari InfinityFree)
- Pastikan nama database, username, password benar

### CSS/JS tidak muncul
- Jalankan `npm run build` ulang di lokal
- Upload ulang folder `public/build/`

---

## Limitasi InfinityFree

| Feature | Status |
|---------|--------|
| PHP 8.3 | ✅ |
| MySQL | ✅ (50MB) |
| SSH/artisan | ❌ |
| Cron jobs | ❌ |
| Queue workers | ❌ |
| Disk | 5GB |
| Bandwidth | Unlimited |
| SSL | ✅ Gratis |

---

## Login Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@poscafe.com | password |
| Kasir | kasir@poscafe.com | password |
| Customer | customer@poscafe.com | password |
