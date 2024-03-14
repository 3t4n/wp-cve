<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Handles the skipping of the upgrade process
 *
 */
function wpsbc_action_skip_upgrade_process() {

	// Verify for nonce
	if( empty( $_GET['wpsbc_token'] ) || ! wp_verify_nonce( $_GET['wpsbc_token'], 'wpsbc_skip_upgrade_process' ) )
		return;

	// Add the option that the upgrader has been skipped
	update_option( 'wpsbc_upgrade_8_0_0_skipped', 1 );

	// Redirect to the edit page of the calendar with a success message
	wp_redirect( add_query_arg( array( 'page' => 'wpsbc-calendars' ), admin_url( 'admin.php' ) ) );
	exit;

}
add_action( 'wpsbc_action_skip_upgrade_process', 'wpsbc_action_skip_upgrade_process', 50 );