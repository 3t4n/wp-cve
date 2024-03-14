<?php
/**
 * Contains code for misc util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

use Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Parcel_Point\Controller;
use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Misc util class.
 *
 * Miscellaneous util functions.
 */
class Misc_Util {
	/**
	 * Return value if not empty, null otherwise.
	 *
	 * @param mixed $value value to be checked.
	 * @return mixed $value
	 */
	public static function not_empty_or_null( $value ) {
		return '' === $value ? null : $value;
	}

	/**
	 * Return floatval if value is not empty, null otherwise.
	 *
	 * @param mixed $value value to be checked.
	 * @return mixed $value
	 */
	public static function parse_float_or_null( $value ) {
		return '' === $value ? null : floatval( $value );
	}

	/**
	 * Return floatval if value is not empty, null otherwise.
	 *
	 * @param mixed $value value to be checked.
	 * @return mixed $value
	 */
	public static function convert_comma( $value ) {
		return false === strpos( $value, '.' ) ? str_replace( ',', '.', $value ) : $value;
	}

	/**
	 * Return base64 encoded value if not null.
	 *
	 * @param mixed $value value to be encoded.
	 * @return mixed $value
	 */
	public static function base64_or_null( $value ) {
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return null === $value ? null : base64_encode( $value );
	}

	/**
	 * Get checkout url.
	 *
	 * @return string checkout url
	 */
	public static function get_checkout_url() {
		static $checkout_url;

		if ( null !== $checkout_url ) {
			return $checkout_url;
		}

		if ( function_exists( 'wc_get_checkout_url' ) ) {
			$checkout_url = wc_get_checkout_url();
		} else {
			$checkout_url = WC()->cart->get_checkout_url();
		}
		return $checkout_url;
	}

	/**
	 * Is checkout page.
	 *
	 * @return boolean is checkout page
	 */
	public static function is_checkout_page() {
		if ( in_the_loop() ) {
			return (int) get_option( 'woocommerce_checkout_page_id' ) === get_the_ID();
		}

		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$checkout_url = self::get_checkout_url();
			$request_uri  = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );

			if ( self::remove_query_string( $checkout_url ) === self::remove_query_string( $request_uri ) ) {
				return true;
			}

			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				$http_referer = sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) );
				if ( false !== strpos( $checkout_url, $http_referer ) && false === strpos( WC()->cart->get_cart_url(), $request_uri ) ) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Helper function to remove query string in url.
	 *
	 * @param string $url url.
	 * @return boolean url without query string
	 */
	public static function remove_query_string( $url ) {
		if ( strpos( $url, '?' ) !== false ) {
			$url = substr( $url, 0, strpos( $url, '?' ) );
		}
		return $url;
	}

	/**
	 * Should display parcel point link.
	 *
	 * @param \WC_Shipping_Rate $rate woocommmerce shipping rate.
	 * @return boolean should display link
	 */
	public static function should_display_parcel_point_link( $rate ) {

		if ( ! in_array( Shipping_Rate_Util::get_id( $rate ), WC()->session->get( 'chosen_shipping_methods' ), true ) ) {
			return false;
		}

		if ( ! WC()->customer->get_shipping_country() || ! WC()->customer->get_shipping_city() ) {
			return false;
		}

		$countries      = Country_Util::get_activated_countries();
		$address_fields = $countries->get_address_fields( WC()->customer->get_shipping_country(), 'shipping_' );
		if ( $address_fields['shipping_state']['required'] && ! WC()->customer->get_shipping_state() ) {
			return false;
		}

		if ( $address_fields['shipping_postcode']['required'] && ! WC()->customer->get_shipping_postcode() ) {
			return false;
		}

		return true;
	}

	/**
	 * Get shipping method settings from method id.
	 *
	 * @param string $method_id woocommerce method id.
	 * @return array $settings method settings
	 */
	public static function get_settings( $method_id ) {
		if ( false !== strpos( $method_id, ':' ) ) {
			$method_name  = explode( ':', $method_id );
			$settings_key = 'woocommerce_' . $method_name[0] . '_' . $method_name[1] . '_settings';
		} else {
			$settings_key = 'woocommerce_' . $method_id . '_settings';
		}
		return get_option( $settings_key );
	}

	/**
	 * Get active parcel point networks for shipping method.
	 *
	 * @param array $settings shipping rate settings.
	 * @return array $networks
	 */
	public static function get_active_parcel_point_networks( $settings ) {
		if ( ! isset( $settings[ Branding::$branding_short . '_parcel_point_networks' ] )
			|| null === $settings[ Branding::$branding_short . '_parcel_point_networks' ]
			|| ! is_array( $settings[ Branding::$branding_short . '_parcel_point_networks' ] )
			|| empty( $settings[ Branding::$branding_short . '_parcel_point_networks' ] ) ) {
			return array();
		}

		$networks = Controller::get_network_list();
		if ( false === $networks || ! is_object( $networks ) ) {
			return array();
		}
		$networks_array = array();
		foreach ( $networks as $network => $network_carriers ) {
			$networks_array[] = $network;
		}
		return array_intersect( $networks_array, $settings[ Branding::$branding_short . '_parcel_point_networks' ] );
	}

	/**
	 * Retrocompatible way to add tooltip.
	 *
	 * @param string $tooltip tooltip.
	 *
	 * @void
	 */
	public static function echo_tooltip( $tooltip ) {
		if ( function_exists( 'wc_help_tip' ) ) {
			echo wc_help_tip( $tooltip );
		} else {
			echo '<img class="help_tip" data-tip="' . esc_attr( $tooltip ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
		}
	}

	/**
	 * Strip encoded double quotes of an array keys.
	 *
	 * @param array $array array.
	 *
	 * @return array
	 */
	public static function array_keys_strip_encoded_double_quotes( $array ) {
		$new_array = array();
		foreach ( $array as $key => $value ) {
			$key               = str_replace( '%22', '', $key );
			$key               = str_replace( '&quot;', '"', $key );
			$new_array[ $key ] = $value;
		}
		return $new_array;
	}

	/**
	 * Strip double quotes of an array keys.
	 *
	 * @param array $array array.
	 *
	 * @return array
	 */
	public static function array_keys_strip_double_quotes( $array ) {
		$new_array = array();
		foreach ( $array as $key => $value ) {
			$key               = str_replace( '%22', '', $key );
			$key               = str_replace( '&quot;', '', $key );
			$key               = str_replace( '"', '', $key );
			$new_array[ $key ] = $value;
		}
		return $new_array;
	}
}
