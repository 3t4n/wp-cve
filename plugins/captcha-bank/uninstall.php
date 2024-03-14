<?php
/**
 * This file contains code for remove tables and options at uninstall.
 *
 * @author  Tech Banker
 * @package captcha-bank
 * @version 3.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
} else {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	} else {
		global $wpdb;
		if ( is_multisite() ) {
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );// db call ok; no-cache ok.
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );// @codingStandardsIgnoreLine.
				$version = get_option( 'captcha-bank-version-number' );
				if ( false !== $version ) {
					global $wp_version, $wpdb;
					$other_settings      = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta
							WHERE meta_key = %s ', 'other_settings'
						)
					);// db call ok; no-cache ok.
					$other_settings_data = maybe_unserialize( $other_settings );

					if ( 'enable' === esc_attr( $other_settings_data['remove_tables_at_uninstall'] ) ) {

						$block_unblock_ip_range_and_address_scheduled = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT * FROM ' . $wpdb->prefix . 'captcha_bank' .
										' WHERE type IN(%s, %s) ', 'block_ip_address', 'block_ip_range'
							)
						);// db call ok; no-cache ok.
						if ( count( $block_unblock_ip_range_and_address_scheduled ) > 0 ) {
							foreach ( $block_unblock_ip_range_and_address_scheduled as $value ) {
								if ( 'block_ip_address' === $value->type ) {
									if ( wp_next_scheduled( 'ip_address_unblocker_' . $value->id ) ) {
										wp_clear_scheduled_hook( 'ip_address_unblocker_' . $value->id );
									}
								} elseif ( 'block_ip_range' === $value->type ) {
									if ( wp_next_scheduled( 'ip_range_unblocker_' . $value->id ) ) {
										wp_clear_scheduled_hook( 'ip_range_unblocker_' . $value->id );
									}
								}
							}
						}
						$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_bank' );// @codingStandardsIgnoreLine.
						$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_bank_meta' );// @codingStandardsIgnoreLine.
						$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_bank_ip_locations' );// @codingStandardsIgnoreLine.

						delete_option( 'captcha-bank-version-number' );
						delete_option( 'captcha_option' );
						delete_option( 'cpb_admin_notice' );
						delete_option( 'captcha-bank-wizard-set-up' );
					}
				}
				restore_current_blog();
			}
		} else {
			$version = get_option( 'captcha-bank-version-number' );
			if ( false !== $version ) {
				global $wp_version, $wpdb;
				$other_settings      = $wpdb->get_var(
					$wpdb->prepare(
						'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta
						WHERE meta_key = %s ', 'other_settings'
					)
				);// db call ok; no-cache ok.
				$other_settings_data = maybe_unserialize( $other_settings );

				if ( 'enable' === esc_attr( $other_settings_data['remove_tables_at_uninstall'] ) ) {

					$block_unblock_ip_range_and_address_scheduled = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'captcha_bank' .
									' WHERE type IN(%s, %s) ', 'block_ip_address', 'block_ip_range'
						)
					);// db call ok; no-cache ok.

					if ( count( $block_unblock_ip_range_and_address_scheduled ) > 0 ) {
						foreach ( $block_unblock_ip_range_and_address_scheduled as $value ) {
							if ( 'block_ip_address' === $value->type ) {
								if ( wp_next_scheduled( 'ip_address_unblocker_' . $value->id ) ) {
									wp_clear_scheduled_hook( 'ip_address_unblocker_' . $value->id );
								}
							} elseif ( 'block_ip_range' === $value->type ) {
								if ( wp_next_scheduled( 'ip_range_unblocker_' . $value->id ) ) {
									wp_clear_scheduled_hook( 'ip_range_unblocker_' . $value->id );
								}
							}
						}
					}
					$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_bank' );// @codingStandardsIgnoreLine.
					$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_bank_meta' );// @codingStandardsIgnoreLine.
					$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_bank_ip_locations' );// @codingStandardsIgnoreLine.

					delete_option( 'captcha-bank-version-number' );
					delete_option( 'captcha_option' );
					delete_option( 'cpb_admin_notice' );
				}
			}
		}
	}
}
