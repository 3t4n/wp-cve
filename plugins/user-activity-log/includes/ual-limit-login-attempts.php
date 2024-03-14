<?php
/**
 * Limit Login Attempt Support.
 *
 * @package User Activity Log
 */

/*
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Logger for the (old but still) very popular plugin Limit Login Attempts
 * https://wordpress.org/plugins/limit-login-attempts/
 */

if ( ! function_exists( 'ual_load_settings_page' ) ) {
	/**
	 * Fired when plugin options screen is loaded.
	 *
	 * @param string $a Page.
	 */
	function ual_load_settings_page( $a ) {
		$data  = isset( $_POST ) ? sanitize_text_field( wp_unslash( $_POST ) ) : '';
		$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
		if ( $data && wp_verify_nonce( $nonce, 'limit-login-attempts-options' ) ) {

			$action   = 'Updated Options';
			$obj_type = 'Settings';
			$post_id  = '';

			if ( isset( $_POST['clear_log'] ) ) {
				$post_title = 'Limit Login Attempts : Cleared IP log';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			}
			if ( isset( $_POST['reset_total'] ) ) {
				$post_title = 'Limit Login Attempts : Reseted lockout count';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			}
			if ( isset( $_POST['reset_current'] ) ) {
				$post_title = 'Limit Login Attempts : Cleared current lockouts';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			}
			if ( isset( $_POST['update_options'] ) ) {
				$post_title = 'Limit Login Attempts : Option Settings Updated';
				ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
			}
		}
	}
}
add_action( 'load-settings_page_limit-login-attempts', 'ual_load_settings_page', 10, 1 );

if ( ! function_exists( 'ual_option_limit_login_lockouts_total' ) ) {
	/**
	 * Fired when plugin options lockouts.
	 *
	 * @param string $value Value.
	 */
	function ual_option_limit_login_lockouts_total( $value ) {
		global $limit_login_just_lockedout;

		if ( ! $limit_login_just_lockedout ) {
			return $value;
		}

		$ip          = limit_login_get_address();
		$whitelisted = is_limit_login_ip_whitelisted( $ip );

		$retries = get_option( 'limit_login_retries' );
		if ( ! is_array( $retries ) ) {
			$retries = array();
		}
		$lockout_type = '';
		if ( ! isset( $retries[ $ip ] ) ) {
			/* longer lockout. */
			$lockout_type = 'longer';
			$count        = limit_login_option( 'allowed_retries' ) * limit_login_option( 'allowed_lockouts' );
			$lockouts     = limit_login_option( 'allowed_lockouts' );
			$time         = round( limit_login_option( 'long_duration' ) / 3600 );
		} else {
			/* normal lockout. */
			$lockout_type = 'normal';
			$count        = $retries[ $ip ];
			$lockouts     = floor( $count / limit_login_option( 'allowed_retries' ) );
			$time         = round( limit_login_option( 'lockout_duration' ) / 60 );
		}
		if ( $whitelisted ) {
			$post_title = ' Limit Login Attempts : Failed login attempt from whitelisted IP';
		} else {

			$post_title = ' Limit Login Attempts : Was locked out because too many failed login attempts';
		}

		$action   = 'Updated Options';
		$obj_type = 'Settings';
		$post_id  = '';

		$uactid = ual_get_activity_function( $action, $obj_type, $post_id, $post_title );
	}
}

add_filter( 'pre_option_limit_login_lockouts_total', 'ual_option_limit_login_lockouts_total', 10, 1 );
