<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return bool
 */
function tbz_paystack_edd_is_setup() {

	if ( edd_get_option( 'edd_paystack_test_mode' ) ) {

		$secret_key = trim( edd_get_option( 'edd_paystack_test_secret_key' ) );
		$public_key = trim( edd_get_option( 'edd_paystack_test_public_key' ) );

	} else {

		$secret_key = trim( edd_get_option( 'edd_paystack_live_secret_key' ) );
		$public_key = trim( edd_get_option( 'edd_paystack_live_public_key' ) );

	}

	return ! ( empty( $public_key ) || empty( $secret_key ) );
}
