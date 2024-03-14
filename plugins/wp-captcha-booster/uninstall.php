<?php
/**
 * This file contains code for remove tables and options at uninstall.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster
 * @version 1.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}
if ( ! current_user_can( 'manage_options' ) ) {
	return;
} else {
	global $wpdb;
	if ( is_multisite() ) {
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ); // db call ok; no-cache ok.
		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id ); // @codingStandardsIgnoreLine.
			$version = get_option( 'captcha_booster_version_number' );
			if ( false !== $version ) {
				$other_settings      = $wpdb->get_var(
					$wpdb->prepare(
						'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta
														 WHERE meta_key = %s ', 'other_settings'
					)
				); // db call ok; no-cache ok.
				$other_settings_data = maybe_unserialize( $other_settings );
				if ( esc_attr( $other_settings_data['remove_tables_at_uninstall'] ) === 'enable' ) {
					$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_booster' ); // @codingStandardsIgnoreLine.
					$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_booster_meta' ); // @codingStandardsIgnoreLine.
					$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_booster_ip_locations' ); // @codingStandardsIgnoreLine.
					// Delete options.
					delete_option( 'captcha_booster_version_number' );
					delete_option( 'captcha_option' );
					delete_option( 'cbo_admin_notice' );
				}
			}
			restore_current_blog();
		}
	} else {
		$captcha_booster_version_number = get_option( 'captcha_booster_version_number' );
		if ( false !== $captcha_booster_version_number ) {
			global $wp_version, $wpdb;
			$other_settings      = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta
														 WHERE meta_key = %s ', 'other_settings'
				)
			); // db call ok; no-cache ok.
			$other_settings_data = maybe_unserialize( $other_settings );

			if ( 'enable' === $other_settings_data['remove_tables_at_uninstall'] ) {


				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_booster' ); // @codingStandardsIgnoreLine.
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_booster_meta' ); // @codingStandardsIgnoreLine.
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'captcha_booster_ip_locations' ); // @codingStandardsIgnoreLine.

				delete_option( 'captcha_booster_version_number' );
				delete_option( 'captcha_option' );
				delete_option( 'cbo_admin_notice' );
			}
		}
	}
}
