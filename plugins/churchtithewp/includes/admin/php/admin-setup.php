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
 * Enqueue mpwpadmin, the admin component in wp-admin.
 *
 * @since    1.0.0
 * @return   void
 */
function church_tithe_wp_enqueue_mpwpadmin() {

	/**
	 * Require the mpwpadmin class
	 */
	if ( ! class_exists( 'MP_WP_Admin' ) ) {
		require CHURCH_TITHE_WP_PLUGIN_DIR . 'assets/libraries/mpwpadmin/php/class-mp-wp-admin.php';
	}

	$args = array(
		'visual_name'          => __( 'Church Tithe WP', 'church-tithe-wp' ),
		'required_permissions' => 'activate_plugins',
		'settings_and_views'   => 'church_tithe_wp_get_views_and_settings', // Callback function name to get the mpwpadmin array for generating the output.
		'svg_icon'             => church_tithe_wp_get_svg_icon(),
		'priority'             => 55,
		'loading_text'         => __( 'Loading Church Tithe WP controls...', 'church-tithe-wp' ),
		'required_permissions' => 'activate_plugins',
		'version'              => CHURCH_TITHE_WP_VERSION,
		'plugin_url'           => CHURCH_TITHE_WP_PLUGIN_URL,
		'validation_functions' => CHURCH_TITHE_WP_PLUGIN_URL . 'includes/admin/js/build/mpwpadmin-validation-functions.js',
	);

	new MP_WP_Admin( $args );

}
add_action( '_admin_menu', 'church_tithe_wp_enqueue_mpwpadmin' );

/**
 * Enqueue our custom fields for mpwpadmin, like Stripe Connect fields
 *
 * @since    1.0.0
 * @param    array $required_js_files The js files that are required for our enqueue call.
 * @return   void
 */
function church_tithe_wp_mpwpwadmin_custom_scripts( $required_js_files ) {

	wp_enqueue_script( 'church_tithe_wp_mpwpadmin_custom_scripts', CHURCH_TITHE_WP_PLUGIN_URL . 'includes/admin/js/build/church-tithe-wp-mpwpadmin-custom-scripts.js', $required_js_files, CHURCH_TITHE_WP_VERSION, true );

	add_filter( 'mpwpadmin_required_js_files', 'church_tithe_wp_mpwpadmin_custom_scripts_required' );

	// Load the custom admin styles, like the Stripe Connect button css.
	if ( SCRIPT_DEBUG ) {
		wp_enqueue_style( 'church_tithe_wp_stripe_connect_btn_css', CHURCH_TITHE_WP_PLUGIN_URL . 'includes/admin/css/src/admin-styles.css', false, CHURCH_TITHE_WP_VERSION );
	} else {
		wp_enqueue_style( 'church_tithe_wp_stripe_connect_btn_css', CHURCH_TITHE_WP_PLUGIN_URL . 'includes/admin/css/build/admin-styles.css', false, CHURCH_TITHE_WP_VERSION );
	}

}
add_action( 'mpwpadmin_enqueue_scripts', 'church_tithe_wp_mpwpwadmin_custom_scripts' );

/**
 * Make sure our custom fields for mpwpadmin, like Stripe Connect fields, are required by the mpwpwadmin initializer
 *
 * @since    1.0.0
 * @param    array $required_js_files The js files that are required for our enqueue call.
 * @return   array
 */
function church_tithe_wp_mpwpadmin_custom_scripts_required( $required_js_files ) {

	$required_js_files[] = 'church_tithe_wp_mpwpadmin_custom_scripts';

	return $required_js_files;
}

/**
 * Conditionally get the "how_to" section for mpwpadmin, dependant on the state of the set-up helper (wizard).
 *
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_how_to_welcome_section() {

	$current_wizard_status = get_option( 'church_tithe_wp_wizard_status' );

	if ( 'in_progress' === $current_wizard_status ) {
		return '';
	}

	return array(
		'visual_name'     => __( 'Show a Tithe Form:', 'church-tithe-wp' ),
		'description'     => __( 'Click below for some quick and easy instructions for showing on a page or post:', 'church-tithe-wp' ),
		'react_component' => 'Tithe_Jar_Shortcode_How_To',
		'icon'            => CHURCH_TITHE_WP_PLUGIN_URL . '/assets/images/svg/churchtithewp-logo.svg',
		'component_data'  => array(
			'strings'        => array(
				'title'                  => __( 'Show a "Give Online" Form', 'church-tithe-wp' ),
				'description'            => __( 'To show a tithe form on any page or post, choose your options, then copy the shortcode and paste it on a page/post:', 'church-tithe-wp' ),
				'default_shortcode_text' => '[churchtithewp]',
			),
			'form_mode'      => array(
				'default'       => 'form',
				'title'         => __( 'How would you like this "Give Online" form to display?', 'church-tithe-wp' ),
				'radio_options' => array(
					'form'      => array(
						'selected'     => true,
						'after_output' => __( 'Embed form in place', 'church-tithe-wp' ),
					),
					'button'    => array(
						'selected'     => false,
						'after_output' => __( 'Show a "Give" button', 'church-tithe-wp' ),
					),
					'text_link' => array(
						'selected'     => false,
						'after_output' => __( 'Show a text link', 'church-tithe-wp' ),
					),
				),
			),
			'link_text'      => array(
				'default' => __( 'Give', 'church-tithe-wp' ),
				'title'   => __( 'What should the button/text say?', 'church-tithe-wp' ),
			),
			'open_style'     => array(
				'default'       => 'in_place',
				'title'         => __( 'When clicked, how should the form open?', 'church-tithe-wp' ),
				'radio_options' => array(
					'in_place' => array(
						'selected'     => true,
						'after_output' => __( 'Open in-place', 'church-tithe-wp' ),
					),
					'in_modal' => array(
						'selected'     => false,
						'after_output' => __( 'Open in a pop-up modal', 'church-tithe-wp' ),
					),
				),
			),
			'copy_shortcode' => array(
				'title'       => __( 'Copy the shortcode', 'church-tithe-wp' ),
				'description' => __( 'Put this shortcode on any post or page to show the Tithe Form', 'church-tithe-wp' ),
				'button_text' => __( 'Copy shortcode', 'church-tithe-wp' ),
			),
		),
	);
}

/**
 * Get the settings array ready for output to use by JS
 *
 * @since    1.0.0
 * @return   array
 */
