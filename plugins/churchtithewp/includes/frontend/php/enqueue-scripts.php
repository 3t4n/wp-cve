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
 * Enqueue the required scripts for the frontend.
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function church_tithe_wp_enqueue_scripts() {

	// Use minified libraries if SCRIPT_DEBUG is turned off.
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.development' : '.production.min';

	$required_js_files = array();

	// If this is a version of WordPress that does not contain React.
	if ( 5 > get_bloginfo( 'version' ) ) {
		wp_enqueue_script( 'react', CHURCH_TITHE_WP_PLUGIN_URL . 'assets/libraries/react/react.min.js', $required_js_files, CHURCH_TITHE_WP_VERSION, false );
		$required_js_files[] = 'react';
		wp_enqueue_script( 'react-dom', CHURCH_TITHE_WP_PLUGIN_URL . 'assets/libraries/react/react-dom.min.js', $required_js_files, CHURCH_TITHE_WP_VERSION, false );
		$required_js_files[] = 'react';
	} else {
		$required_js_files = array(
			'react',
			'react-dom',
		);
	}

	church_tithe_wp_localize_editing_strings();

	// Load Stripe.js (version purposefully left out because it is not loaded locally, but from Stripe, as Stripe requests).
	wp_enqueue_script( 'stripe_js', 'https://js.stripe.com/v3/', $required_js_files, null, false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	$required_js_files[] = 'stripe_js';

	// Load the Church Tithe WP js for the frontend.
	wp_enqueue_script( 'church_tithe_wp_js', CHURCH_TITHE_WP_PLUGIN_URL . 'includes/frontend/js/build/church-tithe-wp-frontend.js', $required_js_files, CHURCH_TITHE_WP_VERSION, true );
	$required_js_files[] = 'church_tithe_wp_js';

	wp_localize_script(
		'church_tithe_wp_js',
		'church_tithe_wp_js_vars',
		array(
			'ajaxurl'          => trailingslashit( get_bloginfo( 'wpurl' ) ),
			'ajax_nonce_value' => wp_create_nonce( 'church-tithe-wp-nonce-action-name' ),

			// Add the frontend nonces for Church Tithe WP.
			'frontend_nonces'  => church_tithe_wp_refresh_and_get_frontend_nonces(),

			// Note that the variables required for each Tithe Form are output in the shortcode or gutenberg output.
		)
	);

	// Load the default Stripe Elements skin.
	if ( SCRIPT_DEBUG ) {
		wp_enqueue_style( 'church_tithe_wp_default_skin', CHURCH_TITHE_WP_PLUGIN_URL . 'includes/frontend/css/src/church-tithe-wp.css', false, CHURCH_TITHE_WP_VERSION );
	} else {
		wp_enqueue_style( 'church_tithe_wp_default_skin', CHURCH_TITHE_WP_PLUGIN_URL . 'includes/frontend/css/build/church-tithe-wp.css', false, CHURCH_TITHE_WP_VERSION );
	}

	// Enqueue the CSS flags.
	wp_enqueue_style( 'church_tithe_wp_flags', CHURCH_TITHE_WP_PLUGIN_URL . 'assets/images/flags/flags.min.css', false, CHURCH_TITHE_WP_VERSION );
}
add_action( 'wp_enqueue_scripts', 'church_tithe_wp_enqueue_scripts' );
