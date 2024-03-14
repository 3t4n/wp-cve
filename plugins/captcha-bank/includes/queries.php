<?php
/**
 * This file is used for fetching data from database.
 *
 * @author  Tech Banker
 * @package captcha-bank/includes
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly.
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

		if ( ! function_exists( 'get_captcha_bank_log_data_unserialize' ) ) {
			/**
			 * This function is used to get data.
			 *
			 * @param string $data .
			 * @param string $start_date .
			 * @param string $end_date .
			 */
			function get_captcha_bank_log_data_unserialize( $data, $start_date, $end_date ) {
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

		if ( ! function_exists( 'get_captcha_bank_meta_data' ) ) {
			/**
			 * This function is used to get meta data.
			 *
			 * @param string $meta_key .
			 */
			function get_captcha_bank_meta_data( $meta_key ) {
				global $wpdb;
				$data = $wpdb->get_var(
					$wpdb->prepare(
						'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', $meta_key
					)
				);// db call ok; no-cache ok.
				return maybe_unserialize( $data );
			}
		}
		if ( isset( $_GET['page'] ) ) {
			$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );// WPCS: CSRF ok,WPCS: input var ok.
		}
		$check_captcha_bank_wizard = get_option( 'captcha-bank-wizard-set-up' );
		$licensing_url             = false === $check_captcha_bank_wizard ? 'captcha_bank_wizard' : $page;
		if ( isset( $_REQUEST['page'] ) ) { // WPCS: CSRF ok, input var ok.
			switch ( $licensing_url ) {
				case 'captcha_bank':
					$meta_data_array                  = get_captcha_bank_meta_data( 'captcha_type' );
					$meta_data_display_settings_array = get_captcha_bank_meta_data( 'display_settings' );
					break;

				case 'captcha_bank_message_settings':
					$error_messages_unserialize_data = get_captcha_bank_meta_data( 'error_message' );
					break;

				case 'captcha_bank_whitelist_ip_addresses':
					global $wpdb;
						$data = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT * FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'whitelist_ip_addresses'
							)
						);// db call ok; no-cache ok.
					break;

				case 'captcha_bank_notifications_setup':
					$meta_data_array = get_captcha_bank_meta_data( 'alert_setup' );
					break;

				case 'captcha_bank_blockage_settings':
					$blocking_options_unserialized_data = get_captcha_bank_meta_data( 'blocking_options' );
					break;

				case 'captcha_bank_block_unblock_ip_addresses':
					$end_date               = CAPTCHA_BANK_LOCAL_TIME + 86340;
					$start_date             = $end_date - 2678340;
					$manage_ip              = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'block_ip_address'
						)
					);// db call ok; no-cache ok.
					$manage_ip_address_date = get_captcha_bank_log_data_unserialize( $manage_ip, $start_date, $end_date );
					$manage_range           = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'block_ip_range'
						)
					);// db call ok; no-cache ok.
					$manage_ip_range_date   = get_captcha_bank_log_data_unserialize( $manage_range, $start_date, $end_date );
					break;

				case 'captcha_bank_block_unblock_countries':
					$country_data_array = get_captcha_bank_meta_data( 'country_blocks' );
					break;

				case 'captcha_bank_other_settings':
					$meta_data_array = get_captcha_bank_meta_data( 'other_settings' );
					break;

				case 'captcha_bank_roles_capabilities':
					$details_roles_capabilities = get_captcha_bank_meta_data( 'roles_and_capabilities' );
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
