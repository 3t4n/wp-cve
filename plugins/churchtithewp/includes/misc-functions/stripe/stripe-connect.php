<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the URL for the Stripe Connect button
 *
 * @since    1.0.0
 * @param    string $mode Either 'live' or test' for the mode of Stripe we are using.
 * @return   string
 */
function church_tithe_wp_get_stripe_connect_button_url( $mode = 'test' ) {

	$paramaters = array(
		'mp_stripe_connect_account'     => true,
		'mode'                          => $mode,
		'state'                         => str_pad( wp_rand( wp_rand(), PHP_INT_MAX ), 100, wp_rand(), STR_PAD_BOTH ),
		'customer_domain'               => site_url(),
		'apple_pay_domain'              => rtrim( network_site_url(), '/' ), // This allows it to work for WP Multisites.
		'customer_redirect_url_success' => site_url() . '?church_tithe_wp_stripe_connect_confirmation',
		'customer_redirect_url_failure' => site_url() . '?church_tithe_wp_stripe_connect_failure',
	);

	return add_query_arg( $paramaters, 'https://churchtithewp.com/' );

}

/**
 * Disconnect a Stripe connect account
 *
 * @since    1.0.0
 * @param    string $mode Either 'live' or test' for the mode of Stripe we are using.
 * @return   string
 */
function church_tithe_wp_stripe_disconnect( $mode = 'test' ) {

	// Fetch the settings from the database.
	$settings = get_option( 'church_tithe_wp_settings' );

	$paramaters = array(
		'mp_stripe_disconnect_account' => true,
		'mode'                         => $mode,
		'stripe_account_id'            => $settings[ 'stripe_' . $mode . '_account_id' ],
	);

	$disconnect_url = add_query_arg( $paramaters, 'https://churchtithewp.com/' );

	$response = json_decode( wp_remote_retrieve_body( wp_remote_post( $disconnect_url ) ), true );

	// Clear the stored Stripe keys so they are gone.
	// Fetch the settings from the database.
	$settings = get_option( 'church_tithe_wp_settings' );

	// Remove the stripe api keys from the saved settings.
	$settings[ 'stripe_' . $mode . '_public_key' ]                  = '';
	$settings[ 'stripe_' . $mode . '_secret_key' ]                  = '';
	$settings[ 'stripe_webhook_signing_secret_' . $mode . '_mode' ] = '';

	if ( 'live' === $mode ) {
		$settings['stripe_apple_pay_status'] = '';
	}

	// Save the settings.
	update_option( 'church_tithe_wp_settings', $settings );

	// Delete other Stripe variables.
	delete_option( 'church_tithe_wp_stripe_country_code' );
	delete_option( 'church_tithe_wp_stripe_available_currencies' );

	return $response;

}

/**
 * Successful response after connection
 *
 * @since    1.0.0
 * @return   mixed
 */
function church_tithe_wp_stripe_connect_confirmation() {

	// Nonce checking is not used as the $state variable is relied on for that intention and security check.
	if ( ! isset( $_GET['church_tithe_wp_stripe_connect_confirmation'] ) || ! isset( $_GET['state'] ) || ! isset( $_GET['mode'] ) ) {
		return false;
	}

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( headers_sent() ) {
		return;
	}

	$state        = sanitize_text_field( wp_unslash( $_GET['state'] ) ); // Nonce is replaces by $state checking.
	$mode         = sanitize_text_field( wp_unslash( $_GET['mode'] ) ); // Nonce is replaces by $state checking.
	$is_live_mode = 'live' === $mode ? true : false;

	$mp_connect_credentials_url = add_query_arg(
		array(
			'mp_connect_client_request' => true,
			'live_mode'                 => $is_live_mode,
			'state'                     => sanitize_text_field( wp_unslash( $_GET['state'] ) ), // Nonce is replaces by $state checking.
		),
		'https://churchtithewp.com/'
	);

	// Call home to the parent site which verifies the state.
	$response = wp_remote_post( 'https://churchtithewp.com/?mp_connect_client_request&state=' . $state );

	// If the state was not valid, or there was no response from the parent site.
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		// translators: This is a link to the Church Tithe WP settings page in wp-admin.
		$message = '<p>' . sprintf( __( 'There was an error getting your Stripe credentials. Please <a href="%s">try again</a>. If you continue to have this problem, please contact Church Tithe WP support.', 'church-tithe-wp' ), esc_url( admin_url( 'admin.php?page=church-tithe-wp' ) ) ) . '</p>';
		wp_die( esc_html( $message ) );
	}

	// Get the variables in the body.
	$data = json_decode( $response['body'], true );

	// Fetch the settings from the database.
	$settings = get_option( 'church_tithe_wp_settings' );

	// Add the stripe api keys to the saved settings.
	$settings[ 'stripe_' . $mode . '_public_key' ] = sanitize_text_field( wp_unslash( $data['stripe_publishable_key'] ) );
	$settings[ 'stripe_' . $mode . '_secret_key' ] = sanitize_text_field( wp_unslash( $data['access_token'] ) );
	$settings[ 'stripe_' . $mode . '_account_id' ] = sanitize_text_field( wp_unslash( $data['stripe_user_id'] ) );
	$settings['stripe_apple_pay_status']           = sanitize_text_field( wp_unslash( $data['apple_pay_status'] ) );

	// Save the settings.
	update_option( 'church_tithe_wp_settings', $settings );

	// Set up other Stripe variables.
	church_tithe_wp_stripe_get_account_country_code();
	church_tithe_wp_stripe_get_available_currencies();

	$redirect_to = church_tithe_wp_get_current_stripe_connect_success_url();

	wp_safe_redirect( esc_url_raw( $redirect_to ) );
	die();
}
add_action( 'init', 'church_tithe_wp_stripe_connect_confirmation' );

/**
 * Failed response from connection attempt
 *
 * @since    1.0.0
 * @return   void
 */
function church_tithe_wp_stripe_connect_failure() {
	if ( ! isset( $_GET['church_tithe_wp_stripe_connect_failure'] ) ) {
		return;
	}

	$redirect_to = church_tithe_wp_get_current_stripe_connect_success_url();
	wp_safe_redirect( esc_url_raw( $redirect_to ) );
	die();

}
add_action( 'init', 'church_tithe_wp_stripe_connect_failure' );

/**
 * Get the URL we want to redirect-to upon success (or failure)
 *
 * @since    1.0.0
 * @return   string The URL where the user should be redirected upon Stripe connect success or failure.
 */
function church_tithe_wp_get_current_stripe_connect_success_url() {

	$saved_stripe_connect_success_url = get_option( 'ctwp_scsr' );

	// Reset the redirect now that it's been used.
	delete_option( 'ctwp_scsr' );

	if ( $saved_stripe_connect_success_url ) {
		return esc_url_raw( $saved_stripe_connect_success_url );
	}

	return esc_url_raw( admin_url( 'admin.php?page=church-tithe-wp&mpwpadmin1=stripe_settings' ) );
}
