<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( is_admin() ) {
	global $wpdb;
	$wp_prefix = $wpdb->prefix;
	// This includes the dbDelta function from WordPress.
	include_once ABSPATH . 'wp-admin/includes/upgrade.php';
	if ( '0.0' == STYLISH_COST_CALCULATOR_VERSION ) {
		//we my do some reset job here, like delete the table
	}
	update_option( 'df_stylish_cost_calculator_premium_version', STYLISH_COST_CALCULATOR_VERSION );
}
