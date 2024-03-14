<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Migration_NotificationTable extends WADA_Migration_Base {
    public $applicableBeforeVersion = '1.2';

    public function isMigrationApplicable(){
        $dbVersion = WADA_Settings::getDatabaseVersion('1.0.0');
        if(version_compare($dbVersion, $this->applicableBeforeVersion, "<")){
            if(WADA_Database::isColExisting($this->wpdb->prefix.'wada_notifications', 'observed_sensor_id')){
                WADA_Log::warning('NotificationTable migration is applicable');
                return true;
            }
        }
        WADA_Log::debug('NotificationTable migration is NOT applicable');
        return false;
    }

    public function doMigration(){
        WADA_Log::info('NotificationTable doMigration');
        $res = array();
        $res[] = '-notification_type: '.WADA_Database::deleteColIfExists($this->wpdb->prefix.'wada_notifications', 'notification_type');
        $res[] = '-observed_sensor_id: '.WADA_Database::deleteColIfExists($this->wpdb->prefix.'wada_notifications', 'observed_sensor_id');
        $res[] = '-observed_user_id: '.WADA_Database::deleteColIfExists($this->wpdb->prefix.'wada_notifications', 'observed_user_id');
        $res[] = '-target_type: '.WADA_Database::deleteColIfExists($this->wpdb->prefix.'wada_notifications', 'target_type');
        $res[] = '-target_user_id: '.WADA_Database::deleteColIfExists($this->wpdb->prefix.'wada_notifications', 'target_user_id');
        $res[] = '-target_group_id: '.WADA_Database::deleteColIfExists($this->wpdb->prefix.'wada_notifications', 'target_group_id');
        $res[] = '-target_value: '.WADA_Database::deleteColIfExists($this->wpdb->prefix.'wada_notifications', 'target_value');
        $res[] = '+name: '.WADA_Database::addColIfNotExists($this->wpdb->prefix.'wada_notifications', 'name', 'VARCHAR(190) NULL', 'id');
        WADA_Log::info('NotificationTable migration results: '.print_r($res, true));
        return true;
    }

}