<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Migration_EventNotificationTable extends WADA_Migration_Base {
    public $applicableBeforeVersion = '1.2';

    public function isMigrationApplicable(){
        $dbVersion = WADA_Settings::getDatabaseVersion('1.0.0');
        if(version_compare($dbVersion, $this->applicableBeforeVersion, "<")){
            if(WADA_Database::isColExisting($this->wpdb->prefix.'wada_event_notifications', 'log_messages')
            || WADA_Database::isColExisting($this->wpdb->prefix.'wada_event_notifications', 'success')){
                WADA_Log::warning('EventNotificationTable migration is applicable');
                return true;
            }
        }
        WADA_Log::debug('EventNotificationTable migration is NOT applicable');
        return false;
    }

    public function doMigration(){
        WADA_Log::info('EventNotificationTable doMigration');
        $res = array();
        $res[] = '-log_messages: '.WADA_Database::deleteColIfExists($this->wpdb->prefix.'wada_event_notifications', 'log_messages');
        $res[] = 'rename success->send_erors: '.WADA_Database::renameCol($this->wpdb->prefix.'wada_event_notifications', 'success', 'send_errors');
        WADA_Log::info('EventNotificationTable migration results: '.print_r($res, true));
        return true;
    }

}