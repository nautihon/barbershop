@echo off
echo ========================================
echo   BARBERSHOP - START SERVER
echo ========================================
echo.

REM Check if .env exists
if not exist .env (
    echo [ERROR] File .env not found!
    echo Please copy .env.example to .env and configure it.
    pause
    exit /b 1
)

REM Check if vendor exists
if not exist vendor (
    echo [INFO] Installing dependencies...
    call composer install
    echo.
)

REM Check if node_modules exists
if not exist node_modules (
    echo [INFO] Installing npm packages...
    call npm install
    echo.
)

REM Clear caches
echo [INFO] Clearing caches...
call php artisan config:clear
call php artisan cache:clear
call php artisan route:clear
call php artisan view:clear
echo.

REM Check database connection
echo [INFO] Checking database connection...
call php artisan migrate:status >nul 2>&1
if errorlevel 1 (
    echo [WARNING] Database connection failed. Please check your .env file.
    echo.
)

REM Start server
echo [INFO] Starting Laravel development server...
echo [INFO] Server will be available at: http://localhost:8000
echo [INFO] Press Ctrl+C to stop the server
echo.
echo ========================================
echo.

php artisan serve

pause

