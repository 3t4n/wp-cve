<?php
/**
 * This file contains user's login details code.
 *
 * @author  Tech Banker
 * @package captcha-bank/includes
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly.
if ( ! function_exists( 'captcha_bank_user_log_in_fails' ) ) {
	/**
	 * This function is used to create entry when user fails to log in.
	 *
	 * @param string $username .
	 */
	function captcha_bank_user_log_in_fails( $username ) {
		$obj_dbmailer_captcha_bank = new Dbmailer_Captcha_Bank();
		global $wpdb, $alert_setup_data_array;
		$ip         = get_ip_address_for_captcha_bank();
		$ip_address = '::1' === $ip ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $ip ) );
		$get_ip     = get_ip_location_captcha_bank( long2ip_captcha_bank( $ip_address ) );
		if ( ! captcha_bank_smart_ip_detect_crawler() ) {
			$logs_parent_id                 = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT id FROM ' . $wpdb->prefix . 'captcha_bank WHERE type=%s', 'logs'
				)
			);// db call ok; no-cache ok.
			$insert_user_login              = array();
			$insert_user_login['type']      = 'login_log';
			$insert_user_login['parent_id'] = $logs_parent_id;
			$wpdb->insert( captcha_bank_parent(), $insert_user_login );// db call ok; no-cache ok.
			$last_id = $wpdb->insert_id;

			$insert_user_login                    = array();
			$insert_user_login['username']        = $username;
			$insert_user_login['user_ip_address'] = $ip_address;
			$insert_user_login['resources']       = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';// WPCS: input var ok, sanitization ok.
			$insert_user_login['http_user_agent'] = isset( $_SERVER['HTTP_USER_AGENT'] ) ? wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) : '';// @codingStandardsIgnoreLine
			if ('' == $get_ip->country_name && '' == $get_ip->city)
			{
				$location = '';
			}
			else if ('' == $get_ip->country_name)
			{
				$location = '';
			}
			else if ('' == $get_ip->city)
			{
				$location = $get_ip->country_name;
			}
			else
			{
				$location = $get_ip->city . ', ' . $get_ip->country_name;
			}
			$insert_user_login['location']        = $location;
			$insert_user_login['latitude']        = $get_ip->latitude;
			$insert_user_login['longitude']       = $get_ip->longitude;
			$insert_user_login['date_time']       = CAPTCHA_BANK_LOCAL_TIME;
			$insert_user_login['status']          = 'Failure';
			$insert_user_login['meta_id']         = $last_id;
			$insert_data                          = array();
			$insert_data['meta_id']               = $last_id;
			$insert_data['meta_key']              = 'recent_login_data';// WPCS: slow query ok.
			$insert_data['meta_value']            = maybe_serialize( $insert_user_login );// WPCS: slow query ok.
			$wpdb->insert( captcha_bank_meta(), $insert_data );// db call ok; no-cache ok.
		}
		$alert_setup            = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'alert_setup'
			)
		);// db call ok; no-cache ok.
		$alert_setup_data_array = maybe_unserialize( $alert_setup );
		if ( 'enable' === $alert_setup_data_array['email_when_a_user_fails_login'] ) {
			$template_failure            = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT  meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'template_for_user_failure'
				)
			);// db call ok; no-cache ok.
			$template_failure_data_array = maybe_unserialize( $template_failure );
			$obj_dbmailer_captcha_bank->login_mail_command_captcha_bank( $template_failure_data_array, $username );
		}

		if ( ! function_exists( 'get_user_data_remove_unwanted_users' ) ) {
			/**
			 * This function is used to remove unwanted users.
			 *
			 * @param string $data .
			 * @param string $date .
			 * @param string $blocked_time .
			 * @param string $ip_address .
			 */
			function get_user_data_remove_unwanted_users( $data, $date, $blocked_time, $ip_address ) {
				$array_details = array();
				foreach ( $data as $raw_row ) {
					$row = maybe_unserialize( $raw_row->meta_value );
					if ( $ip_address === $row['user_ip_address'] ) {
						if ( 'permanently' !== $blocked_time ) {
							if ( 'Failure' === $row['status'] && $row['date_time'] + $blocked_time >= $date ) {
								array_push( $array_details, $row );
							}
						} else {
							if ( 'Failure' === $row['status'] ) {
								array_push( $array_details, $row );
							}
						}
					}
				}
				return $array_details;
			}
		}

		$blocking_options_data              = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'blocking_options'
			)
		);// db call ok; no-cache ok.
		$blocking_options_unserialized_data = maybe_unserialize( $blocking_options_data );
		if ( 'enable' === $blocking_options_unserialized_data['auto_ip_block'] ) {
			$get_ip   = get_ip_location_captcha_bank( long2ip_captcha_bank( $ip_address ) );
			if ('' == $get_ip->country_name && '' == $get_ip->city)
			{
				$location = '';
			}
			else if ('' == $get_ip->country_name)
			{
				$location = '';
			}
			else if ('' == $get_ip->city)
			{
				$location = $get_ip->country_name;
			}
			else
			{
				$location = $get_ip->city . ', ' . $get_ip->country_name;
			}

			$date              = CAPTCHA_BANK_LOCAL_TIME;
			$get_all_user_data = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM ' . $wpdb->prefix . 'captcha_bank_meta
					WHERE meta_key= %s', 'recent_login_data'
				)
			);// db call ok; no-cache ok.

			$blocked_for_time = $blocking_options_unserialized_data['block_for_time'];

			switch ( $blocked_for_time ) {
				case '1Hour':
					$this_time = 60 * 60;
					break;

				case '12Hour':
					$this_time = 12 * 60 * 60;
					break;

				case '24hours':
					$this_time = 24 * 60 * 60;
					break;

				case '48hours':
					$this_time = 2 * 24 * 60 * 60;
					break;

				case 'week':
					$this_time = 7 * 24 * 60 * 60;
					break;

				case 'month':
					$this_time = 30 * 24 * 60 * 60;
					break;

				case 'permanently':
					$this_time = 'permanently';
					break;
			}

			$user_data = COUNT( get_user_data_remove_unwanted_users( $get_all_user_data, $date, $this_time, $ip_address ) );
			if ( ! defined( 'CPB_COUNT_LOGIN_STATUS' ) ) {
				define( 'CPB_COUNT_LOGIN_STATUS', $user_data );
			}
			if ( $user_data >= $blocking_options_unserialized_data['maximum_login_attempt_in_a_day'] ) {
				$ip_address_parent_id = $wpdb->get_var(
					$wpdb->prepare(
						'SELECT id FROM ' . $wpdb->prefix . 'captcha_bank WHERE type=%s', 'advance_security'
					)
				);// db call ok; no-cache ok.

				$ip_address_block              = array();
				$ip_address_block['type']      = 'block_ip_address';
				$ip_address_block['parent_id'] = $ip_address_parent_id;
				$wpdb->insert( captcha_bank_parent(), $ip_address_block );// db call ok; no-cache ok.
				$last_id = $wpdb->insert_id;

				$ip_address_block_meta                = array();
				$ip_address_block_meta['ip_address']  = $ip_address;
				$ip_address_block_meta['blocked_for'] = $blocked_for_time;
				$ip_address_block_meta['location']    = $location;
				$ip_address_block_meta['comments']    = 'IP ADDRESS AUTOMATIC BLOCKED!';
				$ip_address_block_meta['date_time']   = CAPTCHA_BANK_LOCAL_TIME;
				$ip_address_block_meta['meta_id']     = $last_id;

				$insert_data             = array();
				$insert_data['meta_id']  = $last_id;
				$insert_data['meta_key'] = 'block_ip_address';// WPCS: slow query ok.

				$insert_data['meta_value'] = maybe_serialize( $ip_address_block_meta );// WPCS: slow query ok.

				$wpdb->insert( captcha_bank_meta(), $insert_data );// db call ok; no-cache ok.

				if ( 'permanently' !== $blocked_for_time ) {
					$cron_name = 'ip_address_unblocker_' . $last_id;
					wp_schedule_captcha_bank( $cron_name, $blocked_for_time );
				}

				$email_when_ip_address_blocked = $wpdb->get_var(
					$wpdb->prepare(
						'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'alert_setup'
					)
				);// db call ok; no-cache ok.

				$email_when_ip_address_blocked_unserialized = maybe_unserialize( $email_when_ip_address_blocked );
				if ( 'enable' === $email_when_ip_address_blocked_unserialized['email_when_an_ip_address_is_blocked'] ) {
					$template_for_ip_address_blocked = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'template_for_ip_address_blocked'
						)
					);// db call ok; no-cache ok.
					$meta_data_array                 = maybe_unserialize( $template_for_ip_address_blocked );
					$obj_dbmailer_captcha_bank->ip_address_mail_command_captcha_bank( $meta_data_array, $ip_address_block_meta );
				}
				$error_data                       = $wpdb->get_var(
					$wpdb->prepare(
						'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'error_message'
					)
				);// db call ok; no-cache ok.
				$error_messages_unserialized_data = maybe_unserialize( $error_data );
				$replace_address                  = str_replace( '[ip_address]', long2ip_captcha_bank( $ip_address ), htmlspecialchars_decode( $error_messages_unserialized_data['for_blocked_ip_address_error'] ) );
				wp_die( $replace_address );// WPCS: XSS ok.

			}
			add_filter( 'login_errors', 'login_error_messages_captcha_bank', 10, 1 );
		}
	}
}
if ( ! function_exists( 'captcha_bank_user_log_in_success' ) ) {
	/**
	 * This function is used to create entry when user logged in successfully.
	 *
	 * @param string $username .
	 */
	function captcha_bank_user_log_in_success( $username ) {
		$obj_dbmailer_captcha_bank = new Dbmailer_Captcha_Bank();
		global $wpdb, $alert_setup_data_array;
		$ip         = get_ip_address_for_captcha_bank();
		$ip_address = '::1' === $ip ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $ip ) );
		$get_ip     = get_ip_location_captcha_bank( long2ip_captcha_bank( $ip_address ) );
		if ( ! captcha_bank_smart_ip_detect_crawler() ) {
			$logs_parent_id                 = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT id FROM ' . $wpdb->prefix . 'captcha_bank WHERE type=%s', 'logs'
				)
			);// db call ok; no-cache ok.
			$insert_user_login              = array();
			$insert_user_login['type']      = 'login_log';
			$insert_user_login['parent_id'] = $logs_parent_id;
			$wpdb->insert( captcha_bank_parent(), $insert_user_login );// db call ok; no-cache ok.

			$last_id = $wpdb->insert_id;

			$insert_user_login                    = array();
			$insert_user_login['username']        = $username;
			$insert_user_login['user_ip_address'] = $ip_address;
			$insert_user_login['resources']       = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';// WPCS: input var ok, sanitization ok.
			$insert_user_login['http_user_agent'] = isset( $_SERVER['HTTP_USER_AGENT'] ) ? wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) : '';// @codingStandardsIgnoreLine
			if ('' == $get_ip->country_name && '' == $get_ip->city)
			{
				$location = '';
			}
			else if ('' == $get_ip->country_name)
			{
				$location = '';
			}
			else if ('' == $get_ip->city)
			{
				$location = $get_ip->country_name;
			}
			else
			{
				$location = $get_ip->city . ', ' . $get_ip->country_name;
			}
			$insert_user_login['location']        = $location;
			$insert_user_login['latitude']        = $get_ip->latitude;
			$insert_user_login['longitude']       = $get_ip->longitude;
			$insert_user_login['date_time']       = CAPTCHA_BANK_LOCAL_TIME;
			$insert_user_login['status']          = 'Success';
			$insert_user_login['meta_id']         = $last_id;

			$insert_data               = array();
			$insert_data['meta_id']    = $last_id;
			$insert_data['meta_key']   = 'recent_login_data';// WPCS: slow query ok.
			$insert_data['meta_value'] = maybe_serialize( $insert_user_login );// WPCS: slow query ok.
			$wpdb->insert( captcha_bank_meta(), $insert_data );// db call ok; no-cache ok.
		}
		$alert_setup            = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'alert_setup'
			)
		);// db call ok; no-cache ok.
		$alert_setup_data_array = maybe_unserialize( $alert_setup );
		if ( 'enable' === $alert_setup_data_array['email_when_a_user_success_login'] ) {
			$template_success            = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'template_for_user_success'
				)
			);// db call ok; no-cache ok.
			$template_success_data_array = maybe_unserialize( $template_success );
			if ( empty( $template_success_data_array['send_to'] ) ) {
				$template_success_data_array['send_to'] = get_bloginfo( 'admin_email' );
			}
			$obj_dbmailer_captcha_bank->login_mail_command_captcha_bank( $template_success_data_array, $username );
		}
	}
}
if ( ! function_exists( 'captcha_bank_check_user_login_status' ) ) {
	/**
	 * This function is used to call the functions captcha_bank_user_log_in_fails and captcha_bank_user_log_in_success.
	 *
	 * @param string $username .
	 * @param string $password .
	 */
	function captcha_bank_check_user_login_status( $username, $password ) {
		$userdata = get_user_by( 'login', $username );
		if ( $userdata && wp_check_password( $password, $userdata->user_pass ) ) {
			captcha_bank_user_log_in_success( $username );
		} else {
			if ( '' === $username && '' === $password ) {
				return;
			} else {
				captcha_bank_user_log_in_fails( $username );
			}
		}
	}
}
if ( ! function_exists( 'login_error_messages_captcha_bank' ) ) {
	/**
	 * This function is used to return the login attempts error message.
	 *
	 * @param string $default_error_message .
	 */
	function login_error_messages_captcha_bank( $default_error_message ) {
		global $wpdb;

		$blocking_options_data              = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'blocking_options'
			)
		);// db call ok; no-cache ok.
		$blocking_options_unserialized_data = maybe_unserialize( $blocking_options_data );

		$error_message_login_attempts              = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'error_message'
			)
		);// db call ok; no-cache ok.
		$error_message_login_attempts_unserialized = maybe_unserialize( $error_message_login_attempts );
		$login_attempts                            = $blocking_options_unserialized_data['maximum_login_attempt_in_a_day'] - CPB_COUNT_LOGIN_STATUS;
		$replace_login_attempts                    = str_replace( '[maxAttempts]', $login_attempts, $error_message_login_attempts_unserialized['for_login_attempts_error'] );
		$display_error_message                     = $default_error_message . ' ' . $replace_login_attempts;

		return $display_error_message;
	}
}
	/**
	 * This function is used to returns the version of active plugins.
	 *
	 * @param string $plugin .
	 */
function captcha_bank_plugin_get_version( $plugin ) {
	$plugin_data    = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
	$plugin_version = $plugin_data['Version'];
	return $plugin_version;
}
