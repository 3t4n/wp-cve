<?php

/*
* Create table in DB 
*/

function itps_db_install() {
	global $wpdb;
	global $itps_db_version;

	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . "itman_page_speed_insights";

	//strategy: 1 for desktop, 2 for mobile

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		strategy int(2) NOT NULL,
		measure_date  datetime, 
		performance_score int,
		first_contentful_paint decimal(5,2),
		speed_index decimal(5,2),
		interactive decimal(5,2),
		PRIMARY KEY  (id)
	  ) $charset_collate;";
	  
	  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  dbDelta( $sql );    

	  update_option( "itps_db_version", $itps_db_version );
}

/*
* Check if DB update is needed
*/

function itps_update_db_check() {
	global $itps_db_version;
	if ( get_site_option( 'itps_db_version' ) != $itps_db_version ) {
		itps_db_install();
	}
}

/*
* Get Color code based on performance score
*/

function itps_get_color($performance_score) {
	$color = "#c7221f";

	switch($performance_score) {
		case $performance_score >= 90:
			$color = "#178239"; 
			break;
		case $performance_score >= 50 &&  $performance_score < 90:
			$color = "#e67700"; 
			break;  
		default:
			$color = "#c7221f";    
			break;      
	}

	return $color;
}

/*
* Check if plugin is installed in localhost
*/

function itps_is_localhost() {
	if (substr($_SERVER['REMOTE_ADDR'], 0, 4) == '127.' || $_SERVER['REMOTE_ADDR'] == '::1') return 1;
	else return 0;
}