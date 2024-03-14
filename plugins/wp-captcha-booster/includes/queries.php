<?php
/**
 * This file is used for fetching data from database.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/includes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	foreach ( $user_role_permission as $permission ) {
		if ( current_user_can( $permission ) ) {
			$access_granted = true;
			break;
		}
	}
	if ( ! $access_granted ) {
		return;
	} else {
		$cpb_data_logs = array();

		if ( ! function_exists( 'get_captcha_booster_log_data_unserialize' ) ) {
			/**
			 * This function is used to fetch the unserialized data.
			 *
			 * @param string  $data .
			 * @param integer $start_date .
			 * @param integer $end_date .
			 */
			function get_captcha_booster_log_data_unserialize( $data, $start_date, $end_date ) {
				$array_details = array();
				foreach ( $data as $raw_row ) {
					$row = maybe_unserialize( $raw_row->meta_value );

					if ( $row['date_time'] >= $start_date && $row['date_time'] <= $end_date ) {
						array_push( $array_details, $row );
					}
				}
				return $array_details;
			}
		}

		if ( ! function_exists( 'get_captcha_booster_meta_data' ) ) {
			/**
			 * This function is used to fetch the unserialized data.
			 *
			 * @param string $meta_key .
			 */
			function get_captcha_booster_meta_data( $meta_key ) {
				global $wpdb;
				$data = $wpdb->get_var(
					$wpdb->prepare(
						'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key=%s', $meta_key
					)
				);// db call ok; no-cache ok.
				return maybe_unserialize( $data );
			}
		}

		$check_captcha_booster_wizard = get_option( 'captcha-booster-wizard-set-up' );
		if ( isset( $_GET['page'] ) ) {
			$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );// WPCS: CSRF ok,WPCS: input var ok.
		}
		$page_url = false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : $page;
		if ( isset( $_REQUEST['page'] ) ) { // WPCS: CSRF ok,WPCS: input var ok.
			switch ( $page_url ) {
				case 'cpb_captcha_booster':
					$meta_data_array = get_captcha_booster_meta_data( 'captcha_type' );
					break;

				case 'cpb_error_message':
					$error_messages_unserialize_data = get_captcha_booster_meta_data( 'error_message' );
					break;

				case 'cpb_display_settings':
					$display_settings_unserialized_data = get_captcha_booster_meta_data( 'display_settings' );
					$captcha_type_unserialized_data     = get_captcha_booster_meta_data( 'captcha_type' );
					break;

				case 'cpb_alert_setup':
					$meta_data_array = get_captcha_booster_meta_data( 'alert_setup' );
					break;

				case 'cpb_live_traffic':
					$live_traffic_data_unserialize = get_captcha_booster_meta_data( 'other_settings' );
					if ( 'enable' === $live_traffic_data_unserialize['live_traffic_monitoring'] ) {
						$end_date       = CAPTCHA_BOOSTER_LOCAL_TIME;
						$start_date     = $end_date - 60;
						$captcha_manage = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT * FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'visitor_logs_data'
							)
						);// db call ok; no-cache ok.
						$cpb_data_logs  = get_captcha_booster_log_data_unserialize( $captcha_manage, $start_date, $end_date );
					}
					break;

				case 'cpb_login_logs':
					$end_date       = CAPTCHA_BOOSTER_LOCAL_TIME + 86340;
					$start_date     = $end_date - 604380;
					$captcha_manage = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'recent_login_data'
						)
					);// db call ok; no-cache ok.
					$cpb_data_logs  = get_captcha_booster_log_data_unserialize( $captcha_manage, $start_date, $end_date );
					break;

				case 'cpb_visitor_logs':
					$visitor_logs_data_unserialize = get_captcha_booster_meta_data( 'other_settings' );
					if ( 'enable' === $visitor_logs_data_unserialize['visitor_logs_monitoring'] ) {
						$end_date       = CAPTCHA_BOOSTER_LOCAL_TIME + 86340;
						$start_date     = $end_date - 172640;
						$captcha_manage = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT * FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'visitor_logs_data'
							)
						);// db call ok; no-cache ok.
						$cpb_data_logs  = get_captcha_booster_log_data_unserialize( $captcha_manage, $start_date, $end_date );
					}
					break;

				case 'cpb_blocking_options':
					$blocking_options_unserialized_data = get_captcha_booster_meta_data( 'blocking_options' );
					break;

				case 'cpb_manage_ip_addresses':
					$end_date               = CAPTCHA_BOOSTER_LOCAL_TIME + 86340;
					$start_date             = $end_date - 2678340;
					$manage_ip              = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'block_ip_address'
						)
					);// db call ok; no-cache ok.
					$manage_ip_address_date = get_captcha_booster_log_data_unserialize( $manage_ip, $start_date, $end_date );
					break;

				case 'cpb_manage_ip_ranges':
					$end_date             = CAPTCHA_BOOSTER_LOCAL_TIME + 86340;
					$start_date           = $end_date - 2678340;
					$manage_range         = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'block_ip_range'
						)
					);// db call ok; no-cache ok.
					$manage_ip_range_date = get_captcha_booster_log_data_unserialize( $manage_range, $start_date, $end_date );
					break;

				case 'cpb_country_blocks':
					$country_data_array = get_captcha_booster_meta_data( 'country_blocks' );
					break;

				case 'cpb_other_settings':
					$meta_data_array = get_captcha_booster_meta_data( 'other_settings' );
					break;

				case 'cpb_roles_and_capabilities':
					$details_roles_capabilities = get_captcha_booster_meta_data( 'roles_and_capabilities' );
					$core_roles                 = array(
						'manage_options',
						'edit_plugins',
						'edit_posts',
						'publish_posts',
						'publish_pages',
						'edit_pages',
						'read',
					);
					$other_roles_array          = isset( $details_roles_capabilities['capabilities'] ) && '' !== $details_roles_capabilities['capabilities'] ? $details_roles_capabilities['capabilities'] : $core_roles;
					break;
			}
		}
	}
}
