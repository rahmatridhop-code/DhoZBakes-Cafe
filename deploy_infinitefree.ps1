# POS Cafe - Deploy to InfinityFree Script
# Jalankan script ini SEBELUM upload ke InfinityFree

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  POS Cafe - Build for Production" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# 1. Clean old builds
Write-Host "[1/6] Cleaning old builds..." -ForegroundColor Yellow
if (Test-Path "deploy") { Remove-Item -Recurse -Force "deploy" }
New-Item -ItemType Directory -Path "deploy" -Force | Out-Null

# 2. Copy project files (exclude dev stuff)
Write-Host "[2/6] Copying project files..." -ForegroundColor Yellow
$exclude = @(
    ".git",
    "node_modules",
    ".env",
    "database.sqlite",
    "test_order.php",
    "export_db.php",
    "deploy",
    "tests",
    ".editorconfig",
    ".gitattributes",
    ".gitignore",
    "phpunit.xml"
)

Get-ChildItem -Path . | Where-Object { $exclude -notcontains $_.Name } | ForEach-Object {
    Copy-Item -Path $_.FullName -Destination "deploy\" -Recurse -Force
}

# 3. Run composer install --no-dev
Write-Host "[3/6] Running composer install --no-dev..." -ForegroundColor Yellow
Set-Location "deploy"
composer install --no-dev --optimize-autoloader --no-interaction
composer dump-autoload --no-dev --no-interaction
Set-Location ..

# 4. Build frontend assets
Write-Host "[4/6] Building frontend assets..." -ForegroundColor Yellow
Set-Location "deploy"
if (Test-Path "package.json") {
    npm install --production 2>$null
    npm run build 2>$null
}
Set-Location ..

# 5. Create .htaccess for root redirect
Write-Host "[5/6] Creating .htaccess files..." -ForegroundColor Yellow
@"
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ /public/`$1 [L]
</IfModule>
"@ | Out-File -FilePath "deploy\.htaccess" -Encoding UTF8

# Fix storage .htaccess for public access
if (Test-Path "deploy\public\.htaccess") {
    $htaccess = Get-Content "deploy\public\.htaccess" -Raw
    $storageRule = @"

    # Storage public access
    RewriteCond %{REQUEST_URI} ^/storage/
    RewriteRule ^storage/(.*)$ /../storage/app/public/`$1 [L]
"@
    ($htaccess + $storageRule) | Out-File -FilePath "deploy\public\.htaccess" -Encoding UTF8
}

# 6. Create storage/public symlink workaround
$storagePublic = "deploy\storage\app\public"
if (!(Test-Path $storagePublic)) {
    New-Item -ItemType Directory -Path $storagePublic -Force | Out-Null
}

# Copy storage files if exist
if (Test-Path "storage\app\public") {
    Copy-Item -Path "storage\app\public\*" -Destination $storagePublic -Recurse -Force -ErrorAction SilentlyContinue
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  Build Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Files ready in: deploy/" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Buat akun di https://infinityfree.com" -ForegroundColor White
Write-Host "2. Buat hosting account + MySQL database" -ForegroundColor White
Write-Host "3. Edit file env_production -> isi MySQL credentials" -ForegroundColor White
Write-Host "4. Rename env_production ke .env" -ForegroundColor White
Write-Host "5. Copy isi .env ke .env di folder deploy" -ForegroundColor White
Write-Host "6. Upload semua isi folder deploy ke htdocs/ via File Manager" -ForegroundColor White
Write-Host "7. Import database/poscafe_export.sql via phpMyAdmin" -ForegroundColor White
Write-Host ""
