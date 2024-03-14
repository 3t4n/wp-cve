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
 * Do a health check to see if Stripe Live mode is hooked up properly
 *
 * @param    array $health_checks All the health checks.
 * @return   array $health_checks
 */
function church_tithe_wp_heath_check_stripe_live_mode( $health_checks ) {

	$settings = get_option( 'church_tithe_wp_settings' );

	// If Stripe live mode has been not been connected yet.
	if ( ! isset( $settings['stripe_live_public_key'] ) || empty( $settings['stripe_live_public_key'] ) ) {
		$is_healthy = false;
	} else {
		$is_healthy = true;
	}

	$health_checks['stripe_live_mode'] = array(
		'priority'        => 200,
		'is_healthy'      => $is_healthy,
		'is_health_check' => true,
		'is_wizard_step'  => CHURCH_TITHE_WP_WIZARD_TEST_MODE ? false : true, // The constant allows the wizard to onboard you in Stripe Test mode if true.
		'react_component' => 'Church_Tithe_WP_Stripe_Connect_Health_Check',
		'icon'            => CHURCH_TITHE_WP_PLUGIN_URL . '/assets/images/svg/wallet.svg',
		'unhealthy'       => array(
			'instruction'        => __( 'Stripe Live Mode is not connected. Connect it to fix this!', 'church-tithe-wp' ),
			'fix_it_button_text' => __( 'Let\'s fix it!', 'church-tithe-wp' ),
			'component_data'     => array(
				'server_api_endpoint_set_stripe_connect_success_url' => admin_url() . '?church_tithe_wp_set_ctwp_scsr',
				'church_tithe_wp_set_ctwp_scsr_nonce' => wp_create_nonce( 'church_tithe_wp_set_ctwp_scsr' ),
				'strings'                        => array(
					'title'                      => __( 'Connect to Stripe', 'church-tithe-wp' ),
					'description'                => __( 'This makes accepting payments on your website possible.', 'church-tithe-wp' ),
					'stripe_connect_button_text' => __( 'Click here to connect with Stripe', 'church-tithe-wp' ),
				),
				'stripe_connect_url'             => church_tithe_wp_get_stripe_connect_button_url( 'live' ),
			),
		),
		'healthy'         => array(
			'instruction'    => __( 'Stripe is connected and ready to accept payments. Excellent!', 'church-tithe-wp' ),
			'component_data' => array(
				'strings' => array(
					'title'                        => __( 'Stripe is successfully connected to your website (Live Mode).', 'church-tithe-wp' ),
					'description'                  => __( 'Great job! Stripe is all connected and ready to accept payments on your website!', 'church-tithe-wp' ),
					'next_wizard_step_button_text' => __( 'Next step', 'church-tithe-wp' ),
				),
			),
		),
	);

	return $health_checks;

}
add_filter( 'church_tithe_wp_health_checks_and_wizard_vars', 'church_tithe_wp_heath_check_stripe_live_mode' );
