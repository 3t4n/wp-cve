<?php
/*
Plugin Name: iTunes Podcast Review Manager
Plugin URI: https://efficientwp.com/plugins/itunes-podcast-review-manager
Description: Get your iTunes podcast reviews from all countries. Checks iTunes automatically and displays your podcast reviews in a sortable table.
Version: 3.7
Author: Doug Yuen
Author URI: https://reviewranger.com
License: GPLv2
*/

/* EXIT IF FILE IS CALLED DIRECTLY */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*****************************
* GLOBAL VARIABLES
*****************************/

$iprm_current_version = '3.7';


/*****************************
* INCLUDES
*****************************/

require_once( dirname( __FILE__ ) . '/includes/class-iprm-podcast.php' );
require_once( dirname( __FILE__ ) . '/includes/data-processing-functions.php' );
require_once( dirname( __FILE__ ) . '/includes/display-functions.php' );
require_once( dirname( __FILE__ ) . '/includes/main-page.php' );
require_once( dirname( __FILE__ ) . '/includes/premium-page.php' );
require_once( dirname( __FILE__ ) . '/includes/script-functions.php' );
require_once( dirname( __FILE__ ) . '/includes/upgrade-functions.php' );
require_once( dirname( __FILE__ ) . '/includes/utility-functions.php' );

/* CHECK FOR UPGRADE CHANGES */
iprm_update_option( 'iprm_current_version', $iprm_current_version );
iprm_upgrade_check();

add_filter( 'cron_schedules', 'iprm_cron_add_every_four_hours' );
add_action( 'iprm_schedule', 'iprm_automatic_check' );
add_shortcode( 'iprm', 'iprm_display_as_shortcode' );
register_deactivation_hook( __FILE__, 'iprm_deactivate' );

/* SCHEDULE A CRON JOB TO CHECK FOR REVIEWS EVERY 4 HOURS */

if ( ! wp_next_scheduled( 'iprm_schedule' ) ) {
	wp_schedule_event( time(), 'four_hours', 'iprm_schedule' );
}
