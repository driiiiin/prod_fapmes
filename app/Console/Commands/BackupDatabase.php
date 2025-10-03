<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database {--type=daily : Backup type (daily, weekly, monthly)} {--encrypt : Encrypt the backup file} {--compress : Compress the backup file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a secure backup of the FAPMES database with encryption and compression options';

    /**
     * Backup storage path
     */
    protected $backupPath = 'private/backups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Starting FAPMES database backup...');

            // Create backup directory if it doesn't exist
            $this->createBackupDirectory();

            // Get database configuration
            $dbConfig = $this->getDatabaseConfig();

            // Generate backup filename
            $filename = $this->generateBackupFilename($dbConfig['driver']);

            // Create backup based on database type
            $backupFile = $this->createBackup($dbConfig, $filename);

            if ($backupFile) {
                // Apply security measures
                $this->applySecurityMeasures($backupFile);

                // Clean up old backups
                $this->cleanupOldBackups();

                // Log the backup
                $this->logBackup($filename);

                $this->info('Database backup completed successfully!');
                $this->info("Backup saved to: {$backupFile}");

                return 0;
            }

            $this->error('Backup failed!');
            return 1;

        } catch (Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            Log::error('Database backup failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Create backup directory structure
     */
    protected function createBackupDirectory()
    {
        $type = $this->option('type');
        $path = "{$this->backupPath}/{$type}";

        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
            $this->info("Created backup directory: {$path}");
        }
    }

    /**
     * Get database configuration
     */
    protected function getDatabaseConfig()
    {
        $connection = DB::connection();
        $config = $connection->getConfig();

        return [
            'driver' => $config['driver'],
            'database' => $config['database'],
            'host' => $config['host'] ?? null,
            'port' => $config['port'] ?? null,
            'username' => $config['username'] ?? null,
            'password' => $config['password'] ?? null,
        ];
    }

    /**
     * Generate backup filename with timestamp
     */
    protected function generateBackupFilename($driver)
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $type = $this->option('type');

        $extension = $this->getFileExtension($driver);
        $filename = "fapmes_backup_{$type}_{$timestamp}.{$extension}";

        return $filename;
    }

    /**
     * Get appropriate file extension based on database driver
     */
    protected function getFileExtension($driver)
    {
        switch ($driver) {
            case 'sqlite':
                return 'sqlite';
            case 'mysql':
            case 'mariadb':
            case 'pgsql':
            case 'sqlsrv':
                return 'sql';
            default:
                return 'backup';
        }
    }

    /**
     * Create backup based on database type
     */
    protected function createBackup($dbConfig, $filename)
    {
        $type = $this->option('type');
        $backupPath = "{$this->backupPath}/{$type}/{$filename}";

        switch ($dbConfig['driver']) {
            case 'sqlite':
                return $this->backupSqlite($dbConfig['database'], $backupPath);

            case 'mysql':
            case 'mariadb':
                return $this->backupMysql($dbConfig, $backupPath);

            case 'pgsql':
                return $this->backupPostgresql($dbConfig, $backupPath);

            default:
                $this->error("Unsupported database driver: {$dbConfig['driver']}");
                return false;
        }
    }

    /**
     * Backup SQLite database
     */
    protected function backupSqlite($databasePath, $backupPath)
    {
        try {
            // For SQLite, we copy the database file directly
            if (file_exists($databasePath)) {
                $content = file_get_contents($databasePath);
                Storage::put($backupPath, $content);

                $this->info("SQLite database backed up successfully");
                return $backupPath;
            } else {
                $this->error("SQLite database file not found: {$databasePath}");
                return false;
            }
        } catch (Exception $e) {
            $this->error("SQLite backup failed: " . $e->getMessage());
            return false;
        }
    }

        /**
     * Backup MySQL/MariaDB database
     */
    protected function backupMysql($dbConfig, $backupPath)
    {
        try {
            // First try with mysqldump
            $command = $this->buildMysqldumpCommand($dbConfig);

            // Execute mysqldump command
            $output = [];
            $returnCode = 0;

            exec($command . ' 2>&1', $output, $returnCode);

            if ($returnCode === 0) {
                $sqlContent = implode("\n", $output);
                Storage::put($backupPath, $sqlContent);

                $this->info("MySQL database backed up successfully using mysqldump");
                return $backupPath;
            } else {
                $this->warn("mysqldump failed, trying PHP-based backup...");
                return $this->backupMysqlWithPHP($dbConfig, $backupPath);
            }
        } catch (Exception $e) {
            $this->warn("mysqldump failed, trying PHP-based backup...");
            return $this->backupMysqlWithPHP($dbConfig, $backupPath);
        }
    }

    /**
     * Backup MySQL using PHP (fallback when mysqldump is not available)
     */
    protected function backupMysqlWithPHP($dbConfig, $backupPath)
    {
        try {
            $this->info("Creating MySQL backup using PHP...");

            // Create a new connection for backup
            $backupConnection = new \PDO(
                "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset=utf8mb4",
                $dbConfig['username'],
                $dbConfig['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]
            );

            $sqlContent = $this->generateMySQLBackupSQL($backupConnection, $dbConfig['database']);
            Storage::put($backupPath, $sqlContent);

            $this->info("MySQL database backed up successfully using PHP");
            return $backupPath;

        } catch (Exception $e) {
            $this->error("PHP-based MySQL backup failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate MySQL backup SQL using PHP
     */
    protected function generateMySQLBackupSQL($pdo, $databaseName)
    {
        $sql = "-- FAPMES Database Backup\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: {$databaseName}\n\n";

        // Get all tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $sql .= "-- Table structure for table `{$table}`\n";
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";

            // Get table structure
            $createTable = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch();
            $sql .= $createTable['Create Table'] . ";\n\n";

            // Get table data
            $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll();
            if (!empty($rows)) {
                $sql .= "-- Data for table `{$table}`\n";
                $sql .= "INSERT INTO `{$table}` VALUES\n";

                $values = [];
                foreach ($rows as $row) {
                    $rowValues = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $rowValues[] = 'NULL';
                        } else {
                            $rowValues[] = $pdo->quote($value);
                        }
                    }
                    $values[] = '(' . implode(', ', $rowValues) . ')';
                }

                $sql .= implode(",\n", $values) . ";\n\n";
            }
        }

        return $sql;
    }

        /**
     * Build mysqldump command
     */
    protected function buildMysqldumpCommand($dbConfig)
    {
        // Try to find mysqldump in common locations
        $mysqldumpPath = $this->findMysqldumpPath();
        $command = $mysqldumpPath;

        if ($dbConfig['host']) {
            $command .= " -h {$dbConfig['host']}";
        }

        if ($dbConfig['port']) {
            $command .= " -P {$dbConfig['port']}";
        }

        if ($dbConfig['username']) {
            $command .= " -u {$dbConfig['username']}";
        }

        if ($dbConfig['password']) {
            $command .= " -p{$dbConfig['password']}";
        }

        $command .= " --single-transaction --routines --triggers";
        $command .= " {$dbConfig['database']}";

        return $command;
    }

    /**
     * Find mysqldump executable path
     */
    protected function findMysqldumpPath()
    {
        // Common paths for mysqldump on Windows
        $possiblePaths = [
            'mysqldump', // If it's in PATH
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\wamp\\bin\\mysql\\mysql8.0.31\\bin\\mysqldump.exe',
            'C:\\wamp64\\bin\\mysql\\mysql8.0.31\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files (x86)\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
        ];

        foreach ($possiblePaths as $path) {
            if ($this->isExecutable($path)) {
                $this->info("Found mysqldump at: {$path}");
                return $path;
            }
        }

        // If not found, return the default and let it fail with a clear error
        $this->error("mysqldump not found. Please install MySQL client tools or add mysqldump to your PATH.");
        $this->error("Common locations: C:\\xampp\\mysql\\bin\\mysqldump.exe");
        return 'mysqldump';
    }

    /**
     * Check if a file is executable
     */
    protected function isExecutable($path)
    {
        if ($path === 'mysqldump') {
            // Check if it's in PATH
            $output = [];
            $returnCode = 0;
            exec('where mysqldump 2>&1', $output, $returnCode);
            return $returnCode === 0;
        }

        return file_exists($path);
    }

    /**
     * Backup PostgreSQL database
     */
    protected function backupPostgresql($dbConfig, $backupPath)
    {
        try {
            $command = $this->buildPgDumpCommand($dbConfig);

            // Execute pg_dump command
            $output = [];
            $returnCode = 0;

            exec($command . ' 2>&1', $output, $returnCode);

            if ($returnCode === 0) {
                $sqlContent = implode("\n", $output);
                Storage::put($backupPath, $sqlContent);

                $this->info("PostgreSQL database backed up successfully");
                return $backupPath;
            } else {
                $this->error("PostgreSQL backup failed: " . implode("\n", $output));
                return false;
            }
        } catch (Exception $e) {
            $this->error("PostgreSQL backup failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Build pg_dump command
     */
    protected function buildPgDumpCommand($dbConfig)
    {
        $command = 'pg_dump';

        if ($dbConfig['host']) {
            $command .= " -h {$dbConfig['host']}";
        }

        if ($dbConfig['port']) {
            $command .= " -p {$dbConfig['port']}";
        }

        if ($dbConfig['username']) {
            $command .= " -U {$dbConfig['username']}";
        }

        $command .= " {$dbConfig['database']}";

        return $command;
    }

    /**
     * Apply security measures to backup file
     */
    protected function applySecurityMeasures($backupFile)
    {
        // Set restrictive file permissions
        $fullPath = Storage::path($backupFile);
        chmod($fullPath, 0600); // Read/write for owner only

        // Encrypt if requested
        if ($this->option('encrypt')) {
            $this->encryptBackup($backupFile);
        }

        // Compress if requested
        if ($this->option('compress')) {
            $this->compressBackup($backupFile);
        }

        $this->info("Security measures applied to backup file");
    }

    /**
     * Encrypt backup file
     */
    protected function encryptBackup($backupFile)
    {
        try {
            $content = Storage::get($backupFile);
            $encryptionKey = config('app.key');

            // Use Laravel's encryption
            $encrypted = encrypt($content);

            Storage::put($backupFile . '.encrypted', $encrypted);
            Storage::delete($backupFile); // Remove unencrypted version

            $this->info("Backup encrypted successfully");
        } catch (Exception $e) {
            $this->error("Encryption failed: " . $e->getMessage());
        }
    }

    /**
     * Compress backup file
     */
    protected function compressBackup($backupFile)
    {
        try {
            $content = Storage::get($backupFile);
            $compressed = gzencode($content, 9); // Maximum compression

            Storage::put($backupFile . '.gz', $compressed);
            Storage::delete($backupFile); // Remove uncompressed version

            $this->info("Backup compressed successfully");
        } catch (Exception $e) {
            $this->error("Compression failed: " . $e->getMessage());
        }
    }

    /**
     * Clean up old backups based on retention policy
     */
    protected function cleanupOldBackups()
    {
        $type = $this->option('type');
        $retentionDays = $this->getRetentionDays($type);

        $backupPath = "{$this->backupPath}/{$type}";
        $files = Storage::files($backupPath);

        $deletedCount = 0;
        foreach ($files as $file) {
            $fileTime = Storage::lastModified($file);
            $fileAge = Carbon::now()->diffInDays(Carbon::createFromTimestamp($fileTime));

            if ($fileAge > $retentionDays) {
                Storage::delete($file);
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            $this->info("Cleaned up {$deletedCount} old backup files");
        }
    }

    /**
     * Get retention days based on backup type
     */
    protected function getRetentionDays($type)
    {
        switch ($type) {
            case 'daily':
                return 7; // Keep daily backups for 7 days
            case 'weekly':
                return 28; // Keep weekly backups for 4 weeks
            case 'monthly':
                return 365; // Keep monthly backups for 1 year
            default:
                return 7;
        }
    }

    /**
     * Log backup operation
     */
    protected function logBackup($filename)
    {
        $logData = [
            'filename' => $filename,
            'type' => $this->option('type'),
            'encrypted' => $this->option('encrypt'),
            'compressed' => $this->option('compress'),
            'timestamp' => Carbon::now()->toISOString(),
            'user' => 'system',
        ];

        Log::info('Database backup created', $logData);
    }
}
