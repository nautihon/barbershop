@echo off
title Barbershop Server
color 0A

REM Quick check for .env
if not exist .env (
    echo [ERROR] .env not found! Copy .env.example to .env
    pause
    exit /b 1
)

REM Only install if missing (skip if exists)
if not exist vendor\autoload.php (
    echo [INFO] Installing Composer dependencies...
    composer install --no-interaction --quiet
)

if not exist node_modules (
    echo [INFO] Installing NPM packages...
    npm install --silent
)

REM Quick cache clear (only essential)
php artisan config:clear >nul 2>&1
php artisan route:clear >nul 2>&1

REM Check if port 8000 is already in use
netstat -ano | findstr :8000 >nul 2>&1
if not errorlevel 1 (
    echo [WARNING] Port 8000 is already in use!
    echo [INFO] Run stop.bat first or use: php artisan serve --port=8001
    pause
    exit /b 1
)

REM Start server
echo.
echo ========================================
echo   BARBERSHOP SERVER STARTING
echo ========================================
echo   URL: http://localhost:8000
echo   Press Ctrl+C to stop
echo ========================================
echo.

php artisan serve

