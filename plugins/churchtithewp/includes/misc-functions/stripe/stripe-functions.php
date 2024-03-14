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
 * Update something about a Stripe Customer
 *
 * @access      public
 * @since       1.0.0.
 * @param       array $stripe_customer The Stripe Customer object.
 * @param       array $fields_to_update The fields to update on this Stripe Customer.
 * @return      array A Stripe Customer Object in an array form
 */
function church_tithe_wp_update_stripe_customer( $stripe_customer, $fields_to_update ) {

	$s = new Church_Tithe_WP_Stripe(
		array(
			'url'    => 'https://api.stripe.com/v1/customers/' . $stripe_customer['id'],
			'fields' => $fields_to_update,
		)
	);

	// Execute the call to Stripe.
	$stripe_customer = $s->call();

	return $stripe_customer;
}

/**
 * Set the stripe customer key in the user meta.
 *
 * @access      public
 * @since       1.0.0.
 * @param       string $email An email address for a WordPress User that already exists.
 * @return      array A Stripe Customer Object in an array form
 */
function church_tithe_wp_set_stripe_customer_id( $email ) {

	$user = get_user_by( 'email', $email );

	if ( ! $user ) {
		return false;
	}

	$meta_key = church_tithe_wp_stripe_get_customer_key();

	// Create Stripe customer and use email to identify them in stripe.
	$s = new Church_Tithe_WP_Stripe(
		array(
			'url'    => 'https://api.stripe.com/v1/customers',
			'fields' => array(
				'email' => $email,
			),
		)
	);

	// Execute the call to Stripe.
	$customer = $s->call();

	$stripe_customer_id = $customer['id'];

	// Store the minimal amount of info we have to for this customer, just their Stripe Customer ID.
	$success = update_user_meta( $user->ID, $meta_key, $stripe_customer_id );

	if ( $success ) {
		return $customer;
	} else {
		return false;
	}
}

/**
 * Get the stripe customer key from the user meta.
 * If one doesn't exist at Stripe, we'll create one and save that.
 *
 * @access      public
 * @since       1.0.0.
 * @param       string $email An email address for a WordPress User that already exists.
 * @return      array A Stripe Customer Object in an array form
 */
function church_tithe_wp_get_stripe_customer( $email ) {

	$user = get_user_by( 'email', $email );

	if ( ! $user ) {
		return false;
	}

	$meta_key = church_tithe_wp_stripe_get_customer_key();

	$stripe_customer_id = get_user_meta( $user->ID, $meta_key, true );

	// If there isn't a stripe customer ID, create one.
	if ( empty( $stripe_customer_id ) ) {

		$stripe_customer = church_tithe_wp_set_stripe_customer_id( $email );

	} else {

		// Make sure the stripe customer id actually exists in Stripe.
		$s = new Church_Tithe_WP_Stripe(
			array(
				'url' => 'https://api.stripe.com/v1/customers/' . $stripe_customer_id,
			)
		);

		// Execute the call to Stripe.
		$customer = $s->call();

		if ( ! isset( $customer['id'] ) || ( isset( $customer['id'] ) && $customer['id'] !== $stripe_customer_id ) ) {
			$stripe_customer = church_tithe_wp_set_stripe_customer_id( $email );
		} else {
			$stripe_customer = $customer;
		}
	}

	return $stripe_customer;
}

/**
 * Get the meta key for storing Stripe customer IDs in
 *
 * @access      public
 * @since       1.0.0.
 * @return      string
 */
function church_tithe_wp_stripe_get_customer_key() {

	$key = '_church_tithe_wp_stripe_customer_id';
	if ( church_tithe_wp_stripe_is_test_mode() ) {
		$key .= '_test';
	}
	return $key;
}

/**
 * Return true if Stripe is in test mode. False if not.
 *
 * @access      public
 * @since       1.0.0.
 * @return      bool
 */
