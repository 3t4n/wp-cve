<?php
/**
 * Apaczka.pl Mapa Punktów
 *
 * @package Apaczka Mapa Punktów
 * @author  InspireLabs
 * @link    https://inspirelabs.pl/
 */

namespace Apaczka_Points_Map;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Integration with WooCommerce shipping.
 */
class WC_Shipping_Integration {
	/**
	 * Maps_Integration constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Init Hooks.
	 */
	public function init_hooks() {
		add_action( 'woocommerce_init', array( $this, 'filtering_shipping_fields' ) );
	}

	/**
	 * Adds custom field to each shipping method.
	 */
	public function filtering_shipping_fields() {
		$shipping_methods = WC()->shipping->get_shipping_methods();

		foreach ( $shipping_methods as $shipping_method ) {
			add_filter( 'woocommerce_shipping_instance_form_fields_' . $shipping_method->id, array( $this, 'add_map_field' ) );
		}
	}

	/**
	 * Adds Apaczka map fields to shipping method settings.
	 *
	 * @param array $settings .
	 * @return mixed
	 */
	public function add_map_field( $settings ) {
		if ( ! isset( WC()->integrations->integrations['woocommerce-maps-apaczka']->settings['correct_api_connection'] ) ||
			'no' === WC()->integrations->integrations['woocommerce-maps-apaczka']->settings['correct_api_connection']
		) {
			return $settings;
		}

		$settings = WC_Shipping_Integration_Helper::settings_field( $settings );

		return $settings;
	}
}

new WC_Shipping_Integration();
