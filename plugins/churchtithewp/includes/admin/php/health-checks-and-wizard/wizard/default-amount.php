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
 * Create a wizard step to help the user set the default amount for their site.
 *
 * @since    1.0.0
 * @param    array $wizard_steps All the health checks and wizard steps.
 * @return   array $wizard_steps
 */
function church_tithe_wp_default_amount_wizard_step( $wizard_steps ) {

	$saved_settings = get_option( 'church_tithe_wp_settings' );

	$wizard_steps['default_amount'] = array(
		'priority'        => 800,
		'is_healthy'      => false,
		'is_health_check' => false,
		'is_wizard_step'  => true,
		'react_component' => 'Church_Tithe_WP_Setting_Wizard',
		'icon'            => CHURCH_TITHE_WP_PLUGIN_URL . '/assets/images/svg/churchtithewp-logo.svg',
		'unhealthy'       => array(
			'component_data' => array(
				'server_api_endpoint_complete_wizard' => admin_url() . '?church_tithe_wp_complete_wizard',
				'complete_wizard_nonce'               => wp_create_nonce( 'church_tithe_wp_complete_wizard' ),
				'strings'                             => array(
					'title'       => __( 'Set a default tithe amount', 'church-tithe-wp' ),
					'description' => __( 'Set the amount that users will see pre-filled by default. Enter using the smallest unit possible for your currency, like cents. (EG: For $1, enter 100).', 'church-tithe-wp' ),
				),
				'input_field'                         => array(
					'react_component'                     => 'MP_WP_Admin_Input_Field',
					'type'                                => 'number',
					'min'                                 => '0',
					'default_value'                       => 500,
					'saved_value'                         => church_tithe_wp_get_saved_setting( $saved_settings, 'default_amount' ),
					'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
					'server_validation_callback_function' => 'church_tithe_wp_validate_integer_input',
					'server_api_endpoint_url'             => admin_url() . '?church_tithe_wp_save_setting',
					'nonce'                               => wp_create_nonce( 'default_amount' ),
					'instruction_codes'                   => array(
						'empty_initial'     => array(
							'instruction_type'    => 'normal',
							'instruction_message' => __( 'Enter the default tithe amount in the smallest unit possible, like cents. (EG: For $5, enter 500)', 'church-tithe-wp' ),
						),
						'empty_not_initial' => array(
							'instruction_type'    => 'normal',
							'instruction_message' => __( 'Enter the default tithe amount in the smallest unit possible, like cents. (EG: For $5, enter 500)', 'church-tithe-wp' ),
						),
						'error'             => array(
							'instruction_type'    => 'error',
							'instruction_message' => __( 'Enter the default tithe amount in the smallest unit possible, like cents. (EG: For $5, enter 500)', 'church-tithe-wp' ),
						),
						'not_an_integer'    => array(
							'instruction_type'    => 'error',
							'instruction_message' => __( 'Whoops! That isn\'t a valid entry. Enter the default tithe amount in cents. (EG: For $5, enter 500)', 'church-tithe-wp' ),
						),
						'success'           => array(
							'instruction_type'    => 'success',
							'instruction_message' => __( 'Enter the default tithe amount in the smallest unit possible, like cents. (EG: For $5, enter 500)', 'church-tithe-wp' ),
						),
					),
				),
			),
		),
		'healthy'         => array(
			'component_data' => array(
				'strings' => array(
					'title'                        => __( 'You have successfully chosen a default currency.', 'church-tithe-wp' ),
					'description'                  => __( 'This is the currency that your users will see by default.', 'church-tithe-wp' ),
					'next_wizard_step_button_text' => __( 'Next step', 'church-tithe-wp' ),
				),
			),
		),
	);

	return $wizard_steps;

}
add_filter( 'church_tithe_wp_health_checks_and_wizard_vars', 'church_tithe_wp_default_amount_wizard_step' );