function church_tithe_wp_stripe_is_test_mode() {

	$saved_settings = get_option( 'church_tithe_wp_settings' );

	$test_mode = church_tithe_wp_get_saved_setting( $saved_settings, 'stripe_test_mode' );

	if ( 'true' === $test_mode ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Return true if Stripe is in live mode. False if not.
 *
 * @access      public
 * @since       1.0.0.
 * @return      bool
 */
function church_tithe_wp_stripe_is_live_mode() {

	$saved_settings = get_option( 'church_tithe_wp_settings' );

	$test_mode = church_tithe_wp_get_saved_setting( $saved_settings, 'stripe_test_mode' );

	if ( 'true' === $test_mode ) {
		return false;
	} else {
		return true;
	}
}

/**
 * Get the Stripe API publishable key based on the test/live mode
 *
 * @access      public
 * @since       1.0.0.
 * @return      string
 */
function church_tithe_wp_get_stripe_publishable_key() {

	$settings = get_option( 'church_tithe_wp_settings' );

	if ( ! church_tithe_wp_stripe_is_test_mode() ) {
		$mode = 'live';
	} else {
		$mode = 'test';
	}

	return isset( $settings[ 'stripe_' . $mode . '_public_key' ] ) ? $settings[ 'stripe_' . $mode . '_public_key' ] : false;

}

/**
 * Get the Stripe account ID
 *
 * @access      public
 * @since       1.0.0.
 * @return      string
 */
function church_tithe_wp_get_stripe_account_id( $mode = 'live' ) {

	$settings = get_option( 'church_tithe_wp_settings' );

	return isset( $settings[ 'stripe_' . $mode . '_account_id' ] ) ? $settings[ 'stripe_' . $mode . '_account_id' ] : false;

}

/**
 * Get the Stripe API secret key based on the test/live mode
 *
 * @access      public
 * @since       1.0.0.
 * @param       string $mode If blank, get the secret key based on the current mode. Otherwise, get the "live" or "test" secret key.
 * @return      string
 */
function church_tithe_wp_get_stripe_secret_key( $mode = false ) {

	$settings = get_option( 'church_tithe_wp_settings' );

	if ( empty( $mode ) ) {
		if ( ! church_tithe_wp_stripe_is_test_mode() ) {
			$mode = 'live';
		} else {
			$mode = 'test';
		}
	}

	return isset( $settings[ 'stripe_' . $mode . '_secret_key' ] ) ? $settings[ 'stripe_' . $mode . '_secret_key' ] : false;

}

/**
 * Return true if Stripe test mode is successfully connected/stored. False if not.
 *
 * @access      public
 * @since       1.0.0.
 * @return      bool
 */
function church_tithe_wp_stripe_test_mode_connected() {

	$settings = get_option( 'church_tithe_wp_settings' );

	if (
		isset( $settings['stripe_test_public_key'] ) &&
		! empty( $settings['stripe_test_public_key'] ) &&
		isset( $settings['stripe_test_secret_key'] ) &&
		! empty( $settings['stripe_test_secret_key'] )
	) {
		return true;
	} else {
		return false;
	}

}

/**
 * Return true if Stripe live mode is successfully connected/stored. False if not.
 *
 * @access      public
 * @since       1.0.0.
 * @return      bool
 */
function church_tithe_wp_stripe_live_mode_connected() {

	$settings = get_option( 'church_tithe_wp_settings' );

	if (
		isset( $settings['stripe_live_public_key'] ) &&
		! empty( $settings['stripe_live_public_key'] ) &&
		isset( $settings['stripe_live_secret_key'] ) &&
		! empty( $settings['stripe_live_secret_key'] )
	) {
		return true;
	} else {
		return false;
	}

}

/**
 * Create the Apple Pay verification file in the site root
 *
 * @since  1.0.0
 * @return bool
 */
function church_tithe_wp_create_apple_verification_file() {

	global $wp_filesystem;

	$url = admin_url();
	if ( false === ( $creds = request_filesystem_credentials( $url, '', false, false, null ) ) ) { // phpcs:ignore
		echo 'unable to get credentials';
		return false;
	}

	if ( ! WP_Filesystem( $creds ) ) {
		request_filesystem_credentials( null, '', true, false, null );
		return;
	}

	$home_path       = dirname( $wp_filesystem->wp_content_dir() );
	$well_known_path = $home_path . '/.well-known/';
	$apple_ver_path  = $well_known_path . 'apple-developer-merchantid-domain-association';

	if ( $wp_filesystem->exists( $apple_ver_path ) ) {
		return true;
	}

	// If there's no well known directory...
	if ( ! $wp_filesystem->exists( $well_known_path ) ) {
		$wp_filesystem->mkdir( $well_known_path, FS_CHMOD_DIR );
	}

	// If we were unable to create it...
	if ( ! $wp_filesystem->exists( $well_known_path ) ) {
		return false;
	}

	// Grab a fresh copy of the Apple Pay Verification file from Stripe.
	$apple_certificate_content = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://stripe.com/files/apple-pay/apple-developer-merchantid-domain-association' ) ) );

	$apple_ver_file_created = $wp_filesystem->put_contents(
		$apple_ver_path,
		$apple_certificate_content,
		FS_CHMOD_FILE
	);

	if ( false !== $apple_ver_file_created && ! is_wp_error( $apple_ver_file_created ) ) {
		return true;
	} else {
		return false;
	}

}
add_action( 'admin_init', 'church_tithe_wp_create_apple_verification_file' );

/**
 * Cancel a subscription at Stripe
 *
 * @since  1.0.0
 * @param  object $arrangement An arrangement object.
 * @param  string $reason A sentence describing the reason for cancellation.
 * @return array An array containing success or failure
 */
function church_tithe_wp_cancel_stripe_subscription( $arrangement, $reason ) {

	// If no Stripe subscription ID was found for that arrangement, we don't have anything we can cancel.
	if ( ! $arrangement->gateway_subscription_id ) {
		return array(
			'success'    => false,
			'error_code' => 'no_subscription_id_attached_to_arrangement',
			'details'    => 'No subscription ID was attached to arrangement ' . $arrangement->id,
		);
	}

	// Send a call to Stripe to cancel this subscription.
	$s = new Church_Tithe_WP_Stripe_Delete(
		array(
			'url' => 'https://api.stripe.com/v1/subscriptions/' . $arrangement->gateway_subscription_id,
		)
	);

	// Execute the call to Stripe.
	$subscription = $s->call();

	// If you try and cancel a subscription that was already cancelled, this will be a "resource_missing" error, so it's not neccesarily a problem.
	if ( isset( $subscription['error'] ) ) {

		// If the sub was already deleted, you get a "resource_missing" from Stripe, so we'll count that as a success. Al other errors will be caught here.
		if ( 'resource_missing' !== $subscription['error']['code'] ) {

			// Email the admin to let them know about this error. They probably should know about this one.
			$admin_email = get_bloginfo( 'admin_email' );
			// translators: The url of the website.
			$subject   = sprintf( __( 'A user attempted to cancel their subscription but it failed on %s.', 'church-tithe-wp' ), get_bloginfo( 'url' ) );
			$body      = __( 'Please email support@churchtithewp.com with the following information for assistance.', 'church-tithe-wp' ) . ' ' . wp_json_encode( $subscription['error'] ) . "\n" . __( 'Data in request:', 'church-tithe-wp' ) . "\n" . wp_json_encode( $s->fields );
			$mail_sent = wp_mail( $admin_email, $subject, $body );

			return array(
				'success'      => false,
				'error_code'   => 'unable_to_cancel',
				'type'         => 'Subscription',
				'details'      => 'Unable to cancel subscription',
				'subscription' => $subscription,
			);

			// If "resource_missing" is the error from Stripe, we probably already deleted this, and this is a duplicate request.
			// Therefore, we won't update the reason it was cancelled.
		} else {

			$arrangement->update(
				array(
					'recurring_status' => 'cancelled',
				)
			);

		}
	} else {

		$arrangement->update(
			array(
				'recurring_status' => 'cancelled',
				'status_reason'    => $reason ? sanitize_text_field( $reason ) : 'general',
			)
		);
	}

	return array(
		'success'      => true,
		'subscription' => $subscription,
	);
}

/**
 * Get the the country code of this Stripe Account
 *
 * @since  1.0.0
 * @return string The 2 letter country code attached to this Stripe Account
 */
function church_tithe_wp_stripe_get_account_country_code() {

	// Check if we have fetched this before so it can be cached and prevent a call to Stripe.
	$stripe_account_country_code = get_option( 'church_tithe_wp_stripe_country_code' );

	if ( ! empty( $stripe_account_country_code ) ) {
		return $stripe_account_country_code;
	}

	$force_mode = false;

	// If we are in test mode, but no test key exists, use the live key, as the account country code is no different in live/test mode.
	if ( church_tithe_wp_stripe_is_test_mode() ) {
		$mode            = 'test';
		$test_secret_key = church_tithe_wp_get_stripe_secret_key();
		if ( empty( $test_secret_key ) ) {
			$force_mode = 'live';
			$mode       = 'live';
		}
	} else {
		$mode = 'live';
	}

	// Check if we have a Stripe Account ID or not yet.
	$account_id = church_tithe_wp_get_stripe_account_id( $mode );

	if ( ! $account_id ) {
		// Default to US for now.
		return 'US';
	}

	// Ping stripe to get the account data.
	$s = new Church_Tithe_WP_Stripe_Get(
		array(
			'force_mode' => $force_mode, // We force this to live mode if no test key exists.
			'url'        => 'https://api.stripe.com/v1/accounts/' . $account_id,
		)
	);

	// Execute the call to Stripe.
	$account_data = $s->call();

	if ( isset( $account_data['error'] ) || ! isset( $account_data['country'] ) ) {
		// Default to US for now.
		return 'US';
	}

	// Save it to the database so it is cached.
	update_option( 'church_tithe_wp_stripe_country_code', sanitize_text_field( $account_data['country'] ) );

	return $account_data['country'];

}


/**
 * Get the currencies available to this Stripe Account based on its location
 *
 * @since  1.0.0
 * @return array Array of currencies available to the connected account
 */
function church_tithe_wp_stripe_get_available_currencies() {

	// Check if we have fetched this before so it can be cached and prevent a call to Stripe.
	$cached_currencies = get_option( 'church_tithe_wp_stripe_available_currencies' );

	if ( ! empty( $cached_currencies ) ) {
		return $cached_currencies;
	}

	// Ping stripe to get the account data.
	$country_code = church_tithe_wp_stripe_get_account_country_code();

	$force_mode = false;

	// If we are in test mode, but no test key exists, use the live key, as currencies available are no different in live/test mode.
	if ( church_tithe_wp_stripe_is_test_mode() ) {
		$test_secret_key = church_tithe_wp_get_stripe_secret_key();
		if ( empty( $test_secret_key ) ) {
			$force_mode = 'live';
		}
	}

	// Ping stripe to get the data about that country, including the currencies it can charge.
	$s = new Church_Tithe_WP_Stripe_Get(
		array(
			'force_mode' => $force_mode, // We force this to live mode if no test key exists.
			'url' => 'https://api.stripe.com/v1/country_specs/' . $country_code,
		)
	);

	// Execute the call to Stripe.
	$country_data = $s->call();

	if ( isset( $country_data['error'] ) || ! isset( $country_data['supported_payment_currencies'] ) || ! is_array( $country_data['supported_payment_currencies'] ) ) {
		return array();
	}

	// Sanitize the values.
	$sanitized_stripe_currencies = array();

	// Loop through each currency from Stripe and sanitize it.
	foreach ( $country_data['supported_payment_currencies'] as $currency_code ) {
		$sanitized_stripe_currencies[] = sanitize_text_field( $currency_code );
	}

	update_option( 'church_tithe_wp_stripe_available_currencies', $sanitized_stripe_currencies );

	return $sanitized_stripe_currencies;
}

/**
 * Get the name of the connected Stripe Account.
 *
 * @since  1.0.7
 * @param  string $mode The mode to get the account for. Defaults to live.
 * @param  bool $use_cached_value If set to false, a call to Stripe will take place. If true, it will return a cached value from a monthly transient.
 */
function church_tithe_wp_stripe_account_name( $mode = 'live', $use_cached_value = true ) {

	if ( $use_cached_value ) {
		$cached_account_name = get_transient( 'ctwp_stripe_account_name_' . $mode . '_mode' );

		if ( ! empty( $cached_account_name ) ) {
			return $cached_account_name;
		}
	}

	// Check if we have a Stripe Account ID or not yet.
	$account_id = church_tithe_wp_get_stripe_account_id( $mode );

	if ( ! $account_id ) {
		return false;
	}

	// Ping stripe to get the data about that account.
	$s = new Church_Tithe_WP_Stripe_Get(
		array(
			'force_mode' => $mode,
			'url'        => 'https://api.stripe.com/v1/accounts/' . $account_id,
		)
	);

	// Execute the call to Stripe.
	$account_data = $s->call();

	if ( isset( $account_data['error'] ) ) {
		return false;
	}

	if ( isset( $account_data['business_name'] ) ) {
		$account_name = sanitize_text_field( $account_data['business_name'] );
		set_transient( 'ctwp_stripe_account_name_' . $mode . '_mode', $account_name, MONTH_IN_SECONDS );
		return $account_data['business_name'];
	}

	if ( isset( $account_data['display_name'] ) ) {
		$account_name = sanitize_text_field( $account_data['display_name'] );
		set_transient( 'ctwp_stripe_account_name_' . $mode . '_mode', $account_name, MONTH_IN_SECONDS );
		return $account_data['display_name'];
	}

	if (
		! isset( $account_data['settings'] ) ||
		! isset( $account_data['settings']['dashboard'] ) ||
		! isset( $account_data['settings']['display_name'] )
	) {
		return false;
	}

	$account_name = sanitize_text_field( $account_data['settings']['dashboard']['display_name'] );
	set_transient( 'ctwp_stripe_account_name_' . $mode . '_mode', $account_name, MONTH_IN_SECONDS );

	return $account_name;
}
