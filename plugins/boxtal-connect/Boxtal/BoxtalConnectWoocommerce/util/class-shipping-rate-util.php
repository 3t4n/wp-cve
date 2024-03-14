<?php
/**
 * Contains code for shipping rate util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

/**
 * Shipping rate util class.
 *
 * Helper to manage consistency between woocommerce versions shipping rate getters and setters.
 */
class Shipping_Rate_Util {

	/**
	 * Get shipping method settings from shipping rate.
	 *
	 * @param \WC_Shipping_Rate $rate woocommerce shipping rate.
	 * @return array $settings shipping rate settings
	 */
	public static function get_settings( $rate ) {
		return get_option( self::get_settings_key( $rate ) );
	}

	/**
	 * Get shipping method settings key from shipping rate.
	 *
	 * @param \WC_Shipping_Rate $rate woocommerce shipping rate.
	 * @return string $settings_key shipping rate settings key
	 */
	private static function get_settings_key( $rate ) {
		if ( false === strpos( self::get_id( $rate ), ':' ) ) {
			return null;
		}
		list($method_name, $method_instance_id) = explode( ':', self::get_id( $rate ) );
		return 'woocommerce_' . $method_name . '_' . $method_instance_id . '_settings';
	}

	/**
	 * Get id.
	 *
	 * @param \WC_Shipping_Rate $rate woocommerce shipping rate.
	 *
	 * @return string $id shipping rate id
	 */
	public static function get_id( $rate ) {
		if ( method_exists( $rate, 'get_id' ) ) {
			return $rate->get_id();
		}
		return $rate->id;
	}

	/**
	 * Get method id.
	 *
	 * @param \WC_Shipping_Rate $rate woocommerce shipping rate.
	 *
	 * @return string $id shipping rate method id
	 */
	public static function get_method_id( $rate ) {
		if ( method_exists( $rate, 'get_method_id' ) ) {
			return $rate->get_method_id();
		}
		return $rate->method_id;
	}

	/**
	 * Get clean id (without :).
	 *
	 * @param string $id woocommerce shipping rate id.
	 * @return string $id shipping rate id without :
	 */
	public static function get_clean_id( $id ) {
		return str_replace( ':', '', $id );
	}
}
