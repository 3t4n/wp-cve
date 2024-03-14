<?php
/*
Plugin Name: ITMan Page Speed Insights 
Plugin URI: https://www.itman.sk/page-speed-insights/
Description: Displays and measures page performance according to the Google PageSpeed Insights.
Version: 1.0.6
Author: Matej Podstrelenec
Author URI: https://matejpodstrelenec.sk
Text Domain: itman-page-speed-insights
Domain Path: /languages
License: GPL2

Page Speed Insights is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version

Page Speed Insights is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define ('ITPS_PLUGIN_PATH', plugin_dir_path( __FILE__));

include (ITPS_PLUGIN_PATH . 'includes/functions.php');
include (ITPS_PLUGIN_PATH . 'includes/googlePageSpeedAPI.php');

//Globals
$itps_db_version = "1.0";

//Options
add_option('itps_status', 2); 

/*
When plugin is activated
*/

function itps_activate() {
	itps_db_install();
	update_option('itps_status', 1); // 1: Database is installed; 2: Widget is ready to be used 

	//Register CRON event
	if (! wp_next_scheduled ( 'itps_fetchPageSpeedData' )) {
		wp_schedule_event(time(), 'daily', 'itps_fetchPageSpeedData');
	}
}
register_activation_hook( __FILE__, 'itps_activate' );

add_action('itps_fetchPageSpeedData', 'itps_fetchPageSpeedData'); //Register action
add_action('plugins_loaded', 'itps_update_db_check' ); //Check if DB table update is required

/*
When plugin is deactivated
*/

function itps_deactivate() {
	wp_clear_scheduled_hook('itps_fetchPageSpeedData'); //To clear CRON event
}
register_deactivation_hook( __FILE__, 'itps_deactivate' );

/*
Load text domain (translation files)
*/

function itps_load_plugin_textdomain() {
	$domain = 'itman-page-speed-insights';
	load_plugin_textdomain(
		$domain, false, basename(dirname(__FILE__)) . '/languages/'
	);
}
add_action('init', 'itps_load_plugin_textdomain');

include (ITPS_PLUGIN_PATH . 'admin/admin.php');
?>