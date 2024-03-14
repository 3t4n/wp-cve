<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Migration_NotificationUtilTables extends WADA_Migration_Base {
    public $applicableBeforeVersion = '1.2';
    protected $eventNotificationLogTableExisting = true;
    protected $notificationQueueMapTableExisting = true;
    protected $notificationQueueTableExisting = true;
    protected $notificationTriggersTableExisting = true;
    protected $notificationTargetsTableExisting = true;
    protected $notificationQueueTableNeedsUpdate = false;

    public function __construct(){
        parent::__construct();
    }

    public function isMigrationApplicable(){
        $dbVersion = WADA_Settings::getDatabaseVersion('1.0.0');
        if(version_compare($dbVersion, $this->applicableBeforeVersion, "<")){
            $this->eventNotificationLogTableExisting = WADA_Database::isTableExisting('wada_event_notification_log');
            $this->notificationQueueMapTableExisting = WADA_Database::isTableExisting('wada_notification_queue_map');
            $this->notificationQueueTableExisting = WADA_Database::isTableExisting('wada_notification_queue');
            $this->notificationTriggersTableExisting = WADA_Database::isTableExisting('wada_notification_triggers');
            $this->notificationTargetsTableExisting = WADA_Database::isTableExisting('wada_notification_targets');
            if($this->notificationQueueTableExisting){
                $removeEventId = WADA_Database::isColExisting($this->wpdb->prefix.'wada_notification_queue', 'event_id');
                $removeNotificationId = WADA_Database::isColExisting($this->wpdb->prefix.'wada_notification_queue', 'notification_id');
                $this->notificationQueueTableNeedsUpdate = ($removeEventId || $removeNotificationId);
            }else{
                $this->notificationQueueTableNeedsUpdate = false;
            }

            if(!$this->eventNotificationLogTableExisting
                || !$this->notificationQueueMapTableExisting
                || !$this->notificationQueueTableExisting
                || !$this->notificationTriggersTableExisting
                || !$this->notificationTargetsTableExisting
                || $this->notificationQueueTableNeedsUpdate){
                WADA_Log::warning('NotificationUtilTables migration is applicable');
                return true;
            }
        }
        WADA_Log::debug('NotificationUtilTables migration is NOT applicable');
        return false;
    }

    public function doMigration(){
        WADA_Log::info('NotificationUtilTables doMigration');
        $res = array();
        if(!$this->eventNotificationLogTableExisting){
            $res[] = 'createEventNotificationLogTable: '.$this->createEventNotificationLogTable();
        }
        if(!$this->notificationQueueMapTableExisting){
            $res[] = 'createNotificationQueueMapTable: '.$this->createNotificationQueueMapTable();
        }
        if(!$this->notificationQueueTableExisting){
            $res[] = 'createNotificationQueueTable: '.$this->createNotificationQueueTable();
        }
        if(!$this->notificationTriggersTableExisting){
            $res[] = 'createNotificationTriggersTable: '.$this->createNotificationTriggersTable();
        }
        if(!$this->notificationTargetsTableExisting){
            $res[] = 'createNotificationTargetsTable: '.$this->createNotificationTargetsTable();
        }
        if($this->notificationQueueTableNeedsUpdate){
            $res[] = 'updateNotificationQueueTable: '.$this->updateNotificationQueueTable();
        }
        WADA_Log::info('NotificationUtilTables migration results: '.print_r($res, true));
        return true;
    }

    protected function createEventNotificationLogTable(){
        $sql = "CREATE TABLE IF NOT EXISTS ".$this->wpdb->prefix."wada_event_notification_log (
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
                ) ".$this->charsetCollate.";";
        return $this->wpdb->query($sql);
    }

    protected function createNotificationQueueMapTable(){
        $sql = "CREATE TABLE IF NOT EXISTS ".$this->wpdb->prefix."wada_notification_queue_map (
                id INT NOT NULL AUTO_INCREMENT,
                event_notification_id INT NOT NULL,
                queue_id INT NOT NULL,
                PRIMARY KEY (id)
                ) ".$this->charsetCollate.";";
        return $this->wpdb->query($sql);
    }

    protected function createNotificationQueueTable(){
        $sql = "CREATE TABLE IF NOT EXISTS ".$this->wpdb->prefix."wada_notification_queue (
                id INT NOT NULL AUTO_INCREMENT,
                channel_type VARCHAR(45) NOT NULL,
                email_address VARCHAR(255) NULL,
                tel_nr VARCHAR(255) NULL,
                attempt_count INT NOT NULL DEFAULT 0,
                lock_id INT NULL,
                is_locked TINYINT(1) NOT NULL DEFAULT 0,
                last_lock TIMESTAMP NULL,
                PRIMARY KEY (id)
                ) ".$this->charsetCollate.";";
        return $this->wpdb->query($sql);
    }

    protected function createNotificationTriggersTable(){
        $sql = "CREATE TABLE IF NOT EXISTS ".$this->wpdb->prefix."wada_notification_triggers (
                id INT NOT NULL AUTO_INCREMENT,
                notification_id INT NOT NULL,
                trigger_type VARCHAR(45) NOT NULL,
                trigger_id INT NULL,
                trigger_str_id VARCHAR(190) NULL,
                PRIMARY KEY (id)
                ) ".$this->charsetCollate.";";
        return $this->wpdb->query($sql);
    }

    protected function createNotificationTargetsTable(){
        $sql = "CREATE TABLE IF NOT EXISTS ".$this->wpdb->prefix."wada_notification_targets (
                            id INT NOT NULL AUTO_INCREMENT,
                            notification_id INT NOT NULL,
                            channel_type VARCHAR(45) NOT NULL,
                            target_type VARCHAR(45) NOT NULL,
                            target_id INT NULL,
                            target_str_id VARCHAR(190) NULL,
                            PRIMARY KEY (id)
                            ) ".$this->charsetCollate.";";
        return $this->wpdb->query($sql);
    }

    protected function updateNotificationQueueTable(){
        $removedEventId = WADA_Database::deleteColIfExists($this->wpdb->prefix.'wada_notification_queue', 'event_id');
        $removedNotificationId = WADA_Database::deleteColIfExists($this->wpdb->prefix.'wada_notification_queue', 'notification_id');
        return ($removedEventId || $removedNotificationId); // yes, any will do
    }

}