function church_tithe_wp_get_views_and_settings() {

	/**
	 * Require the mpwpadmin class
	 */
	if ( ! class_exists( 'MP_WP_Admin' ) ) {
		require CHURCH_TITHE_WP_PLUGIN_DIR . 'assets/libraries/mpwpadmin/php/class-mp-wp-admin.php';
	}

	$saved_settings            = get_option( 'church_tithe_wp_settings' );
	$all_current_visual_states = mpwpadmin_get_current_visual_state_of_mpwpadmin();
	$lightbox_visual_state     = mpwpadmin_set_lightbox_visual_state_of_mpwpadmin();

	// Set the Onboarding Wizard state. This is intentionally done after the lightbox URL check to override it, since the wizard runs in a lightbox.
	$onboarding_wizard         = church_tithe_wp_set_wizard_for_mpwpadmin( $all_current_visual_states, $lightbox_visual_state );
	$all_current_visual_states = $onboarding_wizard['all_current_visual_states'];
	$lightbox_visual_state     = $onboarding_wizard['lightbox_visual_state'];
	$mpwpadmin_doing_wizard    = $onboarding_wizard['doing_wizard'];

	// Get the health check data.
	$wizard_data       = church_tithe_wp_get_wizard_vars();
	$health_check_data = church_tithe_wp_get_health_check_vars();

	// Set up the views here.
	$church_tithe_wp_admin_views_and_settings = array(
		'general_config' => array(
			'base_url'                              => admin_url( 'admin.php?page=church-tithe-wp' ),
			'app_slug'                              => 'church_tithe_wp_mpwpadmin',
			'server_endpoint_url_refresh_mpwpadmin' => admin_url() . '?church_tithe_wp_refresh_mpwpadmin',
			'mpwpadmin_refresh_nonce'               => wp_create_nonce( 'church_tithe_wp_refresh_mpwpadmin' ),
			'all_current_visual_states'             => $all_current_visual_states,
			'lightbox_visual_state'                 => $lightbox_visual_state,
			'doing_wizard'                          => $mpwpadmin_doing_wizard,
			'default_icon'                          => CHURCH_TITHE_WP_PLUGIN_URL . '/assets/images/svg/churchtithewp-logo.svg',
		),
		'views'          => array(
			'welcome'         => array(
				'visual_name'     => __( 'Welcome', 'church-tithe-wp' ),
				'react_component' => 'MP_WP_Admin_Welcome_View',
				'sections'        => array(
					'how_to'       => church_tithe_wp_how_to_welcome_section(),
					'health_check' => array(
						'visual_name'            => __( 'Health Check for Church Tithe WP', 'church-tithe-wp' ),
						'description'            => __( 'In this section it is super simple to know if your set-up for Church Tithe WP is "healthy", or if it needs some attention. If you run into any issues, double check this section to see if there are any issues showing:', 'church-tithe-wp' ),
						'react_component'        => 'MP_WP_Admin_Health_Check_View',
						'wizard_step_vars'       => $wizard_data['wizard_steps'],
						'health_check_vars'      => $health_check_data['health_checks'],
						'total_unhealthy_checks' => $health_check_data['total_unhealthy_checks'],
					),
				),
			),
			'settings'        => array(
				'visual_name'     => __( 'Tithe Form', 'church-tithe-wp' ),
				'react_component' => 'MP_WP_Admin_Settings_View',
				'settings'        => array(
					'tithe_form_title'             => array(
						'react_component'         => 'MP_WP_Admin_Input_Field',
						'type'                    => 'text',
						'default_value'           => __( 'Give Online', 'church-tithe-wp' ),
						'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'tithe_form_title' ),
						'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
						'nonce'                   => wp_create_nonce( 'tithe_form_title' ),
						'instruction_codes'       => array(
							'empty_initial'     => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'What would you like the title at the top of the tithe form to say?', 'church-tithe-wp' ),
							),
							'empty_not_initial' => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'What would you like the title at the top of the tithe form to say?', 'church-tithe-wp' ),
							),
							'error'             => array(
								'instruction_type'    => 'error',
								'instruction_message' => __( 'What would you like the title at the top of the tithe form to say?', 'church-tithe-wp' ),
							),
							'success'           => array(
								'instruction_type'    => 'success',
								'instruction_message' => __( 'What would you like the title at the top of the tithe form to say?', 'church-tithe-wp' ),
							),
						),
						'help_text'               => array(
							'title' => __( 'Tithe Form Title', 'church-tithe-wp' ),
							'body'  => __( 'This is what appears at the top of the Tithe Form. It is a good idea to help indicate what the tithe form is for here. By default, it says "Give Online". You can set it to anything you would like.', 'church-tithe-wp' ),
						),
					),
					'tithe_form_subtitle'          => array(
						'react_component'         => 'MP_WP_Admin_Input_Field',
						'type'                    => 'text',
						'default_value'           => '',
						'placeholder'             => __( 'Enter an optional subtitle for the tithe form', 'church-tithe-wp' ),
						'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'tithe_form_subtitle' ),
						'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
						'nonce'                   => wp_create_nonce( 'tithe_form_subtitle' ),
						'instruction_codes'       => array(
							'empty_initial'     => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'What would you like the subtitle at the top of the tithe form to say?', 'church-tithe-wp' ),
							),
							'empty_not_initial' => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'What would you like the subtitle at the top of the tithe form to say?', 'church-tithe-wp' ),
							),
							'error'             => array(
								'instruction_type'    => 'error',
								'instruction_message' => __( 'What would you like the subtitle at the top of the tithe form to say?', 'church-tithe-wp' ),
							),
							'success'           => array(
								'instruction_type'    => 'success',
								'instruction_message' => __( 'What would you like the subtitle at the top of the tithe form to say?', 'church-tithe-wp' ),
							),
						),
						'help_text'               => array(
							'title' => __( 'Tithe Form Subtitle', 'church-tithe-wp' ),
							'body'  => __( 'This is what appears just below the title at the top of the Tithe Form. This is a good place to say something about yourself, and give a unique message to your users.', 'church-tithe-wp' ),
						),
					),
					'amount_title'                 => array(
						'react_component'         => 'MP_WP_Admin_Input_Field',
						'type'                    => 'text',
						'default_value'           => __( 'How much would you like to give?', 'church-tithe-wp' ),
						'placeholder'             => __( 'How much would you like to give?', 'church-tithe-wp' ),
						'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'amount_title' ),
						'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
						'nonce'                   => wp_create_nonce( 'amount_title' ),
						'instruction_codes'       => array(
							'empty_initial'     => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'This is the instruction text which appears before the recurring options.', 'church-tithe-wp' ),
							),
							'empty_not_initial' => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'This is the instruction text which appears before the recurring options.', 'church-tithe-wp' ),
							),
							'error'             => array(
								'instruction_type'    => 'error',
								'instruction_message' => __( 'This is the instruction text which appears before the recurring options.', 'church-tithe-wp' ),
							),
							'success'           => array(
								'instruction_type'    => 'success',
								'instruction_message' => __( 'This is the instruction text which appears before the recurring options.', 'church-tithe-wp' ),
							),
						),
						'help_text'               => array(
							'title' => __( 'Amount title', 'church-tithe-wp' ),
							'body'  => __( 'This is the instruction text which appears before the recurring options. You can customize this message, but keep it short so your form stays quick and simple.', 'church-tithe-wp' ),
						),
					),
					'recurring_title'              => array(
						'react_component'         => 'MP_WP_Admin_Input_Field',
						'type'                    => 'text',
						'default_value'           => __( 'How often would you like to give this?', 'church-tithe-wp' ),
						'placeholder'             => __( 'How often would you like to give this?', 'church-tithe-wp' ),
						'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'recurring_title' ),
						'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
						'nonce'                   => wp_create_nonce( 'recurring_title' ),
						'instruction_codes'       => array(
							'empty_initial'     => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'This is the instruction text which appears before the recurring options.', 'church-tithe-wp' ),
							),
							'empty_not_initial' => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'This is the instruction text which appears before the recurring options.', 'church-tithe-wp' ),
							),
							'error'             => array(
								'instruction_type'    => 'error',
								'instruction_message' => __( 'This is the instruction text which appears before the recurring options.', 'church-tithe-wp' ),
							),
							'success'           => array(
								'instruction_type'    => 'success',
								'instruction_message' => __( 'This is the instruction text which appears before the recurring options.', 'church-tithe-wp' ),
							),
						),
						'help_text'               => array(
							'title' => __( 'Amount title', 'church-tithe-wp' ),
							'body'  => __( 'This is the instruction text which appears before the recurring options. You can customize this message, but keep it short so your form stays quick and simple.', 'church-tithe-wp' ),
						),
					),
					'default_currency'             => array(
						'react_component'            => 'MP_WP_Admin_Select_Field',
						'type'                       => 'text',
						'default_value'              => 'USD',
						'placeholder'                => 'USD',
						'saved_value'                => church_tithe_wp_get_saved_setting( $saved_settings, 'default_currency' ),
						'initially_available_values' => church_tithe_wp_get_currencies(),
						'fetch_options_function'     => 'church_tithe_wp_ajax_get_currencies',
						'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_validation_callback_function' => 'church_tithe_wp_validate_currency_input',
						'server_api_endpoint_url'    => admin_url() . '?church_tithe_wp_save_setting',
						'nonce'                      => wp_create_nonce( 'default_currency' ),
						'instruction_codes'          => array(
							'empty_initial'     => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'Enter the 3-letter currency code tithes should use', 'church-tithe-wp' ),
							),
							'empty_not_initial' => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'Enter the 3-letter currency code tithes should use', 'church-tithe-wp' ),
							),
							'error'             => array(
								'instruction_type'    => 'error',
								'instruction_message' => __( 'Enter the 3-letter currency code tithes should use', 'church-tithe-wp' ),
							),
							'invalid_selection' => array(
								'instruction_type'    => 'error',
								'instruction_message' => 'Please enter a valid 3-letter currency.',
							),
							'success'           => array(
								'instruction_type'    => 'success',
								'instruction_message' => __( 'Enter the 3-letter currency code tithes should use', 'church-tithe-wp' ),
							),
						),
						'help_text'                  => array(
							'title' => __( 'Default Currency', 'church-tithe-wp' ),
							'body'  => __( 'Set this to be the currency the tithe form uses by default.', 'church-tithe-wp' ),
						),
						'no_matching_values_text'    => __( 'No matching currencies found. Try another search', 'church-tithe-wp' ),
					),
					'default_amount'               => array(
						'react_component'         => 'MP_WP_Admin_Input_Field',
						'type'                    => 'number',
						'min'                     => '0',
						'default_value'           => 500,
						'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'default_amount' ),
						'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_validation_callback_function' => 'church_tithe_wp_validate_integer_input',
						'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
						'nonce'                   => wp_create_nonce( 'default_amount' ),
						'instruction_codes'       => array(
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
						'help_text'               => array(
							'title' => __( 'Default Tithe Amout', 'church-tithe-wp' ),
							'body'  => __( 'The default tithe is what the Tithe Form will show automatically. From there, your users can change the amount to a custom amount they choose. You may wish to set this at 100.', 'church-tithe-wp' ),
						),
					),
					'tithe_form_image'             => array(
						'react_component'         => 'MP_WP_Admin_File_Upload_Field',
						'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'tithe_form_image' ),
						'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
						'nonce'                   => wp_create_nonce( 'tithe_form_image' ),
						'upload_file_text'        => __( 'Upload File', 'church-tithe-wp' ),
						'remove_file_text'        => __( 'Remove File', 'church-tithe-wp' ),
						'instruction_codes'       => array(
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
						'help_text'               => array(
							'title' => __( 'Tithe Form Image', 'church-tithe-wp' ),
							'body'  => __( "Each tithe form has an image at the top. Here, you can upload the image you'd like to use for that. Recommended size: 100px by 100px", 'church-tithe-wp' ),
						),
					),
					'tithe_form_terms'             => array(
						'react_component'         => 'MP_WP_Admin_TextArea_Field',
						'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'tithe_form_terms' ),
						'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
						'nonce'                   => wp_create_nonce( 'tithe_form_terms' ),
						'default_value'           => '',
						'instruction_codes'       => array(
							'empty_initial'     => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'Enter the terms and conditions for the tithe form.', 'church-tithe-wp' ),
							),
							'empty_not_initial' => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'Enter the terms and conditions for the tithe form.', 'church-tithe-wp' ),
							),
							'error'             => array(
								'instruction_type'    => 'error',
								'instruction_message' => __( 'Enter the terms and conditions for the tithe form.', 'church-tithe-wp' ),
							),
							'success'           => array(
								'instruction_type'    => 'success',
								'instruction_message' => __( 'Enter the terms and conditions for the tithe form.', 'church-tithe-wp' ),
							),
						),
						'help_text'               => array(
							'title' => __( 'Tithe Form Terms', 'church-tithe-wp' ),
							'body'  => __( 'To require customers to agree to a set of terms prior to completing their tithe, enter the terms here. To completely hide this, leave this blank. For example, if your terms are already on your website in another place, you may wish to leave this blank.', 'church-tithe-wp' ),
						),
					),
					'tithe_form_completed_message' => array(
						'react_component'         => 'MP_WP_Admin_Input_Field',
						'type'                    => 'text',
						'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'tithe_form_completed_message' ),
						'default_value'           => __( 'Your payment has been successfully processed. Thank you.', 'church-tithe-wp' ),
						'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
						'nonce'                   => wp_create_nonce( 'tithe_form_completed_message' ),
						'instruction_codes'       => array(
							'empty_initial'     => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'Enter the message you\'d like to show after a successful tithe.', 'church-tithe-wp' ),
							),
							'empty_not_initial' => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'Enter the message you\'d like to show after a successful tithe.', 'church-tithe-wp' ),
							),
							'error'             => array(
								'instruction_type'    => 'error',
								'instruction_message' => __( 'Enter the message you\'d like to show after a successful tithe.', 'church-tithe-wp' ),
							),
							'success'           => array(
								'instruction_type'    => 'success',
								'instruction_message' => __( 'Enter the message you\'d like to show after a successful tithe.', 'church-tithe-wp' ),
							),
						),
						'help_text'               => array(
							'title' => __( 'Tithe Form "On completed" message', 'church-tithe-wp' ),
							'body'  => __( 'After leaving a tithe, this message will be displayed before their receipt. You can leave it blank if you would prefer not to use it.', 'church-tithe-wp' ),
						),
					),
					'statement_descriptor'         => array(
						'react_component'         => 'MP_WP_Admin_Input_Field',
						'type'                    => 'text',
						'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'statement_descriptor' ),
						// translators: The name of the website.
						'default_value'           => get_bloginfo( 'name' ),
						'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
						'nonce'                   => wp_create_nonce( 'statement_descriptor' ),
						'instruction_codes'       => array(
							'empty_initial'     => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'Enter the statement descriptor, which is what shows on credit-card statements.', 'church-tithe-wp' ),
							),
							'empty_not_initial' => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'Enter the statement descriptor, which is what shows on credit-card statements.', 'church-tithe-wp' ),
							),
							'error'             => array(
								'instruction_type'    => 'error',
								'instruction_message' => __( 'Enter the statement descriptor, which is what shows on credit-card statements.', 'church-tithe-wp' ),
							),
							'success'           => array(
								'instruction_type'    => 'success',
								'instruction_message' => __( 'Enter the statement descriptor, which is what shows on credit-card statements.', 'church-tithe-wp' ),
							),
						),
						'help_text'               => array(
							'title' => __( 'Statement Descriptor', 'church-tithe-wp' ),
							'body'  => __( 'This is what will appear on the credit card statement of people who make a payment.', 'church-tithe-wp' ),
						),
					),
					'payment_verb'                 => array(
						'react_component'         => 'MP_WP_Admin_Input_Field',
						'type'                    => 'text',
						'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'payment_verb' ),
						// translators: The name of the website.
						'default_value'           => __( 'Pay', 'church-tithe-wp' ),
						'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
						'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
						'nonce'                   => wp_create_nonce( 'payment_verb' ),
						'instruction_codes'       => array(
							'empty_initial'     => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'Enter the word(s) to use on the "Pay" button (where possible/applicable)', 'church-tithe-wp' ),
							),
							'empty_not_initial' => array(
								'instruction_type'    => 'normal',
								'instruction_message' => __( 'Enter the word(s) to use on the "Pay" button (where possible/applicable)', 'church-tithe-wp' ),
							),
							'error'             => array(
								'instruction_type'    => 'error',
								'instruction_message' => __( 'Enter the word(s) to use on the "Pay" button (where possible/applicable)', 'church-tithe-wp' ),
							),
							'success'           => array(
								'instruction_type'    => 'success',
								'instruction_message' => __( 'Enter the word(s) to use on the "Pay" button (where possible/applicable)', 'church-tithe-wp' ),
							),
						),
						'help_text'               => array(
							'title' => __( 'Payment Verb', 'church-tithe-wp' ),
							'body'  => __( 'This is the word(s) that will appear on the "Pay" button while in "Credit Card" mode, and in Apple Pay mode. Note that Google Pay does not allow this to be used at this time, so it will always say "Pay".', 'church-tithe-wp' ),
						),
					),
				),
			),
			'stripe_settings' => array(
				'visual_name'     => __( 'Stripe Settings', 'church-tithe-wp' ),
				'react_component' => 'MP_WP_Admin_Settings_View',
				'settings'        => array(
					'stripe_live_settings' => array(
						'visual_name' => __( 'Stripe Live Mode', 'church-tithe-wp' ),
						'settings'    => array(
							'stripe_connect_live_mode' => array(
								'react_component'         => 'Church_Tithe_WP_Stripe_Connect_Field',
								'stripe_connect_url'      => church_tithe_wp_get_stripe_connect_button_url( 'live' ),
								'stripe_disconnect_url'   => admin_url() . '?church_tithe_wp_stripe_disconnect',
								'stripe_disconnect_nonce_id' => 'stripe_live_mode_disconnect',
								'stripe_disconnect_nonce' => wp_create_nonce( 'stripe_live_mode_disconnect' ),
								'stripe_is_connected'     => church_tithe_wp_stripe_live_mode_connected(),
								'stripe_account_label'    => __( 'Stripe Account:', 'church-tithe-wp' ),
								'stripe_account_name'     => church_tithe_wp_stripe_account_name( 'live' ),
								'mode'                    => 'live',
								'button_strings'          => array(
									'connect_text'    => __( 'Connect your Stripe Account in Live Mode', 'church-tithe-wp' ),
									'connected_text'  => __( 'Connected to Stripe', 'church-tithe-wp' ),
									'disconnect_text' => __( 'Disconnect', 'church-tithe-wp' ),
								),
								'instruction_codes'       => array(
									'connect_stripe'   => array(
										'instruction_type' => 'normal',
										'instruction_message' => __( 'Connect your Stripe Account in Live Mode', 'church-tithe-wp' ),
									),
									'stripe_connected' => array(
										'instruction_type' => 'normal',
										'instruction_message' => __( 'Your Stripe Account is connected in Live Mode', 'church-tithe-wp' ),
									),
									'error'            => array(
										'instruction_type' => 'error',
										'instruction_message' => __( 'Something went wrong. Refresh this page and try again.', 'church-tithe-wp' ),
									),
								),
								'help_text'               => array(
									'title' => __( 'Connect to Stripe (Live Mode)', 'church-tithe-wp' ),
									'body'  => __( 'Clicking this will send you to Stripe to authenticate Church Tithe WP, allowing it to create transactions on your account.', 'church-tithe-wp' ),
								),
							),
							'stripe_webhook_signing_secret_live_mode' => array(
								'react_component'         => 'MP_WP_Admin_Input_Field',
								'type'                    => 'text',
								'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'stripe_webhook_signing_secret_live_mode' ),
								'default_value'           => '',
								'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
								'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
								'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
								'nonce'                   => wp_create_nonce( 'stripe_webhook_signing_secret_live_mode' ),
								'instruction_codes'       => array(
									'empty_initial'     => array(
										'instruction_type' => 'normal',
										'instruction_message' => __( 'Create a webhook in your Stripe account, then put the signing secret here.', 'church-tithe-wp' ),
									),
									'empty_not_initial' => array(
										'instruction_type' => 'normal',
										'instruction_message' => __( 'Create a webhook in your Stripe account, then put the signing secret here.', 'church-tithe-wp' ),
									),
									'error'             => array(
										'instruction_type' => 'error',
										'instruction_message' => __( 'Create a webhook in your Stripe account, then put the signing secret here.', 'church-tithe-wp' ),
									),
									'success'           => array(
										'instruction_type' => 'success',
										'instruction_message' => __( 'Create a webhook in your Stripe account, then put the signing secret here.', 'church-tithe-wp' ),
									),
								),
								'help_text'               => array(
									'react_component' => 'Church_Tithe_WP_Health_Check_As_Help_Lightbox',
									'component_data'  => array(
										'react_component' => 'Church_Tithe_WP_Stripe_Webhook_Health_Check',
										'key'             => 'stripe_webhook_signing_secret_live_mode',
										'server_api_endpoint_set_stripe_connect_success_url' => admin_url() . '?church_tithe_wp_set_ctwp_scsr',
										'church_tithe_wp_set_ctwp_scsr_nonce' => wp_create_nonce( 'church_tithe_wp_set_ctwp_scsr' ),
										'strings'         => array(
											'title'       => __( 'Let\'s get your Stripe Webhooks set up (Live Mode).', 'church-tithe-wp' ),
											'description' => __( 'Stripe webhooks are the way Stripe "talks" to the Church Tithe WP code on your website. There are 3 steps to take here.', 'church-tithe-wp' ),
											'ready_to_get_started' => __( 'Ready to get started?', 'church-tithe-wp' ),
										),
										'steps'           => array(
											'step1' => array(
												'title' => __( '1. Copy the endpoint URL', 'church-tithe-wp' ),
												'description' => __( 'Copy this url:', 'church-tithe-wp' ),
												'url_to_copy' => get_bloginfo( 'url' ) . '?church_tithe_wp_stripe_webhook',
											),
											'step2' => array(
												'title' => __( '2. Create Stripe Webhook', 'church-tithe-wp' ),
												'description' => array(
													'line_1' => __( 'A) Click the button below to log into your Stripe Account.', 'church-tithe-wp' ),
													'line_2' => __( 'B) Then click "Add Endpoint" and paste the Endpoint URL.', 'church-tithe-wp' ),
													'line_3' => __( 'C) Under "Events to send", click on "Receive all events".', 'church-tithe-wp' ),
													'line_4' => __( 'PLEASE NOTE: this link is not in the dropdown menu, but underneath it.', 'church-tithe-wp' ),
												),
												'stripe_connect_button_text' => __( 'Go to my Stripe Account in a new tab', 'church-tithe-wp' ),
												'stripe_connect_url' => 'https://dashboard.stripe.com/account/webhooks',
											),
											'step3' => array(
												'title' => __( '3. Copy the "Signing secret" and paste here', 'church-tithe-wp' ),
												'description' => __( 'Under the section called "Signing secret", copy the secret text and paste it here', 'church-tithe-wp' ),
												'input_field' => array(
													'id'   => 'stripe_webhook_signing_secret_live_mode',
													'react_component' => 'MP_WP_Admin_Input_Field',
													'type' => 'text',
													'saved_value' => church_tithe_wp_get_saved_setting( $saved_settings, 'stripe_webhook_signing_secret_live_mode' ),
													'default_value' => '',
													'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
													'server_validation_callback_function' => 'church_tithe_wp_validate_live_stripe_webhook',
													'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
													'nonce' => wp_create_nonce( 'stripe_webhook_signing_secret_live_mode' ),
													'instruction_codes' => array(
														'empty_initial'          => array(
															'instruction_type'    => 'normal',
															'instruction_message' => __( 'Paste the signing secret here.', 'church-tithe-wp' ),
														),
														'empty_not_initial'      => array(
															'instruction_type'    => 'normal',
															'instruction_message' => __( 'Paste the signing secret here.', 'church-tithe-wp' ),
														),
														'error'                  => array(
															'instruction_type'    => 'error',
															'instruction_message' => __( 'Paste the signing secret here.', 'church-tithe-wp' ),
														),
														'invalid_length'         => array(
															'instruction_type'    => 'error',
															'instruction_message' => __( 'Double check the length of the key.', 'church-tithe-wp' ),
														),
														'does_not_contain_whsec' => array(
															'instruction_type'    => 'error',
															'instruction_message' => __( 'Make sure they key you copied begins with "whsec_".', 'church-tithe-wp' ),
														),
														'success'                => array(
															'instruction_type'    => 'success',
															'instruction_message' => __( 'Paste the signing secret here.', 'church-tithe-wp' ),
														),
													),
												),
											),
										),
									),
								),
							),
						),
					),
					'stripe_test_settings' => array(
						'visual_name' => __( 'Stripe Test Mode', 'church-tithe-wp' ),
						'settings'    => array(
							'stripe_connect_test_mode' => array(
								'react_component'         => 'Church_Tithe_WP_Stripe_Connect_Field',
								'stripe_connect_url'      => church_tithe_wp_get_stripe_connect_button_url( 'test' ),
								'stripe_disconnect_url'   => admin_url() . '?church_tithe_wp_stripe_disconnect',
								'stripe_disconnect_nonce_id' => 'stripe_test_mode_disconnect',
								'stripe_disconnect_nonce' => wp_create_nonce( 'stripe_test_mode_disconnect' ),
								'stripe_is_connected'     => church_tithe_wp_stripe_test_mode_connected(),
								'stripe_account_label'    => __( 'Stripe Account:', 'church-tithe-wp' ),
								'stripe_account_name'     => church_tithe_wp_stripe_account_name( 'test' ),
								'mode'                    => 'test',
								'button_strings'          => array(
									'connect_text'    => __( 'Connect your Stripe Account in Test Mode', 'church-tithe-wp' ),
									'connected_text'  => __( 'Connected to Stripe', 'church-tithe-wp' ),
									'disconnect_text' => __( 'Disconnect', 'church-tithe-wp' ),
								),
								'instruction_codes'       => array(
									'connect_stripe'   => array(
										'instruction_type' => 'normal',
										'instruction_message' => __( 'Connect your Stripe Account in Test Mode', 'church-tithe-wp' ),
									),
									'stripe_connected' => array(
										'instruction_type' => 'normal',
										'instruction_message' => __( 'Your Stripe Account is connected in Test Mode', 'church-tithe-wp' ),
									),
									'error'            => array(
										'instruction_type' => 'error',
										'instruction_message' => __( 'Something went wrong. Refresh this page and try again.', 'church-tithe-wp' ),
									),
								),
								'help_text'               => array(
									'title' => __( 'Connect to Stripe (Test Mode)', 'church-tithe-wp' ),
									'body'  => __( 'Clicking this will send you to Stripe to authenticate Church Tithe WP in test mode, allowing it to create test transactions on your account.', 'church-tithe-wp' ),
								),
							),
							'stripe_webhook_signing_secret_test_mode' => array(
								'react_component'         => 'MP_WP_Admin_Input_Field',
								'type'                    => 'text',
								'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'stripe_webhook_signing_secret_test_mode' ),
								'default_value'           => '',
								'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
								'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
								'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
								'nonce'                   => wp_create_nonce( 'stripe_webhook_signing_secret_test_mode' ),
								'instruction_codes'       => array(
									'empty_initial'     => array(
										'instruction_type' => 'normal',
										'instruction_message' => __( 'Create a webhook in your Stripe account (in test mode), then put the signing secret here.', 'church-tithe-wp' ),
									),
									'empty_not_initial' => array(
										'instruction_type' => 'normal',
										'instruction_message' => __( 'Create a webhook in your Stripe account (in test mode), then put the signing secret here.', 'church-tithe-wp' ),
									),
									'error'             => array(
										'instruction_type' => 'error',
										'instruction_message' => __( 'Create a webhook in your Stripe account (in test mode), then put the signing secret here.', 'church-tithe-wp' ),
									),
									'success'           => array(
										'instruction_type' => 'success',
										'instruction_message' => __( 'Create a webhook in your Stripe account (in test mode), then put the signing secret here.', 'church-tithe-wp' ),
									),
								),
								'help_text'               => array(
									'react_component' => 'Church_Tithe_WP_Health_Check_As_Help_Lightbox',
									'component_data'  => array(
										'react_component' => 'Church_Tithe_WP_Stripe_Webhook_Health_Check',
										'key'             => 'stripe_webhook_signing_secret_test_mode',
										'server_api_endpoint_set_stripe_connect_success_url' => admin_url() . '?church_tithe_wp_set_ctwp_scsr',
										'church_tithe_wp_set_ctwp_scsr_nonce' => wp_create_nonce( 'church_tithe_wp_set_ctwp_scsr' ),
										'strings'         => array(
											'title'       => __( 'Let\'s get your Stripe Webhooks set up (Test Mode).', 'church-tithe-wp' ),
											'description' => __( 'Stripe webhooks are the way Stripe "talks" to the Church Tithe WP code on your website. There are 3 steps to take here.', 'church-tithe-wp' ),
											'ready_to_get_started' => __( 'Ready to get started?', 'church-tithe-wp' ),
										),
										'steps'           => array(
											'step1' => array(
												'title' => __( '1. Copy the endpoint URL', 'church-tithe-wp' ),
												'description' => __( 'Copy this url:', 'church-tithe-wp' ),
												'url_to_copy' => get_bloginfo( 'url' ) . '?church_tithe_wp_stripe_webhook',
											),
											'step2' => array(
												'title' => __( '2. Create Stripe Webhook', 'church-tithe-wp' ),
												'description' => array(
													'line_1' => __( 'A) Click the button below to log into your Stripe Account.', 'church-tithe-wp' ),
													'line_2' => __( 'B) Then, click "Developers, Webhooks, Add Endpoint" and paste the Endpoint URL.', 'church-tithe-wp' ),
													'line_3' => __( 'C) Under "Events to send", click on "Receive all events".', 'church-tithe-wp' ),
													'line_4' => __( 'PLEASE NOTE: this link is not in the dropdown menu, but underneath it.', 'church-tithe-wp' ),
												),
												'stripe_connect_button_text' => __( 'Go to my Stripe Account in a new tab', 'church-tithe-wp' ),
												'stripe_connect_url' => 'https://dashboard.stripe.com/test/logs', // Using this because Stripe doesn't have a direct link to test Stripe webhooks. Only live. Otherwise we'd use: 'https://dashboard.stripe.com/account/webhooks'.
											),
											'step3' => array(
												'title' => __( '3. Copy the "Signing secret" and paste here', 'church-tithe-wp' ),
												'description' => __( 'Under the section called "Signing secret", copy the secret text and paste it here', 'church-tithe-wp' ),
												'input_field' => array(
													'id'   => 'stripe_webhook_signing_secret_test_mode',
													'react_component' => 'MP_WP_Admin_Input_Field',
													'type' => 'text',
													'saved_value' => church_tithe_wp_get_saved_setting( $saved_settings, 'stripe_webhook_signing_secret_test_mode' ),
													'default_value' => '',
													'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
													'server_validation_callback_function' => 'church_tithe_wp_validate_test_stripe_webhook',
													'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
													'nonce' => wp_create_nonce( 'stripe_webhook_signing_secret_test_mode' ),
													'instruction_codes' => array(
														'empty_initial'          => array(
															'instruction_type'    => 'normal',
															'instruction_message' => __( 'Paste the signing secret here.', 'church-tithe-wp' ),
														),
														'empty_not_initial'      => array(
															'instruction_type'    => 'normal',
															'instruction_message' => __( 'Paste the signing secret here.', 'church-tithe-wp' ),
														),
														'error'                  => array(
															'instruction_type'    => 'error',
															'instruction_message' => __( 'Paste the signing secret here.', 'church-tithe-wp' ),
														),
														'invalid_length'         => array(
															'instruction_type'    => 'error',
															'instruction_message' => __( 'Double check the length of the key.', 'church-tithe-wp' ),
														),
														'does_not_contain_whsec' => array(
															'instruction_type'    => 'error',
															'instruction_message' => __( 'Make sure they key you copied begins with "whsec_".', 'church-tithe-wp' ),
														),
														'success'                => array(
															'instruction_type'    => 'success',
															'instruction_message' => __( 'Paste the signing secret here.', 'church-tithe-wp' ),
														),
													),
												),
											),
										),
									),
								),
							),
							'stripe_test_mode'         => array(
								'react_component'         => 'MP_WP_Admin_Checkbox_Field',
								'default_value'           => '',
								'saved_value'             => church_tithe_wp_get_saved_setting( $saved_settings, 'stripe_test_mode' ),
								'client_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
								'server_validation_callback_function' => 'church_tithe_wp_validate_simple_input',
								'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_save_setting',
								'nonce'                   => wp_create_nonce( 'stripe_test_mode' ),
								'selected_text'           => __( 'Enable Stripe Test Mode', 'church-tithe-wp' ),
								'unselected_text'         => __( 'Enable Stripe Test Mode', 'church-tithe-wp' ),
								'instruction_codes'       => array(
									'empty_initial'     => array(
										'instruction_type' => 'normal',
										'instruction_message' => __( 'Testing your Tithe Form? Check this box to turn on Test Mode (no live purchases possible)', 'church-tithe-wp' ),
									),
									'empty_not_initial' => array(
										'instruction_type' => 'normal',
										'instruction_message' => __( 'Testing your Tithe Form? Check this box to turn on Test Mode (no live purchases possible)', 'church-tithe-wp' ),
									),
									'error'             => array(
										'instruction_type' => 'error',
										'instruction_message' => __( 'Testing your Tithe Form? Check this box to turn on Test Mode (no live purchases possible)', 'church-tithe-wp' ),
									),
									'success'           => array(
										'instruction_type' => 'success',
										'instruction_message' => __( 'Testing your Tithe Form? Check this box to turn on Test Mode (no live purchases possible)', 'church-tithe-wp' ),
									),
								),
								'help_text'               => array(
									'title' => __( 'Stripe Test Mode', 'church-tithe-wp' ),
									'body'  => __( 'If you enable Stripe Test mode, you can use test credit cards from Stripe to test your Tithe Form. However, in test mode, you cannot accept real purchases. Leave this option unchecked to be in Live Mode. Learn more about Stripe test credit cards at this URL: https://stripe.com/docs/testing#cards', 'church-tithe-wp' ),
								),
							),
						),
					),
				),
			),
			'transactions'    => array(
				'visual_name'                         => __( 'Transactions', 'church-tithe-wp' ),
				'react_component'                     => 'MP_WP_Admin_List_View',
				'react_component_single_item_view'    => 'Church_Tithe_WP_Admin_Single_Transaction',
				'server_api_endpoint_url'             => admin_url() . '?church_tithe_wp_get_transaction_history_admin',
				'server_api_endpoint_url_single_item' => admin_url() . '?church_tithe_wp_get_transaction_admin',
				'server_api_endpoint_url_refund_transaction' => admin_url() . '?church_tithe_wp_refund_transaction_admin',
				'nonce'                               => wp_create_nonce( 'transactions' ),
				'nonce_refund_transaction'            => wp_create_nonce( 'church_tithe_wp_nonce_refund_transaction' ),
				'total_items'                         => 0,
				'items_per_page'                      => 20,
				'columns'                             => array(),
				'rows'                                => array(),
				'strings'                             => array(
					'refund_transaction'              => __( 'Refund', 'church-tithe-wp' ),
					'refund_transaction_are_you_sure' => __( 'Are you sure you\'d like to refund this transaction?', 'church-tithe-wp' ),
					'refund_transaction_refunding'    => __( 'Refunding transaction...', 'church-tithe-wp' ),
					'refund_transaction_failed'       => __( 'Refund failed. Please try again.', 'church-tithe-wp' ),
					'refund_transaction_succeeded'    => __( 'Refund succeeded.', 'church-tithe-wp' ),
					'refund_transaction_pending'      => __( 'Waiting for response from Stripe. Click to refresh.', 'church-tithe-wp' ),
					'back_to_list_view'               => __( 'Back to transaction history', 'church-tithe-wp' ),
					'uppercase_search'                => __( 'Search', 'church-tithe-wp' ),
					'lowercase_search'                => __( 'search', 'church-tithe-wp' ),
					'uppercase_page'                  => __( 'Page', 'church-tithe-wp' ),
					'lowercase_page'                  => __( 'page', 'church-tithe-wp' ),
					'uppercase_items'                 => __( 'Items', 'church-tithe-wp' ),
					'lowercase_items'                 => __( 'items', 'church-tithe-wp' ),
					'uppercase_per'                   => __( 'Per', 'church-tithe-wp' ),
					'lowercase_per'                   => __( 'per', 'church-tithe-wp' ),
					'uppercase_of'                    => __( 'Of', 'church-tithe-wp' ),
					'lowercase_of'                    => __( 'of', 'church-tithe-wp' ),
				),
				'single_data_view'                    => array(
					'visual_name' => __( 'Transaction Information', 'church-tithe-wp' ),
					'fields'      => array(
						'id' => array(
							'react_component' => 'immutable_text',
							'message'         => __( 'This is the ID of the transaction', 'church-tithe-wp' ),
						),
						'id' => array(
							'react_component' => 'immutable_text',
							'message'         => __( 'This is the ID of the transaction', 'church-tithe-wp' ),
						),
					),
				),
			),
			'arrangements'    => array(
				'visual_name'                         => __( 'Plans', 'church-tithe-wp' ),
				'react_component'                     => 'MP_WP_Admin_List_View',
				'react_component_single_item_view'    => 'Church_Tithe_WP_Admin_Single_Arrangement',
				'server_api_endpoint_url'             => admin_url() . '?church_tithe_wp_get_arrangement_history_admin',
				'server_api_endpoint_url_single_item' => admin_url() . '?church_tithe_wp_get_arrangement_admin',
				'server_api_endpoint_url_cancel_arrangement' => admin_url() . '?church_tithe_wp_cancel_arrangement_admin',
				'nonce'                               => wp_create_nonce( 'arrangements' ),
				'nonce_cancel_arrangement'            => wp_create_nonce( 'church_tithe_wp_nonce_cancel_arrangement' ),
				'total_items'                         => 0,
				'items_per_page'                      => 20,
				'columns'                             => array(),
				'rows'                                => array(),
				'strings'                             => array(
					'cancel_arrangement'              => __( 'Cancel Plan', 'church-tithe-wp' ),
					'cancel_arrangement_are_you_sure' => __( 'Are you sure you\'d like to cancel this arrangement?', 'church-tithe-wp' ),
					'cancel_arrangement_cancelling'   => __( 'Cancelling arrangement...', 'church-tithe-wp' ),
					'cancel_arrangement_failed'       => __( 'Cancellation failed. Please try again.', 'church-tithe-wp' ),
					'cancel_arrangement_succeeded'    => __( 'Successfully cancelled.', 'church-tithe-wp' ),
					'cancel_arrangement_pending'      => __( 'Waiting for response from Stripe. Click to refresh.', 'church-tithe-wp' ),
					'back_to_list_view'               => __( 'Back to arrangement history', 'church-tithe-wp' ),
					'uppercase_search'                => __( 'Search', 'church-tithe-wp' ),
					'lowercase_search'                => __( 'search', 'church-tithe-wp' ),
					'uppercase_page'                  => __( 'Page', 'church-tithe-wp' ),
					'lowercase_page'                  => __( 'page', 'church-tithe-wp' ),
					'uppercase_items'                 => __( 'Items', 'church-tithe-wp' ),
					'lowercase_items'                 => __( 'items', 'church-tithe-wp' ),
					'uppercase_per'                   => __( 'Per', 'church-tithe-wp' ),
					'lowercase_per'                   => __( 'per', 'church-tithe-wp' ),
					'uppercase_of'                    => __( 'Of', 'church-tithe-wp' ),
					'lowercase_of'                    => __( 'of', 'church-tithe-wp' ),
				),
				'single_data_view'                    => array(
					'visual_name' => __( 'Plan Information', 'church-tithe-wp' ),
					'fields'      => array(
						'id' => array(
							'react_component' => 'immutable_text',
							'message'         => __( 'This is the ID of the transaction', 'church-tithe-wp' ),
						),
						'id' => array(
							'react_component' => 'immutable_text',
							'message'         => __( 'This is the ID of the transaction', 'church-tithe-wp' ),
						),
					),
				),
				'transactions_in_arrangement'         => array(
					'visual_name'             => __( 'Transactions in this Plan', 'church-tithe-wp' ),
					'server_api_endpoint_url' => admin_url() . '?church_tithe_wp_get_transaction_history_admin',
					'nonce'                   => wp_create_nonce( 'transactions' ),
					'total_items'             => 0,
					'items_per_page'          => 20,
					'columns'                 => array(),
					'rows'                    => array(),
					'strings'                 => array(
						'uppercase_search' => __( 'Search', 'church-tithe-wp' ),
						'lowercase_search' => __( 'search', 'church-tithe-wp' ),
						'uppercase_page'   => __( 'Page', 'church-tithe-wp' ),
						'lowercase_page'   => __( 'page', 'church-tithe-wp' ),
						'uppercase_items'  => __( 'Items', 'church-tithe-wp' ),
						'lowercase_items'  => __( 'items', 'church-tithe-wp' ),
						'uppercase_per'    => __( 'Per', 'church-tithe-wp' ),
						'lowercase_per'    => __( 'per', 'church-tithe-wp' ),
						'uppercase_of'     => __( 'Of', 'church-tithe-wp' ),
						'lowercase_of'     => __( 'of', 'church-tithe-wp' ),
					),
				),
			),
		),

	);

	return $church_tithe_wp_admin_views_and_settings;

}
