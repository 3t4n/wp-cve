<?php
/**
 * PeachPay express checkout compatibility with WP Rocket.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Disable caching for the PeachPay Express checkout page.
 *
 * @param array $urls Array of URL path patterns to exclude from cache.
 */
function pp_checkout_stop_rocket_caching( $urls ) {
	$urls[] = '/express-checkout/';
	return $urls;
}

/**
 * Cleans entire WP Rocket cache.
 *
 * @return void
 */
function pp_checkout_clear_rocket_cache() {
	if ( ! function_exists( 'rocket_clean_domain' ) ) {
		return;
	}

	rocket_clean_domain();
}

add_filter( 'rocket_cache_reject_uri', 'pp_checkout_stop_rocket_caching' );
add_action( 'pp_checkout_page_added', 'pp_checkout_clear_rocket_cache' );
add_action( 'pp_checkout_page_removed', 'pp_checkout_clear_rocket_cache' );
