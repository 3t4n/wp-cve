<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

delete_option('ip2location_world_clock_type');
delete_option( 'ip2location_world_clock_design' );
delete_option('ip2location_world_clock_time_format');
delete_option( 'ip2location_display_time' );
delete_option( 'ip2location_display_time2' );
delete_option( 'ip2location_world_clock_database' );
delete_option('ip2location_world_clock_token');


wp_cache_flush();

?>