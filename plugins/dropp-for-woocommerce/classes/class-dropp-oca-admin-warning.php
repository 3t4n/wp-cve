<?php
/**
 * Dropp
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp;

use Dropp\Utility\Zone_Utility;

/**
 * Dropp
 */
class Dropp_Oca_Admin_Warning {

	/**
	 * Setup
	 */
	public static function setup(): void {
		add_action( 'admin_notices', __CLASS__ . '::admin_notice' );
	}

	public static function admin_notice(): void {
		$screen = get_current_screen();
		if ( 'woocommerce_page_wc-settings' !== $screen->id ) {
			return;
		}
		self::check();
	}

	public static function dropp_check( $shipping_method, $zone, $zone_data ): void {
		if ( ! $shipping_method->enabled ) {
			return;
		}
		$present = false;
		$enabled = false;
		foreach ( $zone_data['shipping_methods'] as $shipping_method ) {
			if ( 'dropp_is_oca' === $shipping_method->id ) {
				$enabled = ( $enabled ? $enabled : ( $shipping_method->enabled === 'yes' ) );
				$present = true;
			}
		}
		if ( $present && $enabled ) {
			return;
		}
		self::warn( $zone, esc_html__( "You have enabled Dropp for zone, %s, but you have not enabled Dropp Outside Capital Area. This can result in Dropp becoming unavailable if a location that is outside the capital area is selected." ) );
	}

	public static function dropp_oca_check( $shipping_method, $zone, $zone_data ): void {
		if ( ! $shipping_method->enabled ) {
			return;
		}
		$present = false;
		$enabled = false;
		foreach ( $zone_data['shipping_methods'] as $shipping_method ) {
			if ( 'dropp_is' === $shipping_method->id ) {
				$enabled = ( $enabled ? $enabled : ( $shipping_method->enabled === 'yes' ) );
				$present = true;
			}
		}
		if ( $present && $enabled ) {
			return;
		}
		self::warn( $zone, esc_html__( "You have enabled Dropp Outside Capital Area for zone, %s, but you have not enabled Dropp. It will not work without it." ) );
	}

	public static function warn( $zone, $message ): void {
		$message = sprintf( $message, $zone->get_zone_name() );
		$link    = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'admin.php?page=wc-settings&tab=shipping&zone_id=' . $zone->get_id() ),
			esc_html__( 'Click here to go to the zone settings.' )
		);
		$format  = '<div class="notice notice-error is-dismissible"><p>%s %s</p></div>';
		echo sprintf(
			$format,
			$message,
			$link
		);
	}

	public static function check(): void {
		$zones = Zone_Utility::get_zones();
		foreach ( $zones as $zone_data ) {
			$zone = \WC_Shipping_Zones::get_zone( $zone_data['zone_id'] );
			foreach ( $zone_data['shipping_methods'] as $shipping_method ) {
				if ( 'dropp_is' === $shipping_method->id ) {
					self::dropp_check( $shipping_method, $zone, $zone_data );
				}
				if ( 'dropp_is_oca' === $shipping_method->id ) {
					self::dropp_oca_check( $shipping_method, $zone, $zone_data );
				}
			}
		}
	}
}
