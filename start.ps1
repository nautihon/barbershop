# PowerShell Script to Start Barbershop Website
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  BARBERSHOP - START SERVER" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if .env exists
if (-not (Test-Path .env)) {
    Write-Host "[ERROR] File .env not found!" -ForegroundColor Red
    Write-Host "Please copy .env.example to .env and configure it." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

# Check if vendor exists
if (-not (Test-Path vendor)) {
    Write-Host "[INFO] Installing dependencies..." -ForegroundColor Yellow
    composer install
    Write-Host ""
}

# Check if node_modules exists
if (-not (Test-Path node_modules)) {
    Write-Host "[INFO] Installing npm packages..." -ForegroundColor Yellow
    npm install
    Write-Host ""
}

# Clear caches
Write-Host "[INFO] Clearing caches..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
Write-Host ""

# Check database connection
Write-Host "[INFO] Checking database connection..." -ForegroundColor Yellow
$migrateStatus = php artisan migrate:status 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "[WARNING] Database connection failed. Please check your .env file." -ForegroundColor Yellow
    Write-Host ""
}

# Start server
Write-Host "[INFO] Starting Laravel development server..." -ForegroundColor Green
Write-Host "[INFO] Server will be available at: http://localhost:8000" -ForegroundColor Green
Write-Host "[INFO] Press Ctrl+C to stop the server" -ForegroundColor Yellow
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

php artisan serve

Read-Host "`nPress Enter to exit"

