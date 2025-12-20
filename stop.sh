#!/bin/bash

echo "========================================"
echo "  BARBERSHOP - STOP SERVER"
echo "========================================"
echo ""

# Find process using port 8000
PID=$(lsof -ti:8000 2>/dev/null)

if [ -z "$PID" ]; then
    echo "[INFO] No server found running on port 8000"
    echo "[INFO] Server may have already stopped"
else
    echo "[INFO] Found process ID: $PID"
    echo "[INFO] Stopping server..."
    kill -9 $PID 2>/dev/null
    if [ $? -eq 0 ]; then
        echo "[SUCCESS] Server stopped successfully!"
    else
        echo "[WARNING] Could not stop process. Trying force kill..."
        kill -9 $PID 2>/dev/null
    fi
fi

# Alternative: Kill all PHP artisan processes
echo ""
echo "[INFO] Checking for any remaining PHP artisan processes..."
PHP_PIDS=$(pgrep -f "php artisan serve" 2>/dev/null)

if [ -z "$PHP_PIDS" ]; then
    echo "[INFO] No PHP artisan processes found"
else
    echo "[INFO] Found PHP artisan processes. Stopping..."
    pkill -f "php artisan serve"
    echo "[SUCCESS] All PHP artisan processes stopped!"
fi

echo ""
echo "========================================"
echo "[INFO] Done!"
echo "========================================"
echo ""

