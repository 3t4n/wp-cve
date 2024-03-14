<?php
/**
 * This file contains google recaptcha code.
 *
 * @author  Tech Banker
 * @package captcha-bank/includes
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly.
global $wpdb, $display_settings_data, $meta_data_array, $display_setting, $error_data_array;

// include file where is_plugin_active() function is defined.
if ( file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$display_settings_data = $wpdb->get_var(
	$wpdb->prepare(
		'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'display_settings'
	)
);// db call ok; no-cache ok.
$meta_data_array = maybe_unserialize( $display_settings_data );
$display_setting = explode( ',', isset( $meta_data_array['settings'] ) ? $meta_data_array['settings'] : '' );

/* This action hooks is used to display and validate captcha on login form */
if ( '1' === $display_setting[0] ) {
	if ( ! isset( $_REQUEST['wpforo'] ) ) { // WPCS: CSRF ok, input var ok.

		// add Google API JS script on the login section of the site.
		add_action( 'login_enqueue_scripts', 'cb_header_script' );

		// add CSS to make sure the Google Captcha fits nicely
		add_action( 'login_enqueue_scripts', 'cb_wp_css', 10, 2 );

		// adds the required HTML for the REcaptcha to the login form.
		add_action( 'login_form', 'cb_display_captcha' );

		// authenticate the captcha answer.
		add_filter( 'authenticate', 'cb_captcha_on_login', 21, 3 );
	}
} else {

	// Authenticate google recaptcha.
	add_action( 'wp_authenticate', 'captcha_bank_check_user_login_status', 10, 2 );
}

/* This action hook is used to display and validate captcha on registeration form */
if ( '1' === $display_setting[2] ) {
	if ( is_multisite() ) {
		if ( ! isset( $_REQUEST['wpforo'] ) ) {// WPCS: CSRF ok, input var ok.

			// add Google API JS script on the login section of the site
			add_action( 'login_enqueue_scripts', 'cb_header_script' );

			add_action( 'signup_extra_fields', 'cb_display_captcha' );

			add_action( 'wpmu_signup_user_notification', 'cb_validate_captcha_registration_field', 10, 2 );
		}
	} else {
		if ( ! isset( $_REQUEST['wpforo'] ) ) { // WPCS: CSRF ok, input var ok.

			// add Google API JS script on the login section of the site
			add_action( 'login_enqueue_scripts', 'cb_header_script' );

			// adds the required HTML for the REcaptcha to the registration form.
			add_action( 'register_form', 'cb_display_captcha' );

			// authenticate the captcha answer.
			add_action( 'registration_errors', 'cb_validate_captcha_registration_field', 10, 2 );
		}
	}
}
/* This action Hook is Used to create and validate captcha on Lost-Password form */
if ( '1' === $display_setting[4] ) {

	// add Google API JS script on the login section of the site
	add_action( 'login_enqueue_scripts', 'cb_header_script', 10, 2 );

	// adds the required HTML for the REcaptcha to the lost password form.
	add_action( 'lostpassword_form', 'cb_display_captcha' );

	// authenticate the captcha answer.
	add_action( 'lostpassword_post', 'cb_validate_recover_pwd_form' );
}
/* This action hook is used to display and validate captcha on comment form */
if ( '1' === $display_setting[6] ) {

	// add captcha header script to WordPress header.
	add_action( 'wp_head', 'cb_header_script' );

	// adds captcha above the submit button.
	add_action( 'comment_form_after_fields', 'cb_display_captcha_comment_form', 1 );

	// authenticate the captcha answer.
	add_filter( 'preprocess_comment', 'cb_validate_captcha_comment_field' );
}

/* add_action for admin comment form and hide captcha for other. */
if ( '1' === $display_setting[8] || '0' === $display_setting[10] ) {

	// add captcha header script to WordPress header.
	add_action( 'wp_head', 'cb_header_script' );

	// adds captcha above the submit button.
	add_action( 'comment_form_logged_in_after', 'cb_display_captcha_comment_form' );

	// authenticate the captcha answer.
	add_filter( 'preprocess_comment', 'cb_validate_captcha_admin_comment_field' );
}

