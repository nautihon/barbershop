@echo off
title Barbershop Server (Fast)
color 0A

REM Ultra-fast start - skip all checks
echo.
echo [FAST MODE] Starting server...
echo URL: http://localhost:8000
echo Press Ctrl+C to stop
echo.

php artisan serve

