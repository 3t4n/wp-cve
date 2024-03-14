<?php
/**
 * Contains code for configuration util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

use Boxtal\BoxtalConnectWoocommerce\Shipping_Method\Controller;
use Boxtal\BoxtalPhp\ApiClient;
use Boxtal\BoxtalPhp\RestClient;

/**
 * Auth util class.
 *
 * Helper to manage API auth.
 */
class Configuration_Report_Util {

	/**
	 * Generate a full configuration report.
	 *
	 * @return mixed
	 */
	public static function get_configuration_report() {
		$report = array();

		$report['boxtal_config']         = self::get_boxtal_config();
		$report['zones']                 = self::get_zones();
		$report['order_statuses']        = self::get_order_statuses();
		$report['classes']               = self::get_shipping_classes();
		$report['versions']              = self::get_versions();
		$report['php_extensions']        = self::get_php_extensions();
		$report['plugins']               = self::get_plugins();
		$report['settings']              = self::get_settings();
		$report['parcel_points_request'] = self::get_parcel_points_request();

		return $report;
	}

	/**
	 * Get all boxtal connect configurations.
	 *
	 * @return mixed
	 */
	private static function get_boxtal_config() {
		return Configuration_Util::get_all_configs();
	}

	/**
	 * Get all order statuses
	 *
	 * @return mixed
	 */
	private static function get_order_statuses() {
		return wc_get_order_statuses();
	}

	/**
	 * Get platform versions
	 *
	 * @return mixed
	 */
	private static function get_versions() {
		$versions = array();
		global $wp_version;

		$versions['php']            = phpversion();
		$versions['wordpress']      = isset( $wp_version ) ? $wp_version : null;
		$versions['woocommerce']    = defined( 'WC_VERSION' ) ? WC_VERSION : null;
		$versions['boxtal-connect'] = defined( 'BW_VERSION' ) ? BW_VERSION : null;

		return $versions;
	}

	/**
	 * Get installed php extensions
	 *
	 * @return mixed
	 */
	private static function get_php_extensions() {
		$extensions = get_loaded_extensions();
		sort( $extensions );
		return $extensions;
	}


	/**
	 * Get installed plugins
	 *
	 * @return mixed
	 */
	private static function get_plugins() {
		$plugins = get_plugins();
		$result  = array();

		foreach ( $plugins as $plugin ) {
			$result[] = array(
				'name'    => $plugin['Name'],
				'version' => $plugin['version'],
			);
		}

		return $result;
	}

	/**
	 * Get shipping method details
	 *
	 * @param WC_Shipping_Method $shipping_method shipping method object.
	 * @return mixed
	 */
	private static function get_shipping_method_details( $shipping_method ) {
		$class                  = get_class( $shipping_method );
		$shipping_method_detail = array(
			'class' => $class,
		);

		if ( 'WC_Shipping_Flat_Rate' === $class ) {
			$shipping_method_detail['name']       = $shipping_method->title;
			$shipping_method_detail['tax_status'] = $shipping_method->tax_status;
			$shipping_method_detail['cost']       = $shipping_method->get_option( 'cost' );
			$shipping_method_detail['enabled']    = $shipping_method->enabled;
		} elseif ( 'WC_Shipping_Free_Shipping' === $class ) {
			$shipping_method_detail['name']           = $shipping_method->title;
			$shipping_method_detail['minimum_amount'] = $shipping_method->min_amount;
			$shipping_method_detail['requires']       = $shipping_method->requires;
			$shipping_method_detail['enabled']        = $shipping_method->enabled;
		} elseif ( 'WC_Shipping_Local_Pickup' === $class ) {
			$shipping_method_detail['name']       = $shipping_method->title;
			$shipping_method_detail['tax_status'] = $shipping_method->tax_status;
			$shipping_method_detail['cost']       = $shipping_method->get_option( 'cost' );
			$shipping_method_detail['enabled']    = $shipping_method->enabled;
		} elseif ( 'Boxtal\\BoxtalConnectWoocommerce\\Shipping_Method\\Shipping_Method' === $class ) {
			$shipping_method_detail['name']       = $shipping_method->title;
			$shipping_method_detail['tax_status'] = $shipping_method->tax_status;
			$shipping_method_detail['enabled']    = $shipping_method->enabled;
			$shipping_method_detail['pricing']    = array();

			$pricings = Controller::get_pricing_items( Shipping_Method_Util::get_unique_identifier( $shipping_method ) );
			foreach ( $pricings as $pricing ) {
				$shipping_method_detail['pricing'][] = array(
					'price_from'           => $pricing['price_from'],
					'price_to'             => $pricing['price_to'],
					'weight_from'          => $pricing['weight_from'],
					'weight_to'            => $pricing['weight_to'],
					'shipping_class'       => $pricing['shipping_class'],
					'parcel_point_network' => $pricing['parcel_point_network'],
					'pricing'              => $pricing['pricing'],
					'flat_rate'            => $pricing['flat_rate'],
				);
			}
		}

		return $shipping_method_detail;
	}

	/**
	 * Get zones
	 *
	 * @return mixed
	 */
	private static function get_zones() {
		$result = null;

		if ( class_exists( 'WC_Shipping_Zones', false ) ) {
			$zones = \WC_Shipping_Zones::get_zones();

			foreach ( $zones as $zone ) {
				$shipping_methods = $zone['shipping_methods'];
				$zone_data        = array(
					'name'             => $zone['zone_name'],
					'locations'        => $zone['zone_locations'],
					'shipping-methods' => array(),
				);

				foreach ( $shipping_methods as $shipping_method ) {
					$zone_data['shipping-methods'][] = self::get_shipping_method_details( $shipping_method );
				}

				$result[] = $zone_data;
			}
		}

		return $result;
	}

	/**
	 * Get shipping classes
	 *
	 * @return mixed
	 */
	private static function get_shipping_classes() {
		return Shipping_Method_Util::get_shipping_class_list();
	}

	/**
	 * Get woocommerce settings
	 *
	 * @return mixed
	 */
	private static function get_settings() {
		$result  = array();
		$options = array(
			'woocommerce_enable_shipping_calc',
			'woocommerce_shipping_cost_requires_address',
			'woocommerce_ship_to_destination',
			'woocommerce_shipping_debug_mode',
		);

		foreach ( $options as $option ) {
			$result[ $option ] = get_option( $option );
		}

		return $result;
	}

	/**
	 * Get a parcel point request sample
	 *
	 * @return mixed
	 */
	private static function get_parcel_points_request() {
		$lib      = new ApiClient( Auth_Util::get_access_key(), Auth_Util::get_secret_key() );
		$networks = array_keys( get_object_vars( Configuration_Util::get_parcel_point_networks() ) );
		$address  = array(
			'street'  => '15 rue marsolier',
			'city'    => 'PARIS',
			'zipCode' => '75002',
			'country' => 'fr',
		);

		$response = $lib->getParcelPoints( $address, $networks );

		return array(
			'networks' => $networks,
			'address'  => $address,
			'response' => $response,
		);
	}
}
