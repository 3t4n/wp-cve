<?php defined('ABSPATH') or die("No script kiddies please!");

function urpro_forceactivate($version){
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->base_prefix . 'urpro';

 if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
	$sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		siteid bigint(20) DEFAULT '0' NOT NULL,
		ur_key varchar(255),
		ur_value longtext,
		time bigint(20) DEFAULT '0' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

		$wpdb->insert($table_name, array('siteid' => '0', 'ur_key' => 'refresh', 'ur_value' => '900'));
		$wpdb->insert($table_name, array('siteid' => '0', 'ur_key' => 'multisite', 'ur_value' => '1'));
			if(get_option('uptime_robot_nh_api') != ""){
		$wpdb->insert($table_name, array('siteid' => '0', 'ur_key' => 'apikey', 'ur_value' => get_option('uptime_robot_nh_api')));
			}

 }

		wp_schedule_event(time(), 'hourly', 'urpro_schedule_clear_cache');
		update_site_option('urpro_version', $version);
		wp_redirect($_SERVER['REQUEST_URI']); exit();

}