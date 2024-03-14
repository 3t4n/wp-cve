<?php
/**
 * Helpers.
 *
 * @package AdvancedAds
 * @author  Advanced Ads <info@wpadvancedads.com>
 * @since   1.48.0
 */

namespace AdvancedAds\Modules\OneClick;

use AdvancedAds\Utilities\Str;
use AdvancedAds\Utilities\WordPress;

defined( 'ABSPATH' ) || exit;

/**
 * Helpers.
 */
class Helpers {

	/**
	 * Is module enabled.
	 *
	 * @param string $name name of module.
	 *
	 * @return bool
	 */
	public static function is_module_enabled( $name ): bool {
		return boolval( get_option( "pubguru_module_{$name}" ) );
	}

	/**
	 * Check if config has traffic cop subscription
	 *
	 * @param array $config Config instance.
	 *
	 * @return bool
	 */
	public static function has_traffic_cop( $config = null ): bool {
		if ( null === $config ) {
			$config = Options::pubguru_config();
		}

		if (
			isset( $config['params'] ) &&
			( isset( $config['params']['trafficCopIvtAction'] ) && 'block' === $config['params']['trafficCopIvtAction'] ) &&
			( isset( $config['params']['trafficCopTestPercent'] ) && $config['params']['trafficCopTestPercent'] > 0.01 )
		) {
			return true;
		}

		return false;
	}

	/**
	 * Get config file
	 *
	 * Cases
	 *  1. For mobile prefix => pg.mobile
	 *  2. For desktop prefix => pg.desktop
	 *  3. If none of the prefix is found than go with the first one
	 *
	 * @return bool|string
	 */
	public static function get_config_file() {
		static $pubguru_config_name;

		if ( null !== $pubguru_config_name ) {
			return $pubguru_config_name;
		}

		$pubguru_config_name = false;
		$configs             = Options::pubguru_config();

		if ( ! isset( $configs['configs'] ) || empty( $configs['configs'] ) ) {
			$domain = WordPress::get_site_domain( 'name' );
			return "pg.{$domain}.js";
		}

		$pubguru_config_name = wp_is_mobile() ? self::config_contains( 'mobile' ) : self::config_contains( 'desktop' );
		$pubguru_config_name = false !== $pubguru_config_name ? $pubguru_config_name : $configs['configs'][0]['name'];

		return $pubguru_config_name;
	}

	/**
	 * Find config name by needle
	 *
	 * @param string $needle Needle to look into config name.
	 *
	 * @return bool|string
	 */
	private static function config_contains( $needle ) {
		$configs = Options::pubguru_config();

		foreach ( $configs['configs'] as $config ) {
			if ( Str::str_contains( $needle, $config['name'] ) ) {
				return $config['name'];
			}
		}

		return false;
	}

	/**
	 * Start auto ad creation
	 *
	 * @return void
	 */
	public static function start_auto_ad_creation(): void {
		$ads = self::get_ads_from_config();

		if ( $ads && ! wp_next_scheduled( 'advanced-ads-pghb-auto-ad-creation' ) ) {
			wp_schedule_single_event( current_datetime()->getTimestamp() + MINUTE_IN_SECONDS, 'advanced-ads-pghb-auto-ad-creation' );
		}
	}

	/**
	 * Get ads from saved config.
	 *
	 * @return bool|array
	 */
	public static function get_ads_from_config() {
		static $pubguru_config_ads;
		$config = Options::pubguru_config();

		if ( null !== $pubguru_config_ads ) {
			return $pubguru_config_ads;
		}

		$pubguru_config_ads = false;

		if ( isset( $config['configs'][0]['ad_units'] ) && ! empty( $config['configs'][0]['ad_units'] ) ) {
			$pubguru_config_ads = [];

			foreach ( $config['configs'] as $config ) {
				foreach ( $config['ad_units'] as $ad_id => $ad ) {
					$pubguru_config_ads[ $ad_id ] = $ad;
				}
			}
		}

		return $pubguru_config_ads;
	}

	/**
	 * Is ad disabled on page
	 *
	 * @param int $post_id Post id to check for.
	 *
	 * @return bool
	 */
	public static function is_ad_disabled( $post_id = 0 ): bool {
		global $post;

		if ( ! $post_id ) {
			$post_id = $post->ID;
		}

		$settings = get_post_meta( $post_id, '_advads_ad_settings', true );

		return is_singular() ? ! empty( $settings['disable_ads'] ) : false;
	}
}
