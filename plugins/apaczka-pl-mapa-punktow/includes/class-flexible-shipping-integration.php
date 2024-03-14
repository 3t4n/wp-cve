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
 * Integration with Flexible Shipping.
 */
class Flexible_Shipping_Integration {
	/**
	 * Delivery_Point_Map constructor.
	 */
	public function __construct() {
		add_filter( 'flexible_shipping_process_admin_options', array( $this, 'save_setting_options' ) );
	}

	/**
	 * Saves setting options.
	 *
	 * @param array $shipping_method .
	 * @return array
	 */
	public function save_setting_options( $shipping_method ) {
		if ( isset( $_POST['woocommerce_flexible_shipping_supplier_apaczka_map_fxsp'] ) ) {
			$shipping_method['supplier_apaczka_map_fxsp'] = sanitize_text_field( wp_unslash( $_POST['woocommerce_flexible_shipping_supplier_apaczka_map_fxsp'] ) );
		}

		$shipping_method['display_apaczka_map_fxsp'] = 'no';
		if ( isset( $_POST['woocommerce_flexible_shipping_display_apaczka_map_fxsp'] ) && $_POST['woocommerce_flexible_shipping_display_apaczka_map_fxsp'] == 1 ) {
			$shipping_method['display_apaczka_map_fxsp'] = 'yes';
		}

		$shipping_method['only_cod_apaczka_map_fxsp'] = 'no';
		if ( isset( $_POST['woocommerce_flexible_shipping_only_cod_apaczka_map_fxsp'] ) && $_POST['woocommerce_flexible_shipping_only_cod_apaczka_map_fxsp'] == 1 ) {
			$shipping_method['only_cod_apaczka_map_fxsp'] = 'yes';
		}

		return $shipping_method;
	}

	/**
	 * Returns flexible shipping methods data.
	 *
	 * @param int $instance_id .
	 * @return false|mixed|void
	 */
	private function get_methods_data( $instance_id ) {
		return get_option( 'flexible_shipping_methods_' . intval( $instance_id ) );
	}

	/**
	 * Returns chosen shipping method data about map.
	 *
	 * @param string $chosen_shipping_methods .
	 * @param int    $instance_id .
	 * @return array
	 */
	public function get_chosen_shipping_data( $chosen_shipping_methods, $instance_id ) {
		$shipping_data = array();
		$methods_data  = $this->get_methods_data( $instance_id );

		if ( false === $methods_data ) {
			return $shipping_data;
		}

		foreach ( $methods_data as $data ) {
			if ( $chosen_shipping_methods === $data['id_for_shipping'] ) {
				$shipping_data['display_apaczka_map_fxsp']  = isset( $data['display_apaczka_map_fxsp'] ) ? $data['display_apaczka_map_fxsp'] : null;
				$shipping_data['supplier_apaczka_map_fxsp'] = isset( $data['supplier_apaczka_map_fxsp'] ) ? $data['supplier_apaczka_map_fxsp'] : null;
				$shipping_data['only_cod_apaczka_map_fxsp'] = isset( $data['only_cod_apaczka_map_fxsp'] ) ? $data['only_cod_apaczka_map_fxsp'] : null;
			}
		}

		return $shipping_data;
	}
}

new Flexible_Shipping_Integration();
