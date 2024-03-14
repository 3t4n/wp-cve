<?php
/*
Plugin Name: Wing Wordpress Migrator
Plugin URI: https://wordpress.org/plugins/wing-migrator/
Description: ConoHa WINGのWordPressで「WING WordPress Migration」をご利用いただくためのプラグインです。
Version: 1.1.9
Author: GMO Internet Group, Inc.
Author URI: https://www.conoha.jp/
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: wing-migrator
*/

// constant
define( 'WWM_DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR );
define( 'WWM_MIGRATION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WWM_MIGRATION_PLUGIN_NAME', basename( plugin_dir_path( __FILE__ ) ) );
define( 'WWM_MIGRATION_HTTP_USER_AGENT', 'wing-migration' );

define( 'WWM_MIGRATION_STATUS_NO_DATA', 'no_data' );

define( 'WWM_MIGRATION_STATUS_BACKUP_START', 'start_backup' );
define( 'WWM_MIGRATION_STATUS_BACKUP_DUMP_DB', 'dump_db' );
define( 'WWM_MIGRATION_STATUS_BACKUP_COMPRESS_ZIP', 'compress_zip' );
define( 'WWM_MIGRATION_STATUS_BACKUP_COMPRESS_TAR', 'compress_tar' );
define( 'WWM_MIGRATION_STATUS_BACKUP_COMPLETE', 'complete_backup' );

define( 'WWM_MIGRATION_STATUS_RESTORE_START', 'start_restore' );
define( 'WWM_MIGRATION_STATUS_RESTORE_DOWNLOAD_FILE', 'download_file' );
define( 'WWM_MIGRATION_STATUS_RESTORE_EXTRACT_ZIP', 'extract_zip' );
define( 'WWM_MIGRATION_STATUS_RESTORE_EXTRACT_TAR', 'extract_tar' );
define( 'WWM_MIGRATION_STATUS_RESTORE_DB', 'restore_db' );
define( 'WWM_MIGRATION_STATUS_RESTORE_COMPLETE', 'complete_restore' );

define( 'WWM_BACKUP_INFO_WP_OPTION_KEY', 'wwm_backup_info' );
define( 'WWM_BACKUP_BULK_INSERT_LIMIT', 10 );

define( 'WWM_RESTORE_INFO_WP_OPTION_KEY', 'wwm_restore_info' );
define( 'WWM_RESTORE_CRON_REQUEST_LOOPBACK', true );

define( 'WWM_TASK_DEFAULT_TIMEOUT', 45 );
define( 'WWM_TASK_DEFAULT_MAX_RETRY', 100 );
define( 'WWM_BACKUP_RETRY_THRESHOLD_DB_TABLE_ROW', 5000 );
define( 'WWM_BACKUP_RETRY_THRESHOLD_DIRECTORY', 500 );
define( 'WWM_BACKUP_RETRY_THRESHOLD_FILE', 5000 );
define( 'WWM_RESTORE_RETRY_THRESHOLD_DIRECTORY', 500 );
define( 'WWM_RESTORE_RETRY_THRESHOLD_FILE', 5000 );

define( 'WWM_EXECUTE_TIME_LIMIT', 3600 );

// import
$class_dir = WWM_MIGRATION_PLUGIN_DIR . 'classes' . DIRECTORY_SEPARATOR;
require_once $class_dir . 'class-wwm-migration-logger.php';
require_once $class_dir . 'class-wwm-migration-response.php';
require_once $class_dir . 'class-wwm-migration-info.php';

require_once $class_dir . 'mysql' . DIRECTORY_SEPARATOR . 'class-wwm-migration-mysql-query.php';
require_once $class_dir . 'archive' . DIRECTORY_SEPARATOR . 'class-wwm-migration-archive-type.php';
require_once $class_dir . 'archive' . DIRECTORY_SEPARATOR . 'class-wwm-migration-archive.php';
require_once $class_dir . 'utils' . DIRECTORY_SEPARATOR . 'class-wwm-migration-file-utils.php';
require_once $class_dir . 'utils' . DIRECTORY_SEPARATOR . 'class-wwm-migration-server-info.php';

$action_dir = $class_dir . 'actions' . DIRECTORY_SEPARATOR;
require_once $action_dir . 'class-wwm-migration-action-base.php';
require_once $action_dir . 'class-wwm-migration-action-wp-info.php';
require_once $action_dir . 'class-wwm-backup-action-info.php';
require_once $action_dir . 'class-wwm-backup-action-backup.php';
require_once $action_dir . 'class-wwm-backup-action-remove.php';
require_once $action_dir . 'class-wwm-backup-action-log.php';
require_once $action_dir . 'class-wwm-restore-action-restore.php';
require_once $action_dir . 'class-wwm-restore-action-remove.php';
require_once $action_dir . 'class-wwm-restore-action-info.php';
require_once $action_dir . 'class-wwm-restore-action-log.php';
require_once $action_dir . 'class-wwm-ssl-action-create-challenge-file.php';
require_once $action_dir . 'class-wwm-ssl-action-delete-challenge-file.php';

$job_dir = $class_dir . 'jobs' . DIRECTORY_SEPARATOR;
require_once $job_dir . 'class-wwm-job-base.php';
require_once $job_dir . 'class-wwm-backup-job.php';
require_once $job_dir . 'class-wwm-restore-job.php';

$task_dir = $class_dir . 'jobs' . DIRECTORY_SEPARATOR . 'tasks' . DIRECTORY_SEPARATOR;
require_once $task_dir . 'class-wwm-task-base.php';
require_once $task_dir . 'class-wwm-task-backup-database.php';
require_once $task_dir . 'class-wwm-task-backup-file.php';
require_once $task_dir . 'class-wwm-task-backup-finish.php';
require_once $task_dir . 'class-wwm-task-restore-download.php';
require_once $task_dir . 'class-wwm-task-restore-database.php';
require_once $task_dir . 'class-wwm-task-restore-file.php';
require_once $task_dir . 'class-wwm-task-restore-finish.php';


require_once $class_dir . 'class-wwm-migration-controller.php';

$controller = Wwm_Migration_Controller::instance();


