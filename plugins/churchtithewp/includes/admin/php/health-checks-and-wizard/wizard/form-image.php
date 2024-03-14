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
 * Create a wizard step to help the user set the image to use for the tithe form
 *
 * @since    1.0.0
 * @param    array $wizard_steps All the health checks and wizard steps.
 * @return   array $wizard_steps
 */
function church_tithe_wp_form_image_wizard_step( $wizard_steps ) {

	$saved_settings = get_option( 'church_tithe_wp_settings' );

	$wizard_steps['tithe_form_image'] = array(
		'priority'        => 500,
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
					'title'       => __( 'Select an image to use with your tithe form', 'church-tithe-wp' ),
					'description' => __( 'If you would like to feature an image at the top of your tithe form, upload/select one here.', 'church-tithe-wp' ),
				),
				'input_field'                         => array(
					'react_component'                     => 'MP_WP_Admin_File_Upload_Field',
					'saved_value'                         => church_tithe_wp_get_saved_setting( $saved_settings, 'tithe_form_image' ),
					'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
					'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
					'server_api_endpoint_url'             => admin_url() . '?church_tithe_wp_save_setting',
					'nonce'                               => wp_create_nonce( 'tithe_form_image' ),
					'upload_file_text'                    => __( 'Upload File', 'church-tithe-wp' ),
					'remove_file_text'                    => __( 'Remove File', 'church-tithe-wp' ),
					'instruction_codes'                   => array(
						'empty_initial'     => array(
							'instruction_type'    => 'normal',
							'instruction_message' => __( 'Upload the image you\'d like to use at the top of the tithe form.', 'church-tithe-wp' ),
						),
						'empty_not_initial' => array(
							'instruction_type'    => 'normal',
							'instruction_message' => __( 'Upload the image you\'d like to use at the top of the tithe form.', 'church-tithe-wp' ),
						),
						'error'             => array(
							'instruction_type'    => 'error',
							'instruction_message' => __( 'Upload the image you\'d like to use at the top of the tithe form.', 'church-tithe-wp' ),
						),
						'not_an_image'      => array(
							'instruction_type'    => 'error',
							'instruction_message' => __( 'Whoops! That wasn\'t an image file. Try a jpg or a png file.', 'church-tithe-wp' ),
						),
						'success'           => array(
							'instruction_type'    => 'success',
							'instruction_message' => __( 'Upload the image you\'d like to use at the top of the tithe form.', 'church-tithe-wp' ),
						),
					),
				),
				'success_svg'                         => church_tithe_wp_get_svg_icon( '#f9b429' ),
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
add_filter( 'church_tithe_wp_health_checks_and_wizard_vars', 'church_tithe_wp_form_image_wizard_step' );
