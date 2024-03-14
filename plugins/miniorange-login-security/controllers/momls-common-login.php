<?php
/**
 * This file contains functions related to login flow.
 *
 * @package miniorange-login-security/controllers/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * This function redirect user to given url.
 *
 * @param object $user object containing user details.
 * @param string $redirect_to redirect url.
 * @return void
 */
function momls_redirect_user_to( $user, $redirect_to ) {
	$roles        = $user->roles;
	$current_role = array_shift( $roles );
	$redirect_url = isset( $redirect_to ) && ! empty( $redirect_to ) ? $redirect_to : null;
	if ( 'administrator' === $current_role ) {
		$redirect_url = empty( $redirect_url ) ? admin_url() : $redirect_url;

	} else {
		$redirect_url = empty( $redirect_url ) ? home_url() : $redirect_url;
	}
	wp_safe_redirect( $redirect_url );
	exit;
}
/**
 * This function returns status of 2nd factor
 *
 * @param object $user object containing user details.
 * @return string
 */
function momls_get_user_2ndfactor( $user ) {
	global $momlsdb_queries;

	$mo2f_user_email = $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $user->ID );
	$enduser         = new Momls_Two_Factor_Setup();
	$userinfo        = json_decode( $enduser->momls_get_userinfo( $mo2f_user_email ), true );
	if ( json_last_error() === JSON_ERROR_NONE ) {
		if ( 'ERROR' === $userinfo['status'] ) {
			$mo2f_second_factor = 'NONE';
		} elseif ( 'SUCCESS' === $userinfo['status'] ) {
			$mo2f_second_factor = $userinfo['authType'];
		} elseif ( 'FAILED' === $userinfo['status'] ) {
			$mo2f_second_factor = 'USER_NOT_FOUND';
		} else {
			$mo2f_second_factor = 'NONE';
		}
	} else {
		$mo2f_second_factor = 'NONE';
	}

	return $mo2f_second_factor;
}

/**
 * This function used to include css and js files.
 *
 * @return void
 */
function momls_echo_js_css_files() {
	wp_register_style( 'mo2f_style_settings', plugins_url( 'miniorange-login-security/includes/css/twofa_style_settings.min.css', dirname( dirname( __FILE__ ) ) ), array(), MO2F_VERSION );
	wp_print_styles( 'mo2f_style_settings' );

	wp_register_script( 'mo2f_bootstrap_js', plugins_url( 'miniorange-login-security/includes/js/bootstrap.min.js', dirname( dirname( __FILE__ ) ) ), array(), MO2F_VERSION, true );
	wp_print_scripts( 'jquery' );
	wp_print_scripts( 'mo2f_bootstrap_js' );
}
