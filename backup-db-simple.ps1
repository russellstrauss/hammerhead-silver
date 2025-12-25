# Simple Database Backup Script
# Works when Docker containers are running

$timestamp = Get-Date -Format "yyyy-MM-dd_HHmm"
$backupFile = "db-backup\hammerhe_wrdp1_$timestamp.sql"

Write-Host "Creating database backup..." -ForegroundColor Cyan
Write-Host "Backup file: $backupFile" -ForegroundColor Yellow
Write-Host ""

# Try to use the database container
$containerName = "hammerhead-silver-db"
$dbUser = "russell_hhs_user"
$dbPass = "EZsDNwLGpIPKi4E"
$dbName = "russell_hammerhe_wrdp1"

# Check if container exists and is running
$containerStatus = docker ps -a --filter "name=$containerName" --format "{{.Status}}"

if ($containerStatus -match "Up") {
    Write-Host "Container is running. Creating backup..." -ForegroundColor Green
    docker exec $containerName mysqldump -u $dbUser -p$dbPass --no-tablespaces $dbName | Out-File -FilePath $backupFile -Encoding utf8
    
    if ($LASTEXITCODE -eq 0 -and (Test-Path $backupFile) -and (Get-Item $backupFile).Length -gt 0) {
        $fileSize = (Get-Item $backupFile).Length / 1MB
        Write-Host ""
        Write-Host "Backup completed successfully!" -ForegroundColor Green
        Write-Host "  File: $backupFile" -ForegroundColor White
        Write-Host "  Size: $([math]::Round($fileSize, 2)) MB" -ForegroundColor White
    } else {
        Write-Host ""
        Write-Host "Backup failed. Please check:" -ForegroundColor Red
        Write-Host "  1. Docker containers are running (docker-compose up -d)" -ForegroundColor Yellow
        Write-Host "  2. Container name is correct: $containerName" -ForegroundColor Yellow
        exit 1
    }
} else {
    Write-Host "Container is not running." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Please start the containers first:" -ForegroundColor Cyan
    Write-Host "  docker-compose up -d" -ForegroundColor White
    Write-Host ""
    Write-Host "Or if port 3306 is in use, you can backup directly:" -ForegroundColor Cyan
    Write-Host "  mysqldump -h localhost -u $dbUser -p$dbPass $dbName > $backupFile" -ForegroundColor White
    exit 1
}

Write-Host ""
Write-Host "Done!" -ForegroundColor Cyan

