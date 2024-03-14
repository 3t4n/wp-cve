<?php
/**
 * This file contains text captcha code.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/includes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
ob_start();
if ( '' === session_id() ) { // @codingStandardsIgnoreLine
	@session_start(); // @codingStandardsIgnoreLine
}

// get settings values.
if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/captcha-setting.php' ) ) {
	include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/captcha-setting.php';
}
// include file where is_plugin_active() function is defined.
if ( file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// add_action for login.
if ( '1' === $display_setting[0] ) {
	add_action( 'login_form', 'captcha_booster_text_captcha_form' );
	add_filter( 'authenticate', 'captcha_booster_text_login_check', 21, 3 );
} else {
	add_action( 'wp_authenticate', 'captcha_booster_check_user_login_status', 10, 2 );
}

// add_action for registration page.
if ( '1' === $display_setting[2] ) {
	if ( is_multisite() ) {
		add_action( 'signup_extra_fields', 'captcha_booster_text_captcha_form', 10, 2 );
		add_action( 'wpmu_signup_user_notification', 'captcha_booster_register_check', 10, 3 );
	} else {
		add_action( 'register_form', 'captcha_booster_text_captcha_form' );
		add_action( 'register_post', 'captcha_booster_register_check', 10, 3 );
	}
}

// add_action for lost-password.
if ( '1' === $display_setting[4] ) {
	add_action( 'lostpassword_form', 'captcha_booster_text_captcha_form' );
	add_action( 'allow_password_reset', 'captcha_booster_lostpassword_check', 1 );
}

// add_action for comment form.
if ( '1' === $display_setting[6] ) {
	add_action( 'comment_form_after_fields', 'captcha_booster_comment_form', 1 );
	add_action( 'pre_comment_on_post', 'captcha_booster_comment_check' );
}

// add_action for admin comment form.
if ( '1' === $display_setting[8] ) {
	add_action( 'comment_form_logged_in_after', 'captcha_booster_comment_form', 1 );
	add_action( 'pre_comment_on_post', 'captcha_booster_comment_check' );
}
/**
 * Function to display captcha.
 */
function captcha_booster_text_captcha_form() {
	global $wpdb, $captcha_array;
	if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/captcha-frontend.php' ) ) {
		include CAPTCHA_BOOSTER_DIR_PATH . 'includes/captcha-frontend.php';
	}
}
/**
 * Function to display error for login form.
 *
 * @param string $user .
 * @param string $username .
 * @param string $password .
 */
