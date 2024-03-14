<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

// exit if WP uninstall constant is not defined
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

function wp_admin_audit_is_table_existing($tblName){
    global $wpdb;
    $tblName = $wpdb->prefix.$tblName;
    $query = 'SHOW TABLES LIKE \''.$tblName.'\'';
    $results = $wpdb->query( $query );
    if( $results !== false ) {
        return true;
    } else {
        return false;
    }
}

function wp_admin_audit_is_db_data_to_be_deleted_on_uninstall(){
    global $wpdb;
    $settingTable = $wpdb->prefix.'wada_settings';
    if(wp_admin_audit_is_table_existing($settingTable)) {
        $settingId = 1; // DELETE_DB_DATA_ON_UNINSTALL
        $query = 'SELECT setting_value FROM ' . $settingTable . ' WHERE id = \'' . $settingId . '\'';
        $settingValue = intval($wpdb->get_var($query));
        if ($settingValue === 1) {
            return true;
        }
        return false;
    }
    return true;
}

if(wp_admin_audit_is_db_data_to_be_deleted_on_uninstall()){
    global $wpdb;
    $tables = array();
    $tables[] = '#__wada_events';
    $tables[] = '#__wada_event_infos';
    $tables[] = '#__wada_event_notifications';
    $tables[] = '#__wada_event_notification_log';
    $tables[] = '#__wada_event_replications';
    $tables[] = '#__wada_extensions';
    $tables[] = '#__wada_logins';
    $tables[] = '#__wada_notifications';
    $tables[] = '#__wada_notification_queue';
    $tables[] = '#__wada_notification_queue_map';
    $tables[] = '#__wada_notification_triggers';
    $tables[] = '#__wada_notification_targets';
    $tables[] = '#__wada_sensors';
    $tables[] = '#__wada_sensor_options';
    $tables[] = '#__wada_settings';
    $tables[] = '#__wada_users';

    foreach($tables AS $table){
        $table = str_replace('#__', $wpdb->prefix, $table);
        $wpdb->query("DROP TABLE IF EXISTS ".$table);
    }
}
