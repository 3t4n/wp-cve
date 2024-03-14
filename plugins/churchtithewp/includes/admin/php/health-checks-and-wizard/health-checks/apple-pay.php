<?php
/**
 * Church Tithe WP
 *
 * @package     Church Tithe WP
 * @subpackage  Classes/Church Tithe WP
 * @copyright   Copyright (c) 2019, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Do a health check to see if Apple Pay is hooked up properly
 *
 * @param    array $health_checks All health checks.
 * @return   array $health_checks
 */
function church_tithe_wp_heath_check_apple_pay( $health_checks ) {

	$settings = get_option( 'church_tithe_wp_settings' );

	// If Apple Pay vars don't exist.
	$apple_pay_connected_domain_status = isset( $settings['stripe_apple_pay_status'] ) ? $settings['stripe_apple_pay_status'] : false;

	// Default unhealthy array.
	$unhealthy_array = array(
		'instruction'        => __( 'Apple Pay is not hooked up! Reconnect with Stripe to fix it.', 'church-tithe-wp' ),
		'fix_it_button_text' => __( 'Let\'s fix it!', 'church-tithe-wp' ),
		'component_data'     => array(
			'server_api_endpoint_set_stripe_connect_success_url' => admin_url() . '?church_tithe_wp_set_ctwp_scsr',
			'church_tithe_wp_set_ctwp_scsr_nonce' => wp_create_nonce( 'church_tithe_wp_set_ctwp_scsr' ),
			'strings'                        => array(
				'title'                      => __( 'Re-connect your Stripe Account to fix Apple Pay', 'church-tithe-wp' ),
				'description'                => __( 'Click the button below to re-connect your Stripe account and fix Apple Pay.', 'church-tithe-wp' ),
				'stripe_connect_button_text' => __( 'Connect your Stripe Account in Live Mode', 'church-tithe-wp' ),
			),
			'stripe_connect_url'             => church_tithe_wp_get_stripe_connect_button_url( 'live' ),
		),
	);

	if ( 'connected' === $apple_pay_connected_domain_status ) {
		$is_healthy = true;
	} else {
		$is_healthy = false;

		// Modify the unhealthy array.
		$unhealthy_array = array(
			'instruction'        => __( 'Apple Pay is not hooked up! Reconnect with Stripe to fix it.', 'church-tithe-wp' ),
			'fix_it_button_text' => __( 'Let\'s fix it!', 'church-tithe-wp' ),
			'component_data'     => array(
				'server_api_endpoint_set_stripe_connect_success_url' => admin_url() . '?church_tithe_wp_set_ctwp_scsr',
				'church_tithe_wp_set_ctwp_scsr_nonce' => wp_create_nonce( 'church_tithe_wp_set_ctwp_scsr' ),
				'strings'                        => array(
					'title'                      => __( 'Re-connect your Stripe Account to fix Apple Pay', 'church-tithe-wp' ),
					'description'                => $apple_pay_connected_domain_status,
					'stripe_connect_button_text' => __( 'Connect your Stripe Account in Live Mode', 'church-tithe-wp' ),
				),
				'stripe_connect_url'             => church_tithe_wp_get_stripe_connect_button_url( 'live' ),
			),
		);

	}

	// Make sure that live mode is enabled, otherwise Apple Pay doesn't work, for some reason.
	if ( $is_healthy ) {
		if ( ! isset( $settings['stripe_live_public_key'] ) || empty( $settings['stripe_live_public_key'] ) ) {
			$is_healthy = false;
		} else {
			$is_healthy = true;
		}
	}

	// Make sure that live mode is enabled, otherwise Apple Pay doesn't work.
	if ( $is_healthy ) {
		if ( ! isset( $settings['stripe_live_public_key'] ) || empty( $settings['stripe_live_public_key'] ) ) {
			$is_healthy = false;
		} else {
			$is_healthy = true;
		}
	}

	// Make sure the Apple Verification File exists.
	if ( $is_healthy ) {
		$apple_verification_file_exists = church_tithe_wp_create_apple_verification_file();

		if ( ! $apple_verification_file_exists ) {
			$is_healthy = false;

			$unhealthy_array = array(
				'instruction'        => __( 'Apple Pay is not hooked up! The Apple Verification file could not be created on your server. Contact your webhost and ask them to create a .well-known directory at your site\'s root directory, and place this file within it. This file verifies your domain with Apple for Apple Pay.', 'church-tithe-wp' ),
				'fix_it_button_text' => __( 'Let\'s fix it!', 'church-tithe-wp' ),
				'component_data'     => array(
					'server_api_endpoint_set_stripe_connect_success_url' => false,
					'church_tithe_wp_set_ctwp_scsr_nonce' => false,
					'strings'                        => array(
						'title'                      => __( 'There\'s an issue with your server...', 'church-tithe-wp' ),
						'description'                => __( 'The Apple Verification file could not be created. Contact your webhost and ask them to create a .well-known directory at your site\'s root directory and make sure it is writable by the WordPress user group. This file verifies your domain with Apple for Apple Pay.', 'church-tithe-wp' ),
						'stripe_connect_button_text' => __( 'Download Apple Verification File (then send to you your webhost)', 'church-tithe-wp' ),
					),
				),
			);

		}
	}

	// If this is a live site.
	if ( church_tithe_wp_is_site_localhost() ) {
		$is_healthy      = false;
		$unhealthy_array = array(
			'instruction'        => __( 'It looks like you are on a localhost. Apple Pay will not work on a localhost, but on a live site it will.', 'church-tithe-wp' ),
			'fix_it_button_text' => __( 'Read more', 'church-tithe-wp' ),
			'component_data'     => array(
				'server_api_endpoint_set_stripe_connect_success_url' => admin_url() . '?church_tithe_wp_set_ctwp_scsr',
				'church_tithe_wp_set_ctwp_scsr_nonce' => wp_create_nonce( 'church_tithe_wp_set_ctwp_scsr' ),
				'strings'                        => array(
					'title'       => __( 'Apple Pay will only work on a Live Site', 'church-tithe-wp' ),
					'description' => __( 'Apple requires that your domain is verified for Apple Pay to work. A localhost is not a verifiable website for Apple.', 'church-tithe-wp' ),
				),
				'stripe_connect_url'             => church_tithe_wp_get_stripe_connect_button_url( 'live' ),
			),
		);
	}

	$health_checks['apple_pay'] = array(
		'priority'        => 700,
		'is_healthy'      => $is_healthy,
		'is_health_check' => true,
		'is_wizard_step'  => false,
		'react_component' => 'Church_Tithe_WP_Stripe_Connect_Health_Check',
		'icon'            => CHURCH_TITHE_WP_PLUGIN_URL . '/assets/images/svg/churchtithewp-logo.svg',
		'unhealthy'       => $unhealthy_array,
		'healthy'         => array(
			'instruction'    => __( 'Apple Pay is all hooked up and ready to go. Beautiful!', 'church-tithe-wp' ),
			'component_data' => array(
				'strings' => array(
					'title'       => __( 'Apple Pay connected!', 'church-tithe-wp' ),
					'description' => __( 'Great job! Apple Pay is all connected and ready to be used on your website!', 'church-tithe-wp' ),
				),
			),
		),
	);

	return $health_checks;

}
add_filter( 'church_tithe_wp_health_checks_and_wizard_vars', 'church_tithe_wp_heath_check_apple_pay' );
