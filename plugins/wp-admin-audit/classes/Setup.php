<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

require_once 'Constants.php';
require_once 'Application/Log.php';
require_once 'Application/Database.php';
require_once 'Application/Settings.php';
require_once 'Application/Version.php';

class WADA_Setup
{
    public $schedules = array(
        array('scheduleName' => 'wp_admin_audit_maintenance',   'recurrence' => 'twicedaily',   'nextRunDelta' => 300),
        array('scheduleName' => 'wp_admin_audit_queue_work',    'recurrence' => '1min',         'nextRunDelta' => 60)
    );

    protected static function logDuringSetup($message, $loggingLevel = 'INFO'){
        // we need this for two reasons:
        // 1. make sure to only log when we have our logging class
        // 2. send the logging entries with the type LOGENTRY_INSTALLER ($isInstallEntry) flag
        //    -> this is required so that the logging class will not try to use WADA_Settings etc. (and its DB table)
        //       because it may or may not exist at the time of calling during the setup
        $ret = false;
        if(class_exists('WADA_Log')){
            $isInstallEntry = 1; // ($typeNr == WADA_Constants::LOGENTRY_INSTALLER);
            switch($loggingLevel){
                case 'ERROR':
                    $ret = WADA_Log::error($message, $isInstallEntry);
                    break;
                case 'INFO':
                    $ret = WADA_Log::info($message, $isInstallEntry);
                    break;
                case 'DEBUG':
                    $ret = WADA_Log::debug($message, $isInstallEntry);
                    break;
                default:
                    $ret = WADA_Log::info($message, $isInstallEntry);
            }
        }
        return $ret;
    }

    public static function errorLogDuringSetup($message){
        return self::logDuringSetup($message, 'ERROR');
    }

    public static function infoLogDuringSetup($message){
        return self::logDuringSetup($message, 'INFO');
    }

    public static function debugLogDuringSetup($message){
        if('false' === 'true'){
            // only log debug stuff on DEV machine
            // on live considered "dangerous" to run during setup/install time
            return self::logDuringSetup($message, 'DEBUG');
        }
        return false;
    }

    public function installOrUpdate(){
        if(class_exists('WADA_Log')){
            WADA_Log::initFile(); // we need to make sure that on installation the log files get created
        }
        self::infoLogDuringSetup('installOrUpdate');
        $this->setupInitialDatabaseIfNeeded();
        $this->runUpdatesAndMigrations();
        $this->scheduleEvents();
        $this->createUploadsDirectory();
    }

    protected function setupInitialDatabaseIfNeeded(){
        self::infoLogDuringSetup('setupInitialDatabaseIfNeeded');
        $this->createTablesIfNotExisting();
        $this->createIndexesIfNotExisting();
        $this->setupStandardSettingsIfNeeded();
        $this->setupSensorsIfNeeded();
    }

