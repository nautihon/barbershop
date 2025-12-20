# PowerShell Script to Stop Barbershop Server
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  BARBERSHOP - STOP SERVER" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Find process using port 8000
Write-Host "[INFO] Stopping Laravel server on port 8000..." -ForegroundColor Yellow

$port = 8000
$processes = Get-NetTCPConnection -LocalPort $port -ErrorAction SilentlyContinue | Select-Object -ExpandProperty OwningProcess -Unique

if ($processes) {
    foreach ($pid in $processes) {
        Write-Host "[INFO] Found process ID: $pid" -ForegroundColor Yellow
        Write-Host "[INFO] Stopping process..." -ForegroundColor Yellow
        Stop-Process -Id $pid -Force -ErrorAction SilentlyContinue
        if ($?) {
            Write-Host "[SUCCESS] Server stopped successfully!" -ForegroundColor Green
        } else {
            Write-Host "[WARNING] Could not stop process. It may have already stopped." -ForegroundColor Yellow
        }
    }
} else {
    Write-Host "[INFO] No server found running on port 8000" -ForegroundColor Yellow
    Write-Host "[INFO] Server may have already stopped" -ForegroundColor Yellow
}

# Alternative: Kill all PHP artisan processes
Write-Host ""
Write-Host "[INFO] Checking for any remaining PHP artisan processes..." -ForegroundColor Yellow
$phpProcesses = Get-Process php -ErrorAction SilentlyContinue | Where-Object { $_.CommandLine -like "*artisan serve*" }

if ($phpProcesses) {
    Write-Host "[INFO] Found PHP artisan processes. Stopping..." -ForegroundColor Yellow
    $phpProcesses | Stop-Process -Force
    Write-Host "[SUCCESS] All PHP artisan processes stopped!" -ForegroundColor Green
} else {
    Write-Host "[INFO] No PHP artisan processes found" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "[INFO] Done!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Read-Host "Press Enter to exit"

