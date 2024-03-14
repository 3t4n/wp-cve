<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intel
 */

// If uninstall not called from WordPress, then exit.
//if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
//	exit;
//}

function intel_uninstall() {
	global $wpdb;

	// delete tables
	$tables = array(
		"intel_visitor",
		"intel_visitor_identifier",
		"intel_submission",
		"intel_entity_attr",
		"intel_value_str",
	);
	foreach ($tables as $table) {
		$table_name = $wpdb->prefix . $table;
		$sql = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );
	}

	// delete options
	$table_name = $wpdb->prefix . "options";
	$sql = "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'intel_%'";
	$wpdb->query( $sql );


	update_option('intel_uninstall', 1);
}