function captcha_booster_text_login_check( $user, $username, $password ) {
	global $wpdb, $captcha_array, $error_data_array;
	$err        = captcha_booster_login_errors();
	$ip_address = sprintf( '%u', ip2long( get_ip_address_for_captcha_booster() ) );
	if ( $err ) {
		if ( 'empty' === $err ) {
			$error = new WP_Error( 'captcha_wrong', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
		} elseif ( 'invalid' === $err ) {
			$error = new WP_Error( 'captcha_wrong', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_invalid_captcha_error'] );
		}
		captcha_booster_user_log_in_fails( $username, $ip_address );
		return $error;
	} elseif ( isset( $_REQUEST['ux_txt_captcha_challenge_field'] ) && isset( $_SESSION['captcha_code'] ) ) { // @codingStandardsIgnoreLine
		'enable' === $captcha_array['case_sensitive'] ? $captcha_challenge_field = trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) : $captcha_challenge_field = strtolower( trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) ); // WPCS: input var ok, CSRF ok, sanitization okay.
		'enable' === $captcha_array['case_sensitive'] ? $captcha_code[]          = $_SESSION['captcha_code'] : $captcha_code[] = array_map( 'strtolower', $_SESSION['captcha_code'] ); // @codingStandardsIgnoreLine
		if ( in_array( $captcha_challenge_field, $captcha_code[0], true ) ) {
			$userdata        = get_user_by( 'login', $username );
			$user_email_data = get_user_by( 'email', $username );
			if ( ( $userdata && wp_check_password( $password, $userdata->user_pass ) ) || ( $user_email_data && wp_check_password( $password, $user_email_data->user_pass ) ) ) {
				captcha_booster_user_log_in_success( $username, $ip_address );
				return $user;
			} else {
				captcha_booster_user_log_in_fails( $username, $ip_address );
			}
		}
	} else {
		if ( isset( $_REQUEST['log'] ) && isset( $_REQUEST['pwd'] ) ) { // WPCS: CSRF ok, input var ok.
			/* captcha was not found in _REQUEST */
			$error = new WP_Error( 'captcha_wrong', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
			return $error;
		} else {
			/* it is not a submit */
			return $user;
		}
	}
}
/**
 * Function to dislpay error for lost-password form.
 *
 * @param string $user .
 */
function captcha_booster_lostpassword_check( $user ) {
	global $wpdb, $errors, $error_data_array;
	$err = captcha_booster_errors();
	if ( $err ) {
		if ( null === $errors ) {
			$errors = new WP_Error(); // @codingStandardsIgnoreLine
		}
		if ( 'empty' === $err ) {
			$error = new WP_Error( 'captcha_wrong', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
		} elseif ( 'invalid' === $err ) {
			$error = new WP_Error( 'captcha_wrong', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_invalid_captcha_error'] );
		}
		return $error;
	}
	return $user;
}
/**
 * Function to display error for registration form.
 *
 * @param string $user .
 * @param string $email .
 * @param string $errors .
 */
function captcha_booster_register_check( $user, $email, $errors ) {
	global $wpdb, $error_data_array;
	$err = captcha_booster_errors();
	if ( $err ) {
		if ( is_multisite() ) {
			if ( 'empty' === $err ) {
				wp_die( '<strong>' . esc_attr( __( 'ERROR', 'wp-captcha-booster' ) ) . '</strong>: ' . esc_attr( $error_data_array['for_captcha_empty_error'] ) );
			} elseif ( 'invalid' === $err ) {
				wp_die( '<strong>' . esc_attr( __( 'ERROR', 'wp-captcha-booster' ) ) . '</strong>: ' . esc_attr( $error_data_array['for_invalid_captcha_error'] ) );
			}
		} else {
			if ( 'empty' === $err ) {
				$errors->add( 'captcha_wrong', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
			} elseif ( 'invalid' === $err ) {
				$errors->add( 'captcha_wrong', '<strong>' . __( 'ERROR', 'wp-captcha-booster' ) . '</strong>: ' . $error_data_array['for_invalid_captcha_error'] );
			}
		}
	}
}
/**
 * Function to display error for comment form.
 */
function captcha_booster_comment_check() {
	global $wpdb, $error_data_array;
	$err = captcha_booster_errors();
	if ( $err ) {
		if ( 'empty' === $err ) {
			wp_die( esc_attr( $error_data_array['for_captcha_empty_error'] ) );
		} elseif ( 'invalid' === $err ) {
			wp_die( esc_attr( $error_data_array['for_invalid_captcha_error'] ) );
		}
	} else {
		return;
	}
}
/**
 * Function to display captcha on admin comment form.
 */
function captcha_booster_comment_form() {

	global $wpdb, $current_user, $user_role_permission, $display_setting;
	if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/captcha-setting.php' ) ) {
		include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/captcha-setting.php';
	}
	if ( is_user_logged_in() ) {
		if ( is_super_admin() ) {
			$cpb_role = 'administrator';
		} else {
			$cpb_role           = $wpdb->prefix . 'capabilities';
			$current_user->role = array_keys( $current_user->$cpb_role );
			$cpb_role           = $current_user->role[0];
		}
		if ( ( 'administrator' === $cpb_role && '1' === $display_setting[8] ) || ( 'administrator' !== $cpb_role && '0' === $display_setting[10] ) ) {
			if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/captcha-frontend.php' ) ) {
				include CAPTCHA_BOOSTER_DIR_PATH . 'includes/captcha-frontend.php';
			}
		}
	} else {
		if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/captcha-frontend.php' ) ) {
			include CAPTCHA_BOOSTER_DIR_PATH . 'includes/captcha-frontend.php';
		}
	}
}
/**
 * Function to check error for login page and return error type.
 *
 * @param int $errors .
 */
function captcha_booster_login_errors( $errors = null ) {
	global $wpdb, $captcha_array;
	if ( isset( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) { // WPCS: CSRF ok.
		'enable' === $captcha_array['case_sensitive'] ? $captcha_challenge_field = trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) : $captcha_challenge_field = strtolower( trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) ); // WPCS: CSRF ok, input var ok, sanitization okay.

		if ( strlen( $captcha_challenge_field ) <= 0 ) {
			$errors                                  = 'empty';
			$captcha_meta_settings['captcha_status'] = 0;
		} else {
			if ( isset( $_SESSION['captcha_code'] ) ) {// @codingStandardsIgnoreLine
				'enable' === $captcha_array['case_sensitive'] ? $code[] = $_SESSION['captcha_code'] : $code[] = array_map( 'strtolower', $_SESSION['captcha_code'] ); // @codingStandardsIgnoreLine
				if ( ! in_array( $captcha_challenge_field, $code[0], true ) ) {
					$errors                                  = 'invalid';
					$captcha_meta_settings['captcha_status'] = 0;
				} else {
					$captcha_meta_settings['captcha_status'] = 1;
				}
			}
		}
	}
	return $errors;
}
/**
 * Function to check captcha error and return error type.
 *
 * @param int $errors .
 */
function captcha_booster_errors( $errors = null ) {
	global $wpdb, $captcha_array;
	if ( isset( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) {
		'enable' === $captcha_array['case_sensitive'] ? $captcha_challenge_field = trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) : $captcha_challenge_field = strtolower( trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) ); // WPCS: input var ok, CSRF ok, sanitization okay.

		if ( strlen( $captcha_challenge_field ) <= 0 ) {
			$errors                                  = 'empty';
			$captcha_meta_settings['captcha_status'] = 0;
		} else {
			if ( isset( $_SESSION['captcha_code'] ) ) { // @codingStandardsIgnoreLine
				'enable' === $captcha_array['case_sensitive'] ? $code[] = $_SESSION['captcha_code'] : $code[] = array_map( 'strtolower', $_SESSION['captcha_code'] ); // @codingStandardsIgnoreLine
				if ( ! in_array( $captcha_challenge_field, $code[0], true ) ) {
					$errors                                  = 'invalid';
					$captcha_meta_settings['captcha_status'] = 0;
				} else {
					$captcha_meta_settings['captcha_status'] = 1;
				}
			}
		}
	}
	return $errors;
}
