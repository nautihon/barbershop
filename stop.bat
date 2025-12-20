@echo off
echo ========================================
echo   BARBERSHOP - STOP SERVER
echo ========================================
echo.

REM Find and kill PHP processes running on port 8000
echo [INFO] Stopping Laravel server on port 8000...
echo.

REM Find process using port 8000
for /f "tokens=5" %%a in ('netstat -aon ^| findstr :8000 ^| findstr LISTENING') do (
    set PID=%%a
    echo [INFO] Found process ID: %%a
    echo [INFO] Stopping process...
    taskkill /F /PID %%a >nul 2>&1
    if errorlevel 1 (
        echo [WARNING] Could not stop process. It may have already stopped.
    ) else (
        echo [SUCCESS] Server stopped successfully!
    )
)

REM Alternative: Kill all PHP processes (more aggressive)
echo.
echo [INFO] Checking for any remaining PHP artisan processes...
tasklist /FI "IMAGENAME eq php.exe" 2>NUL | find /I /N "php.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [INFO] Found PHP processes. Stopping...
    taskkill /F /IM php.exe >nul 2>&1
    echo [SUCCESS] All PHP processes stopped!
) else (
    echo [INFO] No PHP processes found.
)

echo.
echo ========================================
echo [INFO] Server stopped!
echo ========================================
echo.
pause

