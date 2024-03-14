<?php
/**
 * This file is used for unscheduling schedulers.
 *
 * @author Tech Banker
 * @package wp-captcha-bank/lib
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//exit if accessed directly
if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
	if ( wp_verify_nonce( $nonce_unblock_script, 'unblock_script' ) ) {
		if ( strstr( SCHEDULER_NAME, 'ip_address_unblocker_' ) ) {
			$meta_id = explode( 'ip_address_unblocker_', SCHEDULER_NAME );
		} else {
			$meta_id = explode( 'ip_range_unblocker_', SCHEDULER_NAME );
		}

		$where_parent       = array();
		$where              = array();
		$where_parent['id'] = $meta_id[1];
		$where['meta_id']   = $meta_id[1];

		$type = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT type FROM ' . $wpdb->prefix . 'captcha_bank WHERE id = %d', $meta_id[1]
			)
		); // db call ok; no-cache ok.

		if ( '' !== $type ) {
			$manage_ip = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_id=%d AND meta_key=%s', $meta_id[1], $type
				)
			); // db call ok; no-cache ok.

			$ip_address_data_array = maybe_unserialize( $manage_ip );

			$wpdb->delete( captcha_bank_parent(), $where_parent ); // db call ok; no-cache ok.
			$wpdb->delete( captcha_bank_meta(), $where ); // db call ok; no-cache ok.
			$obj_dbmailer_captcha_bank = new Dbmailer_Captcha_Bank();
			switch ( $type ) {
				case 'block_ip_range':
					$email_when_ip_range_is_unblocked              = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'alert_setup'
						)
					); // db call ok; no-cache ok.
					$email_when_ip_range_is_unblocked_unserialized = maybe_unserialize( $email_when_ip_range_is_unblocked );
					if ( 'enable' === $email_when_ip_range_is_unblocked_unserialized['email_when_an_ip_range_is_unblocked'] ) {
						$template_for_ip_range_unblocked = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'template_for_ip_range_unblocked'
							)
						); // db call ok; no-cache ok.

						$template_for_ip_range_unblocked_unserialized = maybe_unserialize( $template_for_ip_range_unblocked );
						$obj_dbmailer_captcha_bank->ip_range_mail_command_captcha_bank( $template_for_ip_range_unblocked_unserialized, $ip_address_data_array );
					}
					break;

				case 'block_ip_address':
					$email_when_address_unblocked = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'alert_setup'
						)
					); // db call ok; no-cache ok.

					$email_when_ip_address_unblocked_unserialized = maybe_unserialize( $email_when_address_unblocked );
					if ( 'enable' === $email_when_ip_address_unblocked_unserialized['email_when_an_ip_address_is_unblocked'] ) {
						$template_for_ip_address_unblocked = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'template_for_ip_address_unblocked'
							)
						); // db call ok; no-cache ok.

						$template_for_ip_address_unblocked_unserialized = maybe_unserialize( $template_for_ip_address_unblocked );
						$obj_dbmailer_captcha_bank->ip_address_mail_command_captcha_bank( $template_for_ip_address_unblocked_unserialized, $ip_address_data_array );
					}
					break;
			}
		}
		wp_unschedule_captcha_bank( SCHEDULER_NAME );
	}
}
