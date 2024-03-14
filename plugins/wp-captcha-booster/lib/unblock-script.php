<?php
/**
 * This file is used for unscheduling schedulers.
 *
 * @author Tech Banker
 * @package wp-captcha-booster/lib
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
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
				'SELECT type FROM ' . $wpdb->prefix . 'captcha_booster WHERE id=%d', $meta_id[1]
			)
		);// db call ok; no-cache ok.

		if ( '' !== $type ) {
			$manage_ip = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_id=%d AND meta_key=%s', $meta_id[1], $type
				)
			);// WPCS: db call ok; no-cache ok.

			$ip_address_data_array = maybe_unserialize( $manage_ip );

			$wpdb->delete( captcha_booster(), $where_parent ); // WPCS: db call ok; no-cache ok.
			$wpdb->delete( captcha_booster_meta(), $where ); // WPCS: db call ok; no-cache ok.
		}
		wp_unschedule_captcha_booster( SCHEDULER_NAME );
	}
}
