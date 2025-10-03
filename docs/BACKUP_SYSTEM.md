# FAPMES Database Backup System

## Overview

The FAPMES (Financial Accomplishment and Physical Monitoring and Evaluation System) includes a comprehensive database backup system designed to protect critical project data, financial accomplishments, physical accomplishments, and user information.

## Features

### 🔒 Security Features
- **File Encryption**: Optional AES-256 encryption using Laravel's encryption system
- **Compression**: Gzip compression to reduce storage space
- **Restrictive Permissions**: Backup files are set to 0600 (owner read/write only)
- **Private Storage**: Backups stored in non-public directories
- **Secure Logging**: All backup operations are logged with timestamps

### 📊 Database Support
- **SQLite**: Direct file copy with integrity checking
- **MySQL/MariaDB**: mysqldump with transaction safety
- **PostgreSQL**: pg_dump with comprehensive options
- **Automatic Detection**: Automatically detects and configures for your database type

### 🗂️ Organization
- **Categorized Storage**: Daily, weekly, and monthly backup categories
- **Automatic Cleanup**: Configurable retention policies
- **Timestamped Files**: Clear naming convention with creation timestamps
- **Size Monitoring**: Optional backup size limits and warnings

## Usage

### Basic Commands

```bash
# Create a daily backup (default)
php artisan app:backup-database

# Create a weekly backup
php artisan app:backup-database --type=weekly

# Create a monthly backup
php artisan app:backup-database --type=monthly

# Create an encrypted backup
php artisan app:backup-database --encrypt

# Create a compressed backup
php artisan app:backup-database --compress

# Create an encrypted and compressed backup
php artisan app:backup-database --encrypt --compress
```

### Command Options

| Option | Description | Default |
|--------|-------------|---------|
| `--type` | Backup type: daily, weekly, monthly | daily |
| `--encrypt` | Encrypt the backup file | false |
| `--compress` | Compress the backup file | false |

## Storage Location

Backups are stored in the following structure:

```
storage/app/private/backups/
├── daily/
│   ├── fapmes_backup_daily_2025-01-15_14-30-25.sql
│   └── fapmes_backup_daily_2025-01-16_09-15-10.sql
├── weekly/
│   └── fapmes_backup_weekly_2025-01-12_23-45-00.sql
└── monthly/
    └── fapmes_backup_monthly_2025-01-01_00-00-00.sql
```

## Configuration

### Environment Variables

Add these to your `.env` file to customize backup behavior:

```env
# Backup Storage Path
BACKUP_STORAGE_PATH=private/backups

# Default Backup Type
BACKUP_DEFAULT_TYPE=daily

# Retention Policies (in days)
BACKUP_RETENTION_DAILY=7
BACKUP_RETENTION_WEEKLY=28
BACKUP_RETENTION_MONTHLY=365

# Security Settings
BACKUP_ENCRYPT_BY_DEFAULT=false
BACKUP_COMPRESS_BY_DEFAULT=true
BACKUP_FILE_PERMISSIONS=0600

# Notification Settings
BACKUP_NOTIFY_SUCCESS=false
BACKUP_NOTIFY_FAILURE=true
BACKUP_NOTIFICATION_EMAIL=admin@example.com

# Logging Settings
BACKUP_LOGGING=true
BACKUP_LOG_CHANNEL=daily
BACKUP_LOG_LEVEL=info

# File Size Limits
BACKUP_MAX_SIZE=0

# Backup Verification
BACKUP_VERIFY=false
```

### Configuration File

The backup system uses `config/backup.php` for detailed configuration. You can modify this file to:

- Customize database-specific options
- Set mysqldump/pg_dump parameters
- Configure filename templates
- Define retention policies

## Retention Policies

The system automatically manages backup retention:

| Backup Type | Retention Period | Purpose |
|-------------|------------------|---------|
| Daily | 7 days | Short-term recovery |
| Weekly | 28 days (4 weeks) | Medium-term recovery |
| Monthly | 365 days (1 year) | Long-term archival |

## Security Considerations

### File Security
- Backup files are stored with restrictive permissions (0600)
- Files are stored in private directories not accessible via web
- Optional encryption provides additional data protection

### Access Control
- Only authorized system users should have access to backup directories
- Consider implementing additional access controls at the OS level
- Regular security audits of backup storage locations

### Encryption
- Uses Laravel's built-in encryption system
- Encryption key is stored in `APP_KEY` environment variable
- Encrypted backups require the same encryption key for restoration

## Monitoring and Logging

### Log Locations
- Application logs: `storage/logs/laravel.log`
- Backup-specific logs: `storage/logs/backup.log` (if configured)

### Log Format
```json
{
    "filename": "fapmes_backup_daily_2025-01-15_14-30-25.sql",
    "type": "daily",
    "encrypted": true,
    "compressed": true,
    "timestamp": "2025-01-15T14:30:25.000000Z",
    "user": "system"
}
```

## Automation

### Cron Job Setup

Add to your server's crontab for automated backups:

```bash
# Daily backup at 2:00 AM
0 2 * * * cd /path/to/fapmes && php artisan app:backup-database --type=daily --compress

# Weekly backup on Sundays at 3:00 AM
0 3 * * 0 cd /path/to/fapmes && php artisan app:backup-database --type=weekly --encrypt --compress

# Monthly backup on the 1st at 4:00 AM
0 4 1 * * cd /path/to/fapmes && php artisan app:backup-database --type=monthly --encrypt --compress
```

### Laravel Task Scheduling

Add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Daily backup
    $schedule->command('app:backup-database --type=daily --compress')
             ->daily()
             ->at('02:00');
    
    // Weekly backup
    $schedule->command('app:backup-database --type=weekly --encrypt --compress')
             ->weekly()
             ->sundays()
             ->at('03:00');
    
    // Monthly backup
    $schedule->command('app:backup-database --type=monthly --encrypt --compress')
             ->monthly()
             ->at('04:00');
}
```

## Troubleshooting

### Common Issues

1. **Permission Denied**
   - Ensure the web server user has write permissions to storage directory
   - Check that backup directories exist and are writable

2. **mysqldump Not Found**
   - Install MySQL client tools
   - Ensure mysqldump is in system PATH

3. **pg_dump Not Found**
   - Install PostgreSQL client tools
   - Ensure pg_dump is in system PATH

4. **Encryption Errors**
   - Verify APP_KEY is set in .env file
   - Ensure encryption key is consistent across environments

### Verification Commands

```bash
# Check backup directory structure
ls -la storage/app/private/backups/

# Verify backup file integrity
file storage/app/private/backups/daily/fapmes_backup_daily_*.sql

# Check backup logs
tail -f storage/logs/laravel.log | grep backup
```

## Best Practices

1. **Regular Testing**: Periodically test backup restoration procedures
2. **Off-site Storage**: Consider copying backups to external storage
3. **Monitoring**: Set up alerts for backup failures
4. **Documentation**: Maintain restoration procedures for each backup type
5. **Security Audits**: Regularly review backup access and permissions
6. **Performance**: Monitor backup duration and adjust scheduling as needed

## Support

For issues with the backup system:

1. Check the application logs for error messages
2. Verify database connectivity and permissions
3. Ensure all required system tools are installed
4. Review configuration settings in `config/backup.php`
5. Test backup commands manually before automation

---

**Note**: This backup system is designed for the FAPMES application and includes security measures appropriate for government/project management data. Always test backup and restoration procedures in a safe environment before relying on them in production.
