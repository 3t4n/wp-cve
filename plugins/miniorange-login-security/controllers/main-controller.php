<?php
/**
 * This includes files according to the switch case.
 *
 * @package miniorange-login-security/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	global $momls_wpns_utility,$mo2f_dir_name;

	$controller = $mo2f_dir_name . 'controllers' . DIRECTORY_SEPARATOR;


if ( current_user_can( 'administrator' ) ) {

	include $controller . 'navbar.php';
	$current_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : null; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the tab name, doesn't require nonce verification.
	if ( isset( $current_page ) ) {
		switch ( $current_page ) {
			case 'mo_2fa_dashboard':
				include $controller . 'dashboard.php';
				break;
			case 'mo_2fa_account':
				include $controller . 'account.php';
				break;
			case 'mo_2fa_troubleshooting':
				include $controller . 'troubleshooting.php';
				break;
			case 'mo_2fa_two_fa':
				include $controller . 'twofa' . DIRECTORY_SEPARATOR . 'two-fa.php';
				break;
		}
		include $controller . 'support.php';
	}
} else {
	if ( ! is_null( $current_page ) ) {
		switch ( $current_page ) {
			case 'mo_2fa_two_fa':
				include_once $controller . 'twofa' . DIRECTORY_SEPARATOR . 'two-fa.php';
				break;
		}
	}
}

