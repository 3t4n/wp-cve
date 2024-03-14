<?php
/*
Plugin Name: WordPressかんたんお引越し for お名前.com
Plugin URI: https://wordpress.org/plugins/wp-migration-for-onamaecom/
Description: お名前.com レンタルサーバーの「WordPressかんたんお引越し」をご利用いただくためのプラグインです。
Version: 1.0.9
Author: GMO Internet Group, Inc.
Author URI: https://www.onamae.com/
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: wp-migration-for-onamaecom
*/
// constant
define( 'OWM_DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR );
define( 'OWM_MIGRATION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'OWM_MIGRATION_PLUGIN_NAME', basename( plugin_dir_path( __FILE__ ) ) );
define( 'OWM_MIGRATION_HTTP_USER_AGENT', 'onamae-migration' );

define( 'OWM_MIGRATION_STATUS_NO_DATA', 'no_data' );

define( 'OWM_MIGRATION_STATUS_BACKUP_START', 'start_backup' );
define( 'OWM_MIGRATION_STATUS_BACKUP_DUMP_DB', 'dump_db' );
define( 'OWM_MIGRATION_STATUS_BACKUP_COMPRESS_ZIP', 'compress_zip' );
define( 'OWM_MIGRATION_STATUS_BACKUP_COMPRESS_TAR', 'compress_tar' );
define( 'OWM_MIGRATION_STATUS_BACKUP_COMPLETE', 'complete_backup' );

define( 'OWM_MIGRATION_STATUS_RESTORE_START', 'start_restore' );
define( 'OWM_MIGRATION_STATUS_RESTORE_DOWNLOAD_FILE', 'download_file' );
define( 'OWM_MIGRATION_STATUS_RESTORE_EXTRACT_ZIP', 'extract_zip' );
define( 'OWM_MIGRATION_STATUS_RESTORE_EXTRACT_TAR', 'extract_tar' );
define( 'OWM_MIGRATION_STATUS_RESTORE_DB', 'restore_db' );
define( 'OWM_MIGRATION_STATUS_RESTORE_COMPLETE', 'complete_restore' );

define( 'OWM_BACKUP_INFO_WP_OPTION_KEY', 'owm_backup_info' );
define( 'OWM_BACKUP_BULK_INSERT_LIMIT', 10 );

define( 'OWM_RESTORE_INFO_WP_OPTION_KEY', 'owm_restore_info' );
define( 'OWM_RESTORE_CRON_REQUEST_LOOPBACK', true );

define( 'OWM_TASK_DEFAULT_TIMEOUT', 45 );
define( 'OWM_TASK_DEFAULT_MAX_RETRY', 100 );
define( 'OWM_BACKUP_RETRY_THRESHOLD_DB_TABLE_ROW', 5000 );
define( 'OWM_BACKUP_RETRY_THRESHOLD_DIRECTORY', 500 );
define( 'OWM_BACKUP_RETRY_THRESHOLD_FILE', 5000 );
define( 'OWM_RESTORE_RETRY_THRESHOLD_DIRECTORY', 500 );
define( 'OWM_RESTORE_RETRY_THRESHOLD_FILE', 5000 );

define( 'OWM_EXECUTE_TIME_LIMIT', 3600 );

// import
$class_dir = OWM_MIGRATION_PLUGIN_DIR . 'classes' . DIRECTORY_SEPARATOR;
require_once $class_dir . 'class-owm-migration-logger.php';
require_once $class_dir . 'class-owm-migration-response.php';
require_once $class_dir . 'class-owm-migration-info.php';

require_once $class_dir . 'mysql' . DIRECTORY_SEPARATOR . 'class-owm-migration-mysql-query.php';
require_once $class_dir . 'archive' . DIRECTORY_SEPARATOR . 'class-owm-migration-archive-type.php';
require_once $class_dir . 'archive' . DIRECTORY_SEPARATOR . 'class-owm-migration-archive.php';
require_once $class_dir . 'utils' . DIRECTORY_SEPARATOR . 'class-owm-migration-file-utils.php';
require_once $class_dir . 'utils' . DIRECTORY_SEPARATOR . 'class-owm-migration-server-info.php';

$action_dir = $class_dir . 'actions' . DIRECTORY_SEPARATOR;
require_once $action_dir . 'class-owm-migration-action-base.php';
require_once $action_dir . 'class-owm-migration-action-wp-info.php';
require_once $action_dir . 'class-owm-backup-action-info.php';
require_once $action_dir . 'class-owm-backup-action-backup.php';
require_once $action_dir . 'class-owm-backup-action-remove.php';
require_once $action_dir . 'class-owm-backup-action-log.php';
require_once $action_dir . 'class-owm-restore-action-restore.php';
require_once $action_dir . 'class-owm-restore-action-remove.php';
require_once $action_dir . 'class-owm-restore-action-info.php';
require_once $action_dir . 'class-owm-restore-action-log.php';
require_once $action_dir . 'class-owm-ssl-action-create-challenge-file.php';
require_once $action_dir . 'class-owm-ssl-action-delete-challenge-file.php';

$job_dir = $class_dir . 'jobs' . DIRECTORY_SEPARATOR;
require_once $job_dir . 'class-owm-job-base.php';
require_once $job_dir . 'class-owm-backup-job.php';
require_once $job_dir . 'class-owm-restore-job.php';

$task_dir = $class_dir . 'jobs' . DIRECTORY_SEPARATOR . 'tasks' . DIRECTORY_SEPARATOR;
require_once $task_dir . 'class-owm-task-base.php';
require_once $task_dir . 'class-owm-task-backup-database.php';
require_once $task_dir . 'class-owm-task-backup-file.php';
require_once $task_dir . 'class-owm-task-backup-finish.php';
require_once $task_dir . 'class-owm-task-restore-download.php';
require_once $task_dir . 'class-owm-task-restore-database.php';
require_once $task_dir . 'class-owm-task-restore-file.php';
require_once $task_dir . 'class-owm-task-restore-finish.php';


require_once $class_dir . 'class-owm-migration-controller.php';

$controller = Owm_Migration_Controller::instance();


