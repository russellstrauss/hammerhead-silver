# Database Backup Script for Hammerhead Silver
# Creates a timestamped SQL backup in the db-backup directory

$timestamp = Get-Date -Format "yyyy-MM-dd_HHmm"
$backupFile = "db-backup\hammerhe_wrdp1_$timestamp.sql"

Write-Host "Creating database backup..." -ForegroundColor Cyan
Write-Host "Backup file: $backupFile" -ForegroundColor Yellow

# Run mysqldump from the database container
docker exec hammerhead-silver-db mysqldump -u russell_hhs_user -pEZsDNwLGpIPKi4E russell_hammerhe_wrdp1 > $backupFile

if ($LASTEXITCODE -eq 0) {
    $fileSize = (Get-Item $backupFile).Length / 1MB
    Write-Host ""
    Write-Host "Backup completed successfully!" -ForegroundColor Green
    Write-Host "  File: $backupFile" -ForegroundColor White
    Write-Host "  Size: $([math]::Round($fileSize, 2)) MB" -ForegroundColor White
} else {
    Write-Host ""
    Write-Host "Backup failed!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "Done!" -ForegroundColor Cyan