    public function createTablesIfNotExisting(){
        self::infoLogDuringSetup('createTablesIfNotExisting');
        global $wpdb;
        $charsetCollate = $wpdb->get_charset_collate();

        $tblSQLs = array();
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_sensors (
                      id INT NOT NULL,
                      extension_id INT NOT NULL DEFAULT 0,
                      severity INT NULL,
                      active TINYINT(1) NOT NULL DEFAULT 1,
                      name VARCHAR(45) NOT NULL,
                      description TEXT NOT NULL,
                      event_group VARCHAR(45) NULL,
                      event_category VARCHAR(45) NULL,
                      PRIMARY KEY (id)
                      ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_sensor_options (
                      id INT NOT NULL AUTO_INCREMENT,
                      sensor_id INT NOT NULL,
                      option_key VARCHAR(45) NOT NULL,
                      option_value TEXT NULL,
                      PRIMARY KEY (id),
                      UNIQUE INDEX sensor_option_key_unq (option_key ASC)
                      ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_events (
                      id INT NOT NULL AUTO_INCREMENT,
                      occurred_on TIMESTAMP NOT NULL,
                      sensor_id INT NOT NULL,
                      site_id INT NOT NULL,
                      user_id INT NOT NULL,
                      user_name VARCHAR(255) NULL,
                      user_email VARCHAR(255) NULL,
                      object_type VARCHAR(45) NULL,
                      object_id INT NULL,
                      source_ip VARCHAR(39) NULL,
                      source_client VARCHAR(255) NULL,
                      check_value_head VARCHAR(128) NOT NULL,
                      check_value_full VARCHAR(128) NULL,
                      replication_done TINYINT(1) NOT NULL DEFAULT 0,
                      PRIMARY KEY (id)
                      ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_event_infos (
                      id INT NOT NULL AUTO_INCREMENT,
                      event_id INT NOT NULL,
                      info_key VARCHAR(45) NOT NULL,
                      info_value TEXT NULL,
                      prior_value TEXT NULL,
                      PRIMARY KEY (id)
                      ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_event_replications (
                      id INT NOT NULL AUTO_INCREMENT,
                      event_id INT NOT NULL,
                      replicator_id INT NOT NULL,
                      queued_at TIMESTAMP NOT NULL,
                      replication_attempts INT NOT NULL DEFAULT 0,
                      replication_completed TINYINT(1) NOT NULL DEFAULT 0,
                      PRIMARY KEY (id)
                      ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_extensions (
                      id INT NOT NULL AUTO_INCREMENT,
                      active TINYINT(1) NOT NULL DEFAULT 0,
                      name VARCHAR(45) NOT NULL,
                      plugin_folder VARCHAR(255) NOT NULL,
                      ext_api_version INT NOT NULL,
                      PRIMARY KEY (id)
                      ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_settings (
                      id INT NOT NULL,
                      setting_value TEXT NULL,
                      PRIMARY KEY (id)
                      ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_notifications (
                      id INT NOT NULL AUTO_INCREMENT,
                      name VARCHAR(190) NULL,
                      active TINYINT(1) NOT NULL DEFAULT 1,
                      PRIMARY KEY (id)
                      ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_notification_targets (
                            id INT NOT NULL AUTO_INCREMENT,
                            notification_id INT NOT NULL,
                            channel_type VARCHAR(45) NOT NULL,
                            target_type VARCHAR(45) NOT NULL,
                            target_id INT NULL,
                            target_str_id VARCHAR(190) NULL,
                            PRIMARY KEY (id)
                            ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_notification_triggers (
                            id INT NOT NULL AUTO_INCREMENT,
                            notification_id INT NOT NULL,
                            trigger_type VARCHAR(45) NOT NULL,
                            trigger_id INT NULL,
                            trigger_str_id VARCHAR(190) NULL,
                            PRIMARY KEY (id)
                            ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_event_notifications (
                      id INT NOT NULL AUTO_INCREMENT,
                      event_id INT NOT NULL,
                      notification_id INT NOT NULL,
                      sent TINYINT(0) NOT NULL DEFAULT 0,
                      sent_on TIMESTAMP NULL,
                      send_errors TINYINT(0) NOT NULL DEFAULT 0,
                      PRIMARY KEY (id)
                      ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_notification_queue_map (
                      id INT NOT NULL AUTO_INCREMENT,
                      event_notification_id INT NOT NULL,
                      queue_id INT NOT NULL,
                      PRIMARY KEY (id)
                      ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_notification_queue (
                        id INT NOT NULL AUTO_INCREMENT,
                        channel_type VARCHAR(45) NOT NULL,
                        email_address VARCHAR(255) NULL,
                        tel_nr VARCHAR(255) NULL,
                        attempt_count INT NOT NULL DEFAULT 0,
                        lock_id INT NULL,
                        is_locked TINYINT(1) NOT NULL DEFAULT 0,
                        last_lock TIMESTAMP NULL,
                        PRIMARY KEY (id)
                        ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_event_notification_log (
                        id INT NOT NULL AUTO_INCREMENT,
                        event_notification_id INT NOT NULL,
                        event_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        event_type INT NOT NULL,
                        channel_type VARCHAR(45) NOT NULL,
                        recips TEXT NULL DEFAULT NULL,
                        int_val1 INT NULL,
                        int_val2 INT NULL,
                        int_val3 INT NULL,
                        int_val4 INT NULL,
                        msg TEXT NULL DEFAULT NULL,
                        PRIMARY KEY (id)
                        ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_users (
                        user_id INT NOT NULL,
                        last_seen TIMESTAMP NULL,
                        last_login TIMESTAMP NULL,
                        last_pw_change TIMESTAMP NULL,
                        last_pw_change_reminder TIMESTAMP NULL,
                        tracked_since TIMESTAMP NULL,
                        PRIMARY KEY (user_id)
                        ) ".$charsetCollate.";";
        $tblSQLs[] = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."wada_logins (
                        id INT NOT NULL AUTO_INCREMENT,
                        login_date DATE NOT NULL,
                        login_successful TINYINT(1) NOT NULL,
                        user_login VARCHAR(127) NOT NULL,
                        user_login_existing TINYINT(1) NOT NULL,
                        user_id INT NULL,
                        ip_address VARBINARY(16) NOT NULL,
                        PRIMARY KEY (id)
                        ) ".$charsetCollate.";";
        
        foreach($tblSQLs as $tableSql){
            $wpdb->query($tableSql);
        }
    }

    public function createIndexesIfNotExisting(){
        global $wpdb;
        $res = array();
        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_sensor_options',
            'wada_sensor_options_idx1',
            array('sensor_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_events',
            'wada_events_sensors_idx1',
            array('sensor_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_events',
            'wada_events_users_idx1',
            array('user_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_events',
            'wada_events_objects_idx1',
            array('object_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_event_infos',
            'wada_event_infos_events_idx1',
            array('event_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_event_notifications',
            'wada_event_notifications_events_idx1',
            array('event_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_event_notifications',
            'wada_event_notifications_notifications_idx1',
            array('notification_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_event_notifications',
            'wada_event_notifications_event_noti_idx1',
            array('event_id', 'notification_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_notification_targets',
            'wada_notification_targets_notifications_idx1',
            array('notification_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_notification_targets',
            'wada_notification_targets_target_type_idx1',
            array('target_type'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_notification_triggers',
            'wada_notification_triggers_notifications_idx1',
            array('notification_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_notification_triggers',
            'wada_notification_triggers_trigger_type_idx1',
            array('trigger_type'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_notification_queue',
            'wada_notification_queue_channel_type_idx1',
            array('channel_type'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_notification_queue_map',
            'wada_notification_queue_map_evnoti_idx1',
            array('event_notification_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_notification_queue_map',
            'wada_notification_queue_map_queue_idx1',
            array('queue_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_notification_queue_map',
            'wada_notification_queue_map_evnoti_queue_idx1',
            array('event_notification_id', 'queue_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_event_notification_log',
            'wada_event_notification_log_evnoti_idx1',
            array('event_notification_id'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_logins',
            'wada_logins_ip_addr_idx1',
            array('ip_address'));

        $res[] 	= WADA_Database::createIndexIfNotExists(
            $wpdb->prefix.'wada_event_replications',
            'wada_event_replications_events_idx1',
            array('event_id'));

        $filteredRes = array_filter($res);
        if(count($filteredRes)>0) {
            self::infoLogDuringSetup('createIndexesIfNotExisting res: ' . print_r($res, true));
        }else{
            self::infoLogDuringSetup('createIndexesIfNotExisting All indexes already there');
        }
        return $res;
    }

    public function setupStandardSettingsIfNeeded(){
        $settingsMetadata = WADA_Settings::getMetadataForAllSettings();
        //self::infoLogDuringSetup('setupStandardSettingsIfNeeded (all): '.print_r($settingsMetadata, true));
        foreach ($settingsMetadata as $settingProperty => $metadata) {
            //self::infoLogDuringSetup('setupStandardSettingsIfNeeded (settingProperty ' . $settingProperty . '): '.print_r($metadata, true));
            if(!WADA_Settings::doesSettingExist($metadata->settingId)){
                call_user_func(array('WADA_Settings', $metadata->setMethod), $metadata->defaultVal);
            }
        }
    }

    public function setupSensorsIfNeeded(){
        if(!class_exists('WADA_Sensor_Base')){
            require_once('Sensors/Base.php');
        }
        if(!class_exists('WADA_SensorsDB')){
            require_once('Sensors/SensorsDB.php');
        }
        $sensors = WADA_SensorsDB::getSensors();
        return self::addSensorsToDatabase($sensors);
    }

    public static function addSensorsToDatabase($sensors){
        global $wpdb;
        $sensorIds = array_column($sensors, 'id');
        $checkQuery = 'SELECT sensor_catalog.sensor_id AS id FROM (SELECT 0 AS sensor_id UNION ALL SELECT '. implode(' UNION ALL SELECT ', $sensorIds).') sensor_catalog ';
        $checkQuery .= 'LEFT JOIN '.$wpdb->prefix.'wada_sensors sen ON (sensor_catalog.sensor_id = sen.id) ';
        $checkQuery .= 'WHERE sen.id IS NULL AND sensor_catalog.sensor_id > 0';
        //self::infoLogDuringSetup('addSensorsToDatabase: '.$checkQuery);

        $nrSensorsAdded = 0;
        $sensorToAdd = $wpdb->get_results($checkQuery, ARRAY_A);
        if(count($sensorToAdd)>0) {
            self::infoLogDuringSetup('addSensorsToDatabase #sensors to add: '.count($sensorToAdd));
            $sensorIdsToAdd = array_column($sensorToAdd, 'id');

            //self::infoLogDuringSetup('addSensorsToDatabase sensorToAdd '.print_r($sensorToAdd, true));
            //self::infoLogDuringSetup('addSensorsToDatabase sensorIdsToAdd: '.print_r($sensorIdsToAdd, true));

            $sensorModel = new WADA_Model_Sensor();

            foreach ($sensorIdsToAdd as $sensorId) {
                if ($sensorId > 0 && array_key_exists($sensorId, $sensors)) {
                    $newSensor = $sensors[$sensorId];
                    self::infoLogDuringSetup('setupSensorsIfNeeded add sensor ' . print_r($newSensor, true));
                    $res = $sensorModel->store($newSensor);
                    if ($res === false) {
                        self::infoLogDuringSetup('addSensorsToDatabase Failed to create sensor ' . $sensorId . ', error from model: '.$sensorModel->_last_error);
                    } else {
                        self::infoLogDuringSetup('addSensorsToDatabase Added sensor ' . $sensorId);
                        $nrSensorsAdded++;
                    }
                }
            }
            self::infoLogDuringSetup('addSensorsToDatabase #Sensors added: '.$nrSensorsAdded);
        }else{
            self::infoLogDuringSetup('addSensorsToDatabase All sensors in DB, nothing new to add');
        }
        return $nrSensorsAdded;
    }

    protected function getCurrentDatabaseVersion(){
        $currentVersion = '1.2.9';
        $currentVersion = implode('.', array_slice(explode('.', $currentVersion), 0, 3)); // only extract first three version levels
        return ($currentVersion.'.'.intval('127')); // attach build version as 4th level
    }

    public function isDatabaseUpdateNeeded(){
        $dbVersion = WADA_Settings::getDatabaseVersion('1.0.0');
        $currentVersion = $this->getCurrentDatabaseVersion();
        $updateNeeded = (version_compare($currentVersion, $dbVersion, ">") || 'false' === 'true');

        if($updateNeeded){
            self::infoLogDuringSetup('isDatabaseUpdateNeeded UPDATE NEEDED (current: ' . $currentVersion . ', db version: ' . $dbVersion . ')');
        }else{
            self::debugLogDuringSetup('isDatabaseUpdateNeeded No updated needed (current: ' . $currentVersion . ', db version: ' . $dbVersion . ')');
        }

        if('false' === 'true' && isset($_GET['devforce'])){
            self::infoLogDuringSetup('isDatabaseUpdateNeeded FORCE WP AUTO UPDATE');
            wp_maybe_auto_update();
        }

        if('false' === 'true' && isset($_GET['devforce'])){
            self::infoLogDuringSetup('isDatabaseUpdateNeeded FORCE MAINTENANCE RUN');
            if(class_exists('WADA_Maintenance')) {
                WADA_Maintenance::scheduledRun();
            }
        }

        return $updateNeeded;
    }

    protected function doAllMigrations(){
        if(!class_exists('WADA_Migration_Base')){
            require_once('Application/Migrations/Base.php');
        }

        // this defines the scope and the order of the migrations
        $migrationPaths = array();
        $migrationPaths[] = dirname(__FILE__) .'/Application/Migrations/1.2/';
        $migrationPaths[] = dirname(__FILE__) .'/Application/Migrations/1.5/';
        $migrationPaths[] = dirname(__FILE__) .'/Application/Migrations/2.0/';
        $files = array();

        foreach($migrationPaths AS $migrationPath){
            $newFiles = array_diff(scandir($migrationPath), array('.', '..'));
            $newFiles = array_map(function($newFile) use ($migrationPath){ return $migrationPath.$newFile; }, $newFiles);
            $files = array_merge($files, $newFiles);
        }

        self::debugLogDuringSetup('doAllMigration files: '.print_r($files, true));
        foreach($files AS $file){
            $pathInfo = pathinfo($file);
            if($pathInfo['extension'] === 'php'){
                require_once($file);
                $className = 'WADA_Migration_'.$pathInfo['filename'];
                if(class_exists($className, false)){
                    /** @var WADA_Migration_Base $migration */
                    $migration = new $className();
                    self::debugLogDuringSetup('doAllMigration '.$className);
                    if($migration && method_exists($migration, 'isMigrationApplicable') && $migration->isMigrationApplicable()){
                        if(method_exists($migration, 'doMigration')){
                            $migration->doMigration();
                        }
                    }
                }
            }
        }

    }

    protected function runUpdatesAndMigrations(){
        self::infoLogDuringSetup('runUpdatesAndMigrations');
        $updateNeeded = $this->isDatabaseUpdateNeeded();

        if(!$updateNeeded){
            return;
        }

        $dbVersion = WADA_Settings::getDatabaseVersion('1.0.0');
        $currentVersion = $this->getCurrentDatabaseVersion();

        self::infoLogDuringSetup('runUpdatesAndMigrations update needed (from '.$dbVersion.' to '.$currentVersion.')');

        $this->doAllMigrations();

        if(version_compare($currentVersion, $dbVersion, ">")){
            self::infoLogDuringSetup('runUpdatesAndMigrations set DB version to: '.$currentVersion);
            WADA_Settings::setDatabaseVersion($currentVersion); // set DB to latest version
            $dbVersion = WADA_Settings::getDatabaseVersion('1.0.0');
            self::infoLogDuringSetup('runUpdatesAndMigrations DB version now: '.$dbVersion);
        }

    }

    public function scheduleEvents(){
        self::debugLogDuringSetup('scheduleEvents, but first do unschedule');
        $this->unscheduleEvents(); // remove the ones set by earlier install/update
        foreach($this->schedules AS $schedule){
            $res = wp_schedule_event(time() + intval($schedule['nextRunDelta']), $schedule['recurrence'], $schedule['scheduleName']); // (re-)add schedule
            self::debugLogDuringSetup('scheduleEvents scheduled '.$schedule['scheduleName'].' ('.$schedule['recurrence'].'), res: '.$res);
        }
    }

    public function unscheduleEvents(){
        foreach($this->schedules AS $schedule){
            $res = wp_clear_scheduled_hook($schedule['scheduleName']);
            self::debugLogDuringSetup('unscheduleEvents unscheduled '.$schedule['scheduleName'].', res: '.$res);
        }
    }

    public function createUploadsDirectory(){
        $uploadDir = wp_upload_dir();
        $uploadDir = $uploadDir['basedir'];
        $logsDirectory = $uploadDir.'/wp-admin-audit';

        if (!is_dir($logsDirectory)){
            return mkdir($logsDirectory, 0755, true);
        }
        return true;
    }
}