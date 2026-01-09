@echo off
title Barbershop Server - Stop
color 0C

echo.
echo ========================================
echo   STOPPING BARBERSHOP SERVER
echo ========================================
echo.

REM Fast method: Kill PHP processes on port 8000
set FOUND=0
for /f "tokens=5" %%a in ('netstat -ano 2^>nul ^| findstr :8000 ^| findstr LISTENING') do (
    taskkill /F /PID %%a >nul 2>&1
    if not errorlevel 1 (
        echo [SUCCESS] Stopped process PID: %%a
        set FOUND=1
    )
)

REM If no process found on port 8000, try killing all php.exe (artisan serve)
if %FOUND%==0 (
    tasklist /FI "IMAGENAME eq php.exe" 2>NUL | find /I "php.exe" >NUL
    if not errorlevel 1 (
        echo [INFO] Stopping PHP artisan processes...
        taskkill /F /IM php.exe >nul 2>&1
        if not errorlevel 1 (
            echo [SUCCESS] All PHP processes stopped!
        )
    ) else (
        echo [INFO] No server process found.
    )
)

echo.
echo ========================================
echo   SERVER STOPPED
echo ========================================
timeout /t 2 >nul

