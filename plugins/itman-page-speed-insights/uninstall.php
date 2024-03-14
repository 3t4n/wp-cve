<?php
/*
 * Fired when the plugin is uninstalled.
*/

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }

	global $wpdb;

	$table_name = $wpdb->prefix . "itman_page_speed_insights";
	$wpdb->query( "DROP TABLE IF EXISTS " .  $table_name); //Drop plugin table

	delete_option("itps_db_version");
	delete_option("itps_status");