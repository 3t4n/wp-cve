<?php

namespace Watchful\Helpers\BackupPlugins;

use Watchful\Exception;
use watchfulli\XClonerCore\Xcloner;
use watchfulli\XClonerCore\Xcloner_Archive;
use watchfulli\XClonerCore\Xcloner_Database;
use watchfulli\XClonerCore\Xcloner_File_System;
use watchfulli\XClonerCore\Xcloner_FileSystem;
use watchfulli\XClonerCore\Xcloner_Remote_Storage;
use watchfulli\XClonerCore\Xcloner_Settings;
use watchfulli\XClonerCore\Xcloner_Standalone;

class XClonerBackupPlugin implements BackupPluginInterface
{
    static $backup_archive_extensions = array("zip", "tar", "tgz", "tar.gz", "gz", "csv");

    static $xclonerBasePath = WP_PLUGIN_DIR.'/xcloner-backup-and-restore/xcloner.php';

    /** @var Xcloner | Xcloner_Standalone */
    private $plugin_container;

    /** @var string|null $hash */
    private $hash = null;

    /**
     * XClonerBackupPlugin constructor.
     * @param null $hash
     * @throws Exception
     */
    public function __construct($hash = null)
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        if (!file_exists(self::$xclonerBasePath)) {
            throw new Exception('XCloner plugin installation directory not found.');
        }

        require_once(plugin_dir_path(self::$xclonerBasePath).'/vendor/autoload.php');
        require_once(self::get_xcloner_main_class_path());

        if ($hash !== null) {
            $_POST['hash'] = $hash;
            $this->hash = $hash;
        }

