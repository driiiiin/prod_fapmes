<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the database backup system.
    | You can customize backup settings, retention policies, and security options.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Backup Storage Path
    |--------------------------------------------------------------------------
    |
    | The base path where backup files will be stored. This should be in a
    | secure, private location that is not publicly accessible.
    |
    */
    'storage_path' => env('BACKUP_STORAGE_PATH', 'private/backups'),

    /*
    |--------------------------------------------------------------------------
    | Default Backup Type
    |--------------------------------------------------------------------------
    |
    | The default type of backup to create when no type is specified.
    | Options: daily, weekly, monthly
    |
    */
    'default_type' => env('BACKUP_DEFAULT_TYPE', 'daily'),

    /*
    |--------------------------------------------------------------------------
    | Retention Policies
    |--------------------------------------------------------------------------
    |
    | Define how long to keep different types of backups before automatic cleanup.
    | Values are in days.
    |
    */
    'retention' => [
        'daily' => env('BACKUP_RETENTION_DAILY', 7),      // 7 days
        'weekly' => env('BACKUP_RETENTION_WEEKLY', 28),   // 4 weeks
        'monthly' => env('BACKUP_RETENTION_MONTHLY', 365), // 1 year
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | Configure security options for backup files.
    |
    */
    'security' => [
        'encrypt_by_default' => env('BACKUP_ENCRYPT_BY_DEFAULT', false),
        'compress_by_default' => env('BACKUP_COMPRESS_BY_DEFAULT', true),
        'file_permissions' => env('BACKUP_FILE_PERMISSIONS', 0600), // Read/write for owner only
    ],

    /*
    |--------------------------------------------------------------------------
    | Database-Specific Settings
    |--------------------------------------------------------------------------
    |
    | Configuration options for different database types.
    |
    */
    'databases' => [
        'sqlite' => [
            'enabled' => true,
            'file_extension' => 'sqlite',
        ],
        'mysql' => [
            'enabled' => true,
            'file_extension' => 'sql',
            'mysqldump_options' => [
                '--single-transaction',
                '--routines',
                '--triggers',
                '--add-drop-database',
                '--add-drop-table',
            ],
        ],
        'mariadb' => [
            'enabled' => true,
            'file_extension' => 'sql',
            'mysqldump_options' => [
                '--single-transaction',
                '--routines',
                '--triggers',
                '--add-drop-database',
                '--add-drop-table',
            ],
        ],
        'pgsql' => [
            'enabled' => true,
            'file_extension' => 'sql',
            'pg_dump_options' => [
                '--clean',
                '--if-exists',
                '--no-owner',
                '--no-privileges',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Configure notifications for backup events.
    |
    */
    'notifications' => [
        'on_success' => env('BACKUP_NOTIFY_SUCCESS', false),
        'on_failure' => env('BACKUP_NOTIFY_FAILURE', true),
        'email' => env('BACKUP_NOTIFICATION_EMAIL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Settings
    |--------------------------------------------------------------------------
    |
    | Configure logging for backup operations.
    |
    */
    'logging' => [
        'enabled' => env('BACKUP_LOGGING', true),
        'channel' => env('BACKUP_LOG_CHANNEL', 'daily'),
        'level' => env('BACKUP_LOG_LEVEL', 'info'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Filename Template
    |--------------------------------------------------------------------------
    |
    | Template for generating backup filenames. Available placeholders:
    | {app_name} - Application name
    | {type} - Backup type (daily, weekly, monthly)
    | {timestamp} - Current timestamp
    | {database} - Database name
    | {extension} - File extension based on database type
    |
    */
    'filename_template' => env('BACKUP_FILENAME_TEMPLATE', '{app_name}_backup_{type}_{timestamp}.{extension}'),

    /*
    |--------------------------------------------------------------------------
    | Maximum Backup Size
    |--------------------------------------------------------------------------
    |
    | Maximum size for backup files in bytes. If a backup exceeds this size,
    | it will be logged as a warning. Set to 0 to disable size checking.
    |
    */
    'max_size' => env('BACKUP_MAX_SIZE', 0), // 0 = no limit

    /*
    |--------------------------------------------------------------------------
    | Backup Verification
    |--------------------------------------------------------------------------
    |
    | Enable backup verification to ensure backup files are valid.
    | This may add extra time to the backup process.
    |
    */
    'verify_backups' => env('BACKUP_VERIFY', false),

];
