<?php
/**
 * File updates options for premium features tab.
 *
 * @package miniorange-login-security/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	global $momls_wpns_utility,$mo2f_dir_name;
	$profile_url   = esc_url( add_query_arg( array( 'page' => 'mo_2fa_account' ), ( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ) ) );
	$two_fa        = esc_url( add_query_arg( array( 'page' => 'mo_2fa_two_fa' ), ( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ) ) );
	$dashboard_url = esc_url( add_query_arg( array( 'page' => 'mo_2fa_dashboard' ), ( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ) ) );
	$license_url   = 'https://plugins.miniorange.com/2-factor-authentication-for-wordpress-wp-2fa#pricing';
	$logo_url      = plugin_dir_url( dirname( __FILE__ ) ) . 'includes/images/miniorange_logo.png';
	$shw_feedback  = get_site_option( 'donot_show_feedback_message' ) ? false : true;

	$active_tab                  = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the tab name, doesn't require nonce verification.
	$hide_login_form_url         = plugin_dir_url( dirname( __FILE__ ) ) . 'includes/images/WP_hide_default_PL.png';
	$login_with_usename_only_url = plugin_dir_url( dirname( __FILE__ ) ) . 'includes/images/WP_default_login_PL.png';

	require $mo2f_dir_name . 'views' . DIRECTORY_SEPARATOR . 'navbar.php';
