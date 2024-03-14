<?php
/**
 * BuddyPress Birthdays
 * Ajax Request
 *
 * @package BP_Birthdays/assets/inc
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // end if
/**
 * Ajax Requests
 */
add_action( 'wp_ajax_bb_custom_plugin_frontend_ajax', 'bb_custom_plugin_frontend_ajax' );
add_action( 'wp_ajax_nopriv_bb_custom_plugin_frontend_ajax', 'bb_custom_plugin_frontend_ajax' );

/**
 * Action performed for frontend ajax.
 */
function bb_custom_plugin_frontend_ajax()
{
	// Check for nonce security
	if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'bb_widget_nonce_action')) {
		echo 'Nonce verification failed!';
		wp_die();
	}

	ob_start();
	if (isset($_POST['myInputFieldValue'])) {
		$printName = sanitize_text_field(wp_unslash($_POST['myInputFieldValue']));

		// Your ajax Request & Response.
		echo 'Success, Ajax is Working On Your New Plugin. Your field value was: ' . esc_html($printName);
	} else {
		// Your ajax Request & Response.
		echo 'Error, Ajax is Working On Your New Plugin But Your field was empty! Try Typing in the field!';
	}

	wp_die();
}