        $this->plugin_container = self::get_xcloner_container_instance($hash);
    }

    /**
     * @throws Exception
     */
    private static function get_xcloner_main_class_path()
    {
        if (file_exists(plugin_dir_path(self::$xclonerBasePath).'includes/class-xcloner.php')) {
            return plugin_dir_path(self::$xclonerBasePath).'includes/class-xcloner.php';
        }

        if (file_exists(plugin_dir_path(self::$xclonerBasePath).'lib/Xcloner.php')) {
            return self::$xclonerBasePath;
        }

        throw new Exception('XCloner main class file not found.');
    }

    /**
     * @throws Exception
     */
    private static function get_xcloner_container_instance($hash = null)
    {
        if (class_exists('\\Watchfulli\\XClonerCore\\Xcloner_Standalone')) {
            return new Xcloner_Standalone();
        }

        if (class_exists('\\Watchfulli\\XClonerCore\\Xcloner')) {
            return new Xcloner($hash);
        }

        throw new Exception('XCloner main class file not found.');
    }

    /**
     * @return \DateTime | false
     */
    public function get_last_backup_date()
    {
        $backup_list = $this->get_backup_list();
        $backup_timestamps = array_column($backup_list, 'timestamp');
        if (empty($backup_list) || empty($backup_timestamps)) {
            return false;
        }
        rsort($backup_timestamps);

        return date_create()->setTimestamp($backup_timestamps[0]);
    }

    public function get_backup_list()
    {
        /** @var Xcloner_File_System | Xcloner_FileSystem $xcloner_file_system */
        $xcloner_file_system = $this->plugin_container->get_xcloner_filesystem();
        $backup_list = array();
        $available_storage = array_merge($this->get_available_remote_storage(), ['' => 'Local']);
        foreach ($available_storage as $storage => $storage_name) {
            try {
                $storage_archives = $xcloner_file_system->get_backup_archives_list($storage);
            } catch (\Exception $exception) {
                continue;
            }
            foreach ($storage_archives as $backup_archive_full_name => $backup_details) {
                if (isset($backup_list[$backup_archive_full_name])) {
                    $backup_list[$backup_archive_full_name]['storage'][] = $storage_name;
                    continue;
                }
                $backup_details['storage'] = array($storage_name);
                $backup_list[$backup_archive_full_name] = $backup_details;
            }
        }

        return $backup_list;
    }

    public function get_available_remote_storage()
    {
        return $this->plugin_container->get_xcloner_remote_storage()->get_available_storages();
    }

    public function start_backup()
    {
        /** @var Xcloner_Settings $xcloner_settings */
        $xcloner_settings = $this->plugin_container->get_xcloner_settings();
        $hash = $xcloner_settings->generate_new_hash();

        return array(
            'hash' => $hash,
        );
    }

    public static function get_next_step($current_step)
    {
        switch ($current_step) {
            case 'file_recursion':
                return 'database_recursion';
            case 'database_recursion':
                return 'incremental_backup';
            case 'incremental_backup':
                return 'remote_storage';
            case 'remote_storage':
                return 'cleanup';
            default:
                return 'file_recursion';
        }
    }

    private static function update_action(&$params)
    {
        if (empty($params['action'])) {
            $params['action'] = 'file_recursion';
        }
        if (!empty($params[$params['action']]) && $params[$params['action']]['finished']) {
            $params['action'] = self::get_next_step($params['action']);
        }
    }

    /**
     * @throws Exception
     */
    public function step_backup($params = array())
    {
        $this->validate_parameters($params);

        self::update_action($params);
        switch ($params['action']) {
            case 'file_recursion':
                $this->step_file_recursion($params);
                break;
            case 'database_recursion':
                $this->step_database_recursion($params);
                break;
            case 'incremental_backup':
                $this->step_incremental_backup($params);
                break;
            case 'remote_storage':
                $this->step_remote_storage($params);
                break;
            case 'cleanup':
                $this->step_cleanup($params);
                break;
        }

        return $params;
    }

    /**
     * @throws Exception
     */
    private function validate_parameters($params)
    {
        if ($this->hash === null) {
            throw new Exception('Backup hash is missing');
        }

        if (
            !empty($params['user_settings']['remote_storage']['resource']) &&
            !array_key_exists(
                $params['user_settings']['remote_storage']['resource'],
                $this->get_available_remote_storage()
            )
        ) {
            throw new Exception(
                'Backup remote resource "'.$params['user_settings']['remote_storage']['resource'].'"  is unavailable'
            );
        }
    }

    private function step_file_recursion(&$params)
    {
        if (empty($params['file_recursion'])) {
            $params['file_recursion'] = array(
                'init' => true,
                'finished' => false,
                'total_files_num' => 0,

            );
        }
        /** @var Xcloner_File_System | Xcloner_FileSystem $xcloner_file_system */
        $xcloner_file_system = $this->plugin_container->get_xcloner_filesystem();
        $params['file_recursion']['finished'] = !$xcloner_file_system->start_file_recursion(
            $params['file_recursion']['init']
        );
        $params['file_recursion']['total_files_num'] += $xcloner_file_system->get_scanned_files_num();
        $params['file_recursion']['total_files_size'] += sprintf(
            '%.2f',
            $xcloner_file_system->get_scanned_files_total_size() / (1024 * 1024)
        );
        $params['file_recursion']['last_logged_file'] = $xcloner_file_system->last_logged_file();
        $params['file_recursion']['init'] = false;
    }

    private function step_database_recursion(&$params)
    {
        if (empty($params['database_recursion'])) {
            $params['database_recursion'] = array(
                'init' => true,
                'finished' => false,
            );
        }

        if (empty($params['db_parameters'])) {
            $params['db_parameters'] = array(
                '#' => array(DB_NAME),
            );
        }
        /** @var Xcloner_Database $xcloner_database */
        $xcloner_database = $this->plugin_container->get_xcloner_database();
        $params['database_recursion'] = $xcloner_database->start_database_recursion(
            $params['db_parameters'],
            $params['database_recursion'],
            (int)$params['database_recursion']['init']
        );
        if (!empty($params['database_recursion']['stats'])) {
            $params['db_stats'] = $params['database_recursion']['stats'];
            $params['db_stats']['processed_records'] = 0;
        }
        $params['database_recursion']['init'] = false;
        $params['database_recursion']['finished'] = (boolean)$params['database_recursion']['finished'];
        if (!empty($params['database_recursion']['processedRecords'])) {
            $params['db_stats']['processed_records'] += $params['database_recursion']['processedRecords'];
        }
    }

    private function step_incremental_backup(&$params)
    {
        if (empty($params['incremental_backup'])) {
            $params['incremental_backup'] = array(
                'init' => true,
                'finished' => false,
                'extra' => array(),
            );
        }

        if (empty($params['backup_parameters'])) {
            $params['backup_parameters'] = array(
                'backup_name' => 'backup_[domain]-[time]-sql',
                'email_notification' => '',
                'diff_start_date' => '',
                'backup_comments' => 'This backup comes from Watchful',
            );
        }

        /** @var Xcloner_Archive $xcloner_archive_system */
        $xcloner_archive_system = $this->plugin_container->get_archive_system();
        $params['incremental_backup'] = $xcloner_archive_system->start_incremental_backup(
            $params['backup_parametes'],
            $params['incremental_backup']['extra'],
            (int)$params['incremental_backup']['init']
        );

        $params['incremental_backup']['init'] = false;
        $params['incremental_backup']['finished'] = (boolean)$params['incremental_backup']['finished'];
        if ($params['incremental_backup']['finished']) {
            $params['backup_archive_name_full'] = $params['incremental_backup']['extra']['backup_archive_name_full'];
        }
    }

    private function step_remote_storage(&$params)
    {
        if (
            empty($params['user_settings']['remote_storage']) ||
            $params['user_settings']['remote_storage']['resource'] === null
        ) {
            $params['remote_storage']['finished'] = true;

            return;
        }

        $params['remote_storage'] = array(
            'init' => true,
            'finished' => false,
        );
        /** @var Xcloner_Remote_Storage $xcloner_remote_storage */
        $xcloner_remote_storage = $this->plugin_container->get_xcloner_remote_storage();
        $result = $xcloner_remote_storage->upload_backup_to_storage(
            $params['backup_archive_name_full'],
            $params['user_settings']['remote_storage']['resource']
        );
        if (!$result) {
            throw new Exception('Unable to upload backup to remote storage!');
        }
        $params['remote_storage']['finished'] = true;
    }

    private function step_cleanup(&$params)
    {
        /** @var Xcloner_File_System $xcloner_file_system */
        $xcloner_file_system = $this->plugin_container->get_xcloner_filesystem();
        if (!empty($params['user_settings']['remote_storage']['resource']) && $params['user_settings']['remote_storage']['delete_local_backup']) {
            $params['delete_local'] = $xcloner_file_system->delete_backup_by_name($params['backup_archive_name_full']);
        }
        $xcloner_file_system->remove_tmp_filesystem();
        $xcloner_file_system->backup_storage_cleanup();
        $xcloner_file_system->cleanup_tmp_directories();
        $params['completed'] = true;
    }
}
