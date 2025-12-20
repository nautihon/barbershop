#!/bin/bash

echo "========================================"
echo "  BARBERSHOP - START SERVER"
echo "========================================"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "[ERROR] File .env not found!"
    echo "Please copy .env.example to .env and configure it."
    exit 1
fi

# Check if vendor exists
if [ ! -d vendor ]; then
    echo "[INFO] Installing dependencies..."
    composer install
    echo ""
fi

# Check if node_modules exists
if [ ! -d node_modules ]; then
    echo "[INFO] Installing npm packages..."
    npm install
    echo ""
fi

# Clear caches
echo "[INFO] Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo ""

# Check database connection
echo "[INFO] Checking database connection..."
php artisan migrate:status > /dev/null 2>&1
if [ $? -ne 0 ]; then
    echo "[WARNING] Database connection failed. Please check your .env file."
    echo ""
fi

# Start server
echo "[INFO] Starting Laravel development server..."
echo "[INFO] Server will be available at: http://localhost:8000"
echo "[INFO] Press Ctrl+C to stop the server"
echo ""
echo "========================================"
echo ""

php artisan serve

