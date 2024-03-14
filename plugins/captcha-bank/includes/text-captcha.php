<?php
/**
 * This file contains text captcha code.
 *
 * @author  Tech Banker
 * @package captcha-bank/includes
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
ob_start();
if ( '' === session_id() ) {// @codingStandardsIgnoreLine.
	@session_start();// @codingStandardsIgnoreLine.
}

// get settings values.
if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/captcha-setting.php' ) ) {
	include_once CAPTCHA_BANK_DIR_PATH . 'includes/captcha-setting.php';
}
// include file where is_plugin_active() function is defined.
if ( file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}
// add_action for login.
if ( '1' === $display_setting[0] ) {
	if ( ! isset( $_REQUEST['wpforo'] ) ) {// WPCS: CSRF ok, input var ok.
		add_action( 'login_form', 'captcha_bank_text_captcha_form' );
		add_filter( 'authenticate', 'captcha_text_login_check', 21, 3 );
	}
} else {
	add_action( 'wp_authenticate', 'captcha_bank_check_user_login_status', 10, 2 );
}
// add_action for registration page.
if ( '1' === $display_setting[2] ) {
	if ( is_multisite() ) {
		if ( ! isset( $_REQUEST['wpforo'] ) ) {// WPCS: CSRF ok, input var ok.
			add_action( 'signup_extra_fields', 'captcha_bank_text_captcha_form', 10, 2 );
			add_action( 'wpmu_signup_user_notification', 'captcha_register_check', 10, 3 );
		}
	} else {
		if ( ! isset( $_REQUEST['wpforo'] ) ) {// WPCS: CSRF ok, input var ok.
			add_action( 'register_form', 'captcha_bank_text_captcha_form' );
			add_action( 'register_post', 'captcha_register_check', 10, 3 );
		}
	}
}
// add_action for lost-password.
if ( '1' === $display_setting[4] ) {
	add_action( 'lostpassword_form', 'captcha_bank_text_captcha_form' );
	add_action( 'allow_password_reset', 'captcha_lostpassword_check', 1 );
}
// add_action for comment form.
if ( '1' === $display_setting[6] ) {
	add_action( 'comment_form_after_fields', 'captcha_comment_form', 1 );
	add_action( 'pre_comment_on_post', 'captcha_comment_check' );
}
// add_action for admin comment form and hide captcha for other.
if ( '1' === $display_setting[8] || '0' === $display_setting[10] ) {

	add_action( 'comment_form_logged_in_after', 'captcha_comment_form' );
	add_action( 'pre_comment_on_post', 'captcha_comment_check' );
}
if ( ! function_exists( 'captcha_bank_text_captcha_form' ) ) {
	/**
	 * Function to display captcha.
	 */
	function captcha_bank_text_captcha_form() {
		global $wpdb, $captcha_array;
		if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/captcha-frontend.php' ) ) {
			include CAPTCHA_BANK_DIR_PATH . 'includes/captcha-frontend.php';
		}
	}
}
if ( ! function_exists( 'captcha_text_login_check' ) ) {
	/**
	 * Function to display error for login form.
	 *
	 * @param string $user .
	 * @param string $username .
	 * @param string $password .
	 */
	function captcha_text_login_check( $user, $username, $password ) {
		global $wpdb, $captcha_array, $error_data_array;
		$captcha_bank_logical_error = __( 'ERROR', 'captcha-bank' );
		$err                        = captcha_login_errors();
		$ip_address                 = sprintf( '%u', ip2long( get_ip_address_for_captcha_bank() ) );
		if ( $err ) {
			if ( 'empty' === $err ) {
				$error = new WP_Error( 'captcha_wrong', '<strong>' . $captcha_bank_logical_error . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
			} elseif ( 'invalid' === $err ) {
				$error = new WP_Error( 'captcha_wrong', '<strong>' . $captcha_bank_logical_error . '</strong>: ' . $error_data_array['for_invalid_captcha_error'] );
			}
			captcha_bank_user_log_in_fails( $username, $ip_address );
			return $error;
		} elseif ( isset( $_REQUEST['ux_txt_captcha_challenge_field'] ) && isset( $_SESSION['captcha_code'] ) ) {// @codingStandardsIgnoreLine.
			$captcha_challenge_field                                        = 'enable' === $captcha_array['case_sensitive'] ? trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) : strtolower( trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) );// WPCS: CSRF ok, input var ok, sanitization ok.// WPCS: CSRF ok, input var ok, sanitization ok.
			'enable' === $captcha_array['case_sensitive'] ? $captcha_code[] = $_SESSION['captcha_code'] : $captcha_code[] = array_map( 'strtolower', $_SESSION['captcha_code'] );// @codingStandardsIgnoreLine.
			if ( in_array( $captcha_challenge_field, $captcha_code[0], true ) ) {
				$userdata        = get_user_by( 'login', $username );
				$user_email_data = get_user_by( 'email', $username );
				if ( ( $userdata && wp_check_password( $password, $userdata->user_pass ) ) || ( $user_email_data && wp_check_password( $password, $user_email_data->user_pass ) ) ) {
					captcha_bank_user_log_in_success( $username, $ip_address );
					return $user;
				} else {
					captcha_bank_user_log_in_fails( $username, $ip_address );
				}
			}
		} else {
			if ( isset( $_REQUEST['log'] ) && isset( $_REQUEST['pwd'] ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
				/* captcha was not found in _REQUEST */
				$error = new WP_Error( 'captcha_wrong', '<strong>' . $captcha_bank_logical_error . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
				return $error;
			} else {
				/* it is not a submit */
				return $user;
			}
		}
	}
}
if ( ! function_exists( 'captcha_lostpassword_check' ) ) {
	/**
	 * Function to dislpay error for lost-password form.
	 *
	 * @param string $user .
	 */
	function captcha_lostpassword_check( $user ) {
		global $errors, $error_data_array;
		$captcha_bank_logical_error = __( 'ERROR', 'captcha-bank' );
		$err                        = captcha_errors();
		if ( $err ) {
			if ( null === $errors ) {
				$errors = new WP_Error();// WPCS: override ok.
			}
			if ( 'empty' === $err ) {
				$error = new WP_Error( 'captcha_wrong', '<strong>' . $captcha_bank_logical_error . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
			} elseif ( 'invalid' === $err ) {
				$error = new WP_Error( 'captcha_wrong', '<strong>' . $captcha_bank_logical_error . '</strong>: ' . $error_data_array['for_invalid_captcha_error'] );
			}
			return $error;
		}
		return $user;
	}
}
if ( ! function_exists( 'captcha_register_check' ) ) {
	/**
	 * Function to display error for registration form.
	 *
	 * @param string $user .
	 * @param string $email .
	 * @param string $errors .
	 */
	function captcha_register_check( $user, $email, $errors ) {
		global $error_data_array;
		$captcha_bank_logical_error = __( 'ERROR', 'captcha-bank' );
		$err                        = captcha_errors();
		if ( $err ) {
			if ( is_multisite() ) {
				if ( 'empty' === $err ) {
					wp_die( '<strong>' . esc_attr( $captcha_bank_logical_error ) . '</strong>: ' . esc_attr( $error_data_array['for_captcha_empty_error'] ) );
				} elseif ( 'invalid' === $err ) {
					wp_die( '<strong>' . esc_attr( $captcha_bank_logical_error ) . '</strong>: ' . esc_attr( $error_data_array['for_invalid_captcha_error'] ) );
				}
			} else {
				if ( 'empty' === $err ) {
					$errors->add( 'captcha_wrong', '<strong>' . $captcha_bank_logical_error . '</strong>: ' . $error_data_array['for_captcha_empty_error'] );
				} elseif ( 'invalid' === $err ) {
					$errors->add( 'captcha_wrong', '<strong>' . $captcha_bank_logical_error . '</strong>: ' . $error_data_array['for_invalid_captcha_error'] );
				}
			}
		}
	}
}
if ( ! function_exists( 'captcha_comment_check' ) ) {
	/**
	 * Function to display error for comment form .
	 */
	function captcha_comment_check() {
		global $error_data_array;
		$err = captcha_errors();
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
}
if ( ! function_exists( 'captcha_comment_form' ) ) {
	/**
	 * Function to display captcha on admin comment form .
	 */
	function captcha_comment_form() {
		global $wpdb, $current_user, $display_setting;
		if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/captcha-setting.php' ) ) {
			include_once CAPTCHA_BANK_DIR_PATH . 'includes/captcha-setting.php';
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
				if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/captcha-frontend.php' ) ) {
					include CAPTCHA_BANK_DIR_PATH . 'includes/captcha-frontend.php';
				}
			}
		} else {
			if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'includes/captcha-frontend.php' ) ) {
				include CAPTCHA_BANK_DIR_PATH . 'includes/captcha-frontend.php';
			}
		}
	}
}
if ( ! function_exists( 'captcha_login_errors' ) ) {
	/**
	 * Function to check error for login page and return error type .
	 *
	 * @param int $errors .
	 */
	function captcha_login_errors( $errors = null ) {
		global $wpdb, $captcha_array;
		if ( isset( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
			'enable' === $captcha_array['case_sensitive'] ? $captcha_challenge_field = trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) : $captcha_challenge_field = strtolower( trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) );// @codingStandardsIgnoreLine.

			if ( strlen( $captcha_challenge_field ) <= 0 ) {
				$errors                                  = 'empty';
				$captcha_meta_settings['captcha_status'] = 0;
			} else {
				if ( isset( $_SESSION['captcha_code'] ) ) {// @codingStandardsIgnoreLine.
					'enable' === $captcha_array['case_sensitive'] ? $code[] = $_SESSION['captcha_code'] : $code[] = array_map( 'strtolower', $_SESSION['captcha_code'] );// @codingStandardsIgnoreLine.
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
}
if ( ! function_exists( 'captcha_errors' ) ) {
	/**
	 * Function to check captcha error and return error type.
	 *
	 * @param int $errors .
	 */
	function captcha_errors( $errors = null ) {
		global $captcha_array;
		if ( isset( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
			'enable' === $captcha_array['case_sensitive'] ? $captcha_challenge_field = trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) : $captcha_challenge_field = strtolower( trim( esc_attr( wp_unslash( $_REQUEST['ux_txt_captcha_challenge_field'] ) ) ) );// @codingStandardsIgnoreLine.

			if ( strlen( $captcha_challenge_field ) <= 0 ) {
				$errors                                  = 'empty';
				$captcha_meta_settings['captcha_status'] = 0;
			} else {
				if ( isset( $_SESSION['captcha_code'] ) ) {// @codingStandardsIgnoreLine.
					'enable' === $captcha_array['case_sensitive'] ? $code[] = $_SESSION['captcha_code'] : $code[] = array_map( 'strtolower', $_SESSION['captcha_code'] );// @codingStandardsIgnoreLine.
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
}