// Functions to implement recaptcha.
/**
* Handles the display of the reCaptcha form on the WordPress login form.
*
* @param   string $user .
* @param   string $password .
*
* @return  object  WP_Error
*/
function cb_captcha_on_login( $user, $username, $password ) {
	global $wpdb;
	$ip_address                 = sprintf( '%u', ip2long( get_ip_address_for_captcha_bank() ) );
	$error_data           = $wpdb->get_var(
		$wpdb->prepare(
			'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'error_message'
		)
	);// db call ok; no-cache ok.
	$error_data_array           = maybe_unserialize( $error_data );
	if ( isset( $_REQUEST['ux_div_google_recaptcha'] ) || isset( $_POST['g-recaptcha-response'] ) ) {
		update_option('test', 'google-recaptcha');
		$userdata        = get_user_by( 'login', $username );
		$user_email_data = get_user_by( 'email', $username );
		if ( ( $userdata && wp_check_password( $password, $userdata->user_pass ) ) || ( $user_email_data && wp_check_password( $password, $user_email_data->user_pass ) ) )
		{
			captcha_bank_user_log_in_success( $username, $ip_address );
			return $user;
		} else {
			captcha_bank_user_log_in_fails( $username, $ip_address );
		}
		if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
			die( $error_data_array['for_captcha_empty_error'] );
		}
		if ( isset( $_POST['g-recaptcha-response'] ) && 'false' === cb_validate_captcha() ) {
			die( 'CAPTCHA response was incorrect' );
		}
	}
	return $user;
}
/**
* @param   string $sanitized_user_login
* @param   string $user_email
*
* @return  object    WP_Error
*/
function cb_validate_captcha_registration_field( $sanitized_user_login, $user_email ) {
	global $wpdb;
	$error_data           = $wpdb->get_var(
		$wpdb->prepare(
			'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'error_message'
		)
	);// db call ok; no-cache ok.
	$error_data_array           = maybe_unserialize( $error_data );
	if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
		die( $error_data_array['for_captcha_empty_error'] );
	}
	if ( isset( $_POST['g-recaptcha-response'] ) && 'false' === cb_validate_captcha() ) {
		die( 'CAPTCHA response was incorrect' );
	}
	return $sanitized_user_login;
}
/**
 * Function that handles the validation & error messages
 * for the noCaptcha reCaptcha form
 *
 * @since   1.0.1
 */
function cb_validate_recover_pwd_form() {
	global $wpdb;
	$error_data           = $wpdb->get_var(
		$wpdb->prepare(
			'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'error_message'
		)
	);// db call ok; no-cache ok.
	$error_data_array           = maybe_unserialize( $error_data );
	if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
		die( $error_data_array['for_captcha_empty_error'] );
	}
	if ( isset( $_POST['g-recaptcha-response'] ) && 'false' === cb_validate_captcha() ) {
		die( 'CAPTCHA response was incorrect' );
	}
}
/**
 * Function that handles the reCaptcha form validation.
 *
 * @param   array $commentdata .
 *
 * @return  array   $commendata    Returns the commentdata as it was sent by the WordPress post.
 */
function cb_validate_captcha_comment_field( $commentdata ) {
	global $wpdb, $display_setting;
	$error_data           = $wpdb->get_var(
		$wpdb->prepare(
			'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'error_message'
		)
	);// db call ok; no-cache ok.
	$error_data_array           = maybe_unserialize( $error_data );
	if ( '1' === $display_setting[6] && '0' === $display_setting[10] ) {
		if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
			die( $error_data_array['for_captcha_empty_error'] );
		}
		if ( isset( $_POST['g-recaptcha-response'] ) && 'false' === cb_validate_captcha() ) {
			die( 'CAPTCHA response was incorrect' );
		}
	}
	return $commentdata;
}
/**
 * Function that handles the reCaptcha form validation.
 *
 * @param   array $commentdata .
 *
 * @return  array   $commendata    Returns the commentdata as it was sent by the WordPress post.
 */
function cb_validate_captcha_admin_comment_field( $commentdata ) {
	global $wpdb, $display_setting;
	$error_data           = $wpdb->get_var(
		$wpdb->prepare(
			'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'error_message'
		)
	);// db call ok; no-cache ok.
	$error_data_array           = maybe_unserialize( $error_data );
	if ( '1' === $display_setting[8] && '0' === $display_setting[10] ) {
		if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
			die( $error_data_array['for_captcha_empty_error'] );
		}
		if ( isset( $_POST['g-recaptcha-response'] ) && 'false' === cb_validate_captcha() ) {
			die( 'CAPTCHA response was incorrect' );
		}
	}
	return $commentdata;
}
