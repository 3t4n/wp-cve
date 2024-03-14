<?php

if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * AMP Compatibility - wp action hook
 *
 * @since 5.9.0
 *
 * @return void
 */
add_action('wp', 'siteseo_amp_compatibility_wp', 0);
function siteseo_amp_compatibility_wp() {
	if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
		wp_dequeue_script( 'siteseo-accordion' );

		remove_filter( 'siteseo_google_analytics_html', 'siteseo_google_analytics_js', 10);

		remove_action('wp_enqueue_scripts', 'siteseo_google_analytics_ecommerce_js', 20, 1);

		remove_action('wp_enqueue_scripts', 'siteseo_google_analytics_cookies_js', 20, 1);

		remove_action( 'wp_head', 'siteseo_load_google_analytics_options', 0 );
	}
}

/**
 * AMP Compatibility - wp_head action hook
 *
 * @since 5.9.0
 *
 * @return void
 */
add_action('wp_head', 'siteseo_amp_compatibility_wp_head', 0);
function siteseo_amp_compatibility_wp_head() {
	if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
		wp_dequeue_script( 'siteseo-accordion' );
	}
}