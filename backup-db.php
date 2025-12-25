<?php
/**
 * Database Backup Script
 * Creates a timestamped SQL backup using WordPress database connection
 */

define('WP_USE_THEMES', false);
require_once(__DIR__ . '/wp-load.php');

global $wpdb;

$timestamp = date('Y-m-d_His');
$backupDir = __DIR__ . '/db-backup';
$backupFile = $backupDir . '/hammerhe_wrdp1_' . $timestamp . '.sql';

// Ensure backup directory exists
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

echo "Creating database backup...\n";
echo "Backup file: " . basename($backupFile) . "\n\n";

// Get database credentials from wp-config
$dbhost = DB_HOST;
$dbname = DB_NAME;
$dbuser = DB_USER;
$dbpass = DB_PASSWORD;

// Extract host and port if specified (e.g., "db:3306" or "localhost:3306")
$hostParts = explode(':', $dbhost);
$dbhost_only = $hostParts[0];
$dbport = isset($hostParts[1]) ? $hostParts[1] : '3306';

// Try to use mysqldump if available
$mysqldump = shell_exec('which mysqldump');
if (!$mysqldump) {
    $mysqldump = shell_exec('whereis mysqldump');
}

// Build mysqldump command
$command = sprintf(
    'mysqldump -h %s -P %s -u %s -p%s %s > %s 2>&1',
    escapeshellarg($dbhost_only),
    escapeshellarg($dbport),
    escapeshellarg($dbuser),
    escapeshellarg($dbpass),
    escapeshellarg($dbname),
    escapeshellarg($backupFile)
);

echo "Running mysqldump...\n";
exec($command, $output, $returnCode);

if ($returnCode === 0 && file_exists($backupFile) && filesize($backupFile) > 0) {
    $fileSize = filesize($backupFile) / 1024 / 1024; // Convert to MB
    echo "\n✓ Backup completed successfully!\n";
    echo "  File: " . basename($backupFile) . "\n";
    echo "  Size: " . number_format($fileSize, 2) . " MB\n";
} else {
    // Fallback: Use WordPress to export data
    echo "mysqldump not available or failed. Using WordPress export method...\n";
    
    // This is a simplified backup - for full backup, mysqldump is recommended
    echo "\nNote: For a complete backup, please use mysqldump directly:\n";
    echo "  docker exec hammerhead-silver-db mysqldump -u $dbuser -p$dbpass $dbname > $backupFile\n";
    echo "\nOr ensure the Docker containers are running and try the PowerShell script.\n";
    exit(1);
}

echo "\nDone!\n";

