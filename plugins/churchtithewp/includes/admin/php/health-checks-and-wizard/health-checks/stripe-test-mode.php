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
 * Do a health check to see if Stripe Test mode is hooked up properly
 *
 * @param    array $health_checks All the health checks.
 * @return   array $health_checks
 */
function church_tithe_wp_heath_check_stripe_test_mode( $health_checks ) {

	$settings = get_option( 'church_tithe_wp_settings' );

	// If Stripe test mode has been not been connected yet.
	if ( ! isset( $settings['stripe_test_public_key'] ) || empty( $settings['stripe_test_public_key'] ) ) {
		$is_healthy = false;
	} else {
		$is_healthy = true;
	}

	$health_checks['stripe_test_mode'] = array(
		'priority'        => CHURCH_TITHE_WP_WIZARD_TEST_MODE ? 200 : 500,
		'is_healthy'      => $is_healthy,
		'is_health_check' => true,
		'is_wizard_step'  => CHURCH_TITHE_WP_WIZARD_TEST_MODE ? true : false, // The constant allows the wizard to onboard you in Stripe Test mode if true.
		'react_component' => 'Church_Tithe_WP_Stripe_Connect_Health_Check',
		'icon'            => CHURCH_TITHE_WP_PLUGIN_URL . '/assets/images/svg/hard-hat.svg',
		'unhealthy'       => array(
			'instruction'        => __( 'If you would like to run test payments in test mode, click here to connect Stripe\'s test mode. Setting up test mode is not required to accept real payments.', 'church-tithe-wp' ),
			'fix_it_button_text' => __( 'Set up Stripe\'s Test Mode (Optional)', 'church-tithe-wp' ),
			'health_check_icon'  => 'dashicons-info',
			'component_data'     => array(
				'server_api_endpoint_set_stripe_connect_success_url' => admin_url() . '?church_tithe_wp_set_ctwp_scsr',
				'church_tithe_wp_set_ctwp_scsr_nonce' => wp_create_nonce( 'church_tithe_wp_set_ctwp_scsr' ),
				'strings'                        => array(
					'title'                      => __( 'Connect your Stripe Account in Test Mode', 'church-tithe-wp' ),
					'description'                => __( 'This makes it possible to run test payments, and it\'s a good thing to have in place.', 'church-tithe-wp' ),
					'stripe_connect_button_text' => __( 'Connect your Stripe Account in Test Mode', 'church-tithe-wp' ),
				),
				'stripe_connect_url'             => church_tithe_wp_get_stripe_connect_button_url( 'test' ),
			),
		),
		'healthy'         => array(
			'instruction'    => __( 'Stripe (Test Mode) is connected and ready to help run tests when needed.', 'church-tithe-wp' ),
			'component_data' => array(
				'strings' => array(
					'title'                        => __( 'Stripe is now connected to your website in Test Mode.', 'church-tithe-wp' ),
					'description'                  => __( 'Now, if you want to test payments in test mode, you can by using a test credit card.', 'church-tithe-wp' ),
					'next_wizard_step_button_text' => __( 'Next step', 'church-tithe-wp' ),
				),
			),
		),
	);

	return $health_checks;

}
add_filter( 'church_tithe_wp_health_checks_and_wizard_vars', 'church_tithe_wp_heath_check_stripe_test_mode' );
