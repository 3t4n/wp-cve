<?php

/**
 * @author     Saeed Sattar Beglou <saeed.sb@gmail.com>
 * @author     HamidReza Gharahzadeh <hamidprime@gmail.com>
 * @author     Sepehr Najafi <sepehrn249@gmail.com>
 * @since      1.0.0
 *
 * @package    ssbhesabfa
 */

// If uninstall not called from WordPress, then exit.
if (!defined( 'WP_UNINSTALL_PLUGIN')) {
	exit;
}

include_once(plugin_dir_path(__DIR__) . 'admin/services/HesabfaLogService.php');
require 'includes/class-ssbhesabfa-api.php';

// delete tags in hesabfa
$hesabfaApi = new Ssbhesabfa_Api();
$result = $hesabfaApi->fixClearTags();
if (!$result->Success) {
    HesabfaLogService::log(array("ssbhesabfa - Cannot clear tags. Error Message: " . (string)$result->ErrorMessage . ". Error Code: " . (string)$result->ErrorCode));
}

global $wpdb;
$options = $wpdb->get_results("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '%ssbhesabfa%'");
foreach ($options as $option) {
    delete_option($option->option_name);
}

$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ssbhesabfa");
