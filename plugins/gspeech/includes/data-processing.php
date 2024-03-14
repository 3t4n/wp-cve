<?php

// no direct access!
defined('ABSPATH') or die("No direct access");

global $wpdb;
global $sh_;

require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

/******************************
* data processing
******************************/

$query = "SHOW TABLES LIKE '".$wpdb->prefix."gspeech_data'";
$wpdb->get_results($query);
$num_r = $wpdb->num_rows;

$plugin_version = '2.0.0';

if($num_r > 0) {

	$sql_g = "SELECT * FROM ".$wpdb->prefix."gspeech_data";
	$row_g = $wpdb->get_row($sql_g);
	$plugin_version = $row_g->plugin_version;
}

$plg__ = explode('.', $plugin_version);
$plg_v_1 = $plg__[0];
$plg_v_2 = isset($plg__[1]) ? $plg__[1] : 0;
$plg_v_3 = isset($plg__[2]) ? $plg__[2] : 0;

$sh_ = ($plg_v_1 == 1 || ($plg_v_1 == 3 && $plg_v_2 == 0)) ? 1 : 0;

$wpgs_db_processed = get_option("wpgs_db_processed");

// echo "wpgs_db_processed: " . $wpgs_db_processed;

$current_db_version = 1;
$new_db_version = NEW_DB_VER;
$current_db_version = intval($wpgs_db_processed) == 0 ? $current_db_version : $wpgs_db_processed;

if($current_db_version < $new_db_version) { // install

	include('install/install.sql.php'); // install
}

if(!$wpgs_db_processed)
	add_option("wpgs_db_processed", $new_db_version);
else
	update_option("wpgs_db_processed", $new_db_version);

// update sh_
if($sh_ == 1) {
	$sql = "UPDATE `".$wpdb->prefix."gspeech_data` SET `sh_` = '1'";
	$wpdb->query($sql);
}