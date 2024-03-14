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
 * Do a health check to see if the wizard has been run.
 *
 * @since    1.0.0
 * @param    array $health_checks All the health checks and wizard steps.
 * @return   array $health_checks
 */
function church_tithe_wp_heath_check_wizard_complete( $health_checks ) {

	$health_checks['complete_wizard'] = array(
		'priority'        => 99999999999,
		'is_healthy'      => true,
		'is_health_check' => false,
		'is_wizard_step'  => true,
		'react_component' => 'Church_Tithe_WP_Complete_Wizard',
		'icon'            => CHURCH_TITHE_WP_PLUGIN_URL . '/assets/images/svg/churchtithewp-logo.svg',
		'healthy'         => array(
			'component_data' => array(
				'server_api_endpoint_complete_wizard' => admin_url() . '?church_tithe_wp_complete_wizard',
				'complete_wizard_nonce'               => wp_create_nonce( 'church_tithe_wp_complete_wizard' ),
				'strings'                             => array(
					'title'                       => __( 'You\'re all set!', 'church-tithe-wp' ),
					'description'                 => __( 'You are ready to start accepting single and recurring payments through your WordPress using Church Tithe WP.', 'church-tithe-wp' ),
					'complete_wizard_button_text' => __( 'Complete', 'church-tithe-wp' ),
				),
			),
		),
	);

	return $health_checks;

}
add_filter( 'church_tithe_wp_health_checks_and_wizard_vars', 'church_tithe_wp_heath_check_wizard_complete' );
