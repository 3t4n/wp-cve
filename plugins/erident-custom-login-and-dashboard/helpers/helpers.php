<?php
/** Custom Login Dashboard's helper functions.
 *
 * @package Custom_Login_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Get field data types.
 */
function cldashboard_get_field_data_types() {

	return [
		'dashboard_data_left'         => 'string',
		'dashboard_data_right'        => 'string',
		'dashboard_image_logo'        => 'string',
		'dashboard_image_logo_width'  => 'int',
		'dashboard_image_logo_height' => 'int',
		'dashboard_power_text'        => 'string',
		'dashboard_login_width'       => 'int',
		'dashboard_login_radius'      => 'int',
		'dashboard_login_border'      => 'string',
		'dashboard_border_thick'      => 'int',
		'dashboard_border_color'      => 'string',
		'dashboard_login_bg'          => 'string',
		'dashboard_login_bg_opacity'  => 'float', // Deprecated.
		'dashboard_text_color'        => 'string',
		'dashboard_input_text_color'  => 'string',
		'dashboard_label_text_size'   => 'int',
		'dashboard_input_text_size'   => 'int',
		'dashboard_link_color'        => 'string',
		'dashboard_check_shadow'      => 'bool',
		'dashboard_link_shadow'       => 'string',
		'dashboard_check_form_shadow' => 'bool',
		'dashboard_check_lost_pass'   => 'bool',
		'dashboard_check_backtoblog'  => 'bool',
		'dashboard_form_shadow'       => 'string',
		'dashboard_button_color'      => 'string',
		'dashboard_button_text_color' => 'string',
		'top_bg_color'                => 'string',
		'top_bg_image'                => 'string',
		'top_bg_repeat'               => 'string',
		'top_bg_xpos'                 => 'string',
		'top_bg_ypos'                 => 'string',
		'login_bg_image'              => 'string',
		'login_bg_repeat'             => 'string',
		'login_bg_xpos'               => 'string',
		'login_bg_ypos'               => 'string',
		'top_bg_size'                 => 'string',
		'dashboard_delete_db'         => 'bool',
	];

}

/**
 * Get field default values.
 */
function cldashboard_get_field_default_values() {

	return [
		'dashboard_data_left'         => 'Thank you for creating with <a href="https://wordpress.org/">WordPress</a>.',
		'dashboard_data_right'        => '&copy; 2022 All Rights Reserved',
		'dashboard_image_logo'        => CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL . '/assets/images/default-logo.png',
		'dashboard_image_logo_width'  => 90,
		'dashboard_image_logo_height' => 90,
		'dashboard_power_text'        => 'Powered by Your Website',
		'dashboard_login_width'       => 320,
		'dashboard_login_radius'      => 4,
		'dashboard_login_border'      => 'solid',
		'dashboard_border_thick'      => 2,
		'dashboard_border_color'      => '#dddddd',
		'dashboard_login_bg'          => '#ffffff',
		'dashboard_login_bg_opacity'  => 1, // Deprecated.
		'dashboard_text_color'        => '#3c434a',
		'dashboard_input_text_color'  => '#2c3338',
		'dashboard_label_text_size'   => 14,
		'dashboard_input_text_size'   => 24,
		'dashboard_link_color'        => '#50575e',
		'dashboard_check_shadow'      => 0,
		'dashboard_link_shadow'       => '#ffffff',
		'dashboard_check_form_shadow' => 0,
		'dashboard_check_lost_pass'   => 0,
		'dashboard_check_backtoblog'  => 0,
		'dashboard_form_shadow'       => '#CCCCCC',
		'dashboard_button_color'      => '#2271b1',
		'dashboard_button_text_color' => '#FFFFFF',
		'top_bg_color'                => '#f1f1f1',
		'top_bg_image'                => '',
		'top_bg_repeat'               => 'repeat',
		'top_bg_xpos'                 => 'left',
		'top_bg_ypos'                 => 'top',
		'login_bg_image'              => '',
		'login_bg_repeat'             => 'repeat',
		'login_bg_xpos'               => 'left',
		'login_bg_ypos'               => 'top',
		'top_bg_size'                 => 'auto',
		'dashboard_delete_db'         => 0,
	];

}
