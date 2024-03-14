<?php

use WOOER\Main;
use WOOER\Exchange_Rate_Model;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Custom functions, utils, etc.
 */
function wooer_startup_error() {
    $class = 'notice notice-error';
    $message = 'WooCommerce Exchange Rate plugin error: ' . __('WooCommerce plugin is not activated!', 'woo-exchange-rate');

    printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
}

/**
 * Installation function
 */
function wooer_install() {
    global $wpdb;
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
    
    $table_name = Exchange_Rate_Model::get_instance()->get_table_name();

    $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
	  `id` mediumint(9) AUTO_INCREMENT PRIMARY KEY,
	  `currency_code` varchar(3) NOT NULL,
      `currency_pos` varchar(32) NOT NULL DEFAULT 'left',
	  `currency_exchange_rate` decimal(16,4) NOT NULL
	);";
    $wpdb->query($sql);
    
    Main::save_plugin_db_version();
}

/**
 * Upgrade function
 */
function wooer_upgrade(){
    wooer_dvc();
}

/**
 * Uninstall function
 */
function wooer_uninstall() {
    global $wpdb;
    $table_name = Exchange_Rate_Model::get_instance()->get_table_name();

    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
    
    delete_option('wooer_plugin_version');
}

/**
 * Database Version Control
 */
function wooer_dvc() {
    $current_version = Main::get_plugin_current_version();
    $current_db_version = Main::get_plugin_db_version();
    
    if (!$current_db_version) {
        foreach (Main::$versionMap as $version) {
            wooer_db_change_log($version);
        }
        Main::save_plugin_db_version($current_version);
        return;
    }
    
    if (version_compare($current_db_version, $current_version, '<')) {
        foreach (Main::$versionMap as $version) {
            if (version_compare($current_db_version, $version, '<')) {
                wooer_db_change_log($version);
            }
        }
        Main::save_plugin_db_version($current_version);
    }

    return;
}

/**
 * Database change log
 * @global type $wpdb
 * @param type $version
 */
function wooer_db_change_log($version){
    global $wpdb;
    $table_name = Exchange_Rate_Model::get_instance()->get_table_name();
    switch ($version) {
        case '17.3':
            $sql = "ALTER TABLE " . $table_name . " ADD COLUMN `currency_pos` varchar(32) NOT NULL DEFAULT 'left'";
            $wpdb->query($sql);
            $sql = "ALTER TABLE " . $table_name . " MODIFY `currency_exchange_rate` decimal(16,4) NOT NULL";
            $wpdb->query($sql);
            break;
    }
}
