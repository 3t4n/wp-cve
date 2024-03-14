<?php
// exit if uninstall is not called
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$keep = get_option( 'vswc-setting-16' );
if ( $keep != 'yes' ) {
	// set global
	global $wpdb;

	// delete options
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'vswc-setting%'" );
}
