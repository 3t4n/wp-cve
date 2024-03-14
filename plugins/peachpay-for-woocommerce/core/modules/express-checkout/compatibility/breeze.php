<?php
/**
 * PeachPay express checkout compatibility with Breeze.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Adds a exclusion to the Breeze cache for the PeachPay Express Checkout page
 */
function pp_checkout_stop_breeze_cache() {
	$breeze_advanced_settings = get_option( 'breeze_advanced_settings' );
	if ( ! is_array( $breeze_advanced_settings ) ) {
		return;
	}

	$express_checkout_permalink = pp_checkout_permalink();

	$breeze_exclude_urls = isset( $breeze_advanced_settings['breeze-exclude-urls'] ) ? $breeze_advanced_settings['breeze-exclude-urls'] : null;
	if ( ! is_array( $breeze_exclude_urls ) ) {
		$breeze_advanced_settings['breeze-exclude-urls'] = array( $express_checkout_permalink );
	} elseif ( ! in_array( $express_checkout_permalink, $breeze_exclude_urls, true ) ) {
		$breeze_advanced_settings['breeze-exclude-urls'][] = $express_checkout_permalink;
	} else {
		return;
	}

	update_option( 'breeze_advanced_settings', $breeze_advanced_settings );
}

/**
 * Clears the Breeze Cache.
 */
function pp_checkout_clear_breeze_cache() {
	do_action( 'breeze_clear_all_cache' );
}

add_action( 'init', 'pp_checkout_stop_breeze_cache' );
add_action( 'pp_checkout_page_added', 'pp_checkout_clear_rocket_cache' );
add_action( 'pp_checkout_page_removed', 'pp_checkout_clear_rocket_cache' );
