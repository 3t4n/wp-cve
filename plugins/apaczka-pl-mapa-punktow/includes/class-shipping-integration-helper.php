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
class WC_Shipping_Integration_Helper {
	/**
	 * Adds Apaczka map fields to shipping method settings.
	 *
	 * @param array $settings .
	 * @return array
	 */
	public static function settings_field( $settings ) {
		$settings['display_apaczka_map'] = array(
			'title'       => esc_html__( 'Apaczka.pl Delivery Points Map', 'apaczka-pl-mapa-punktow' ),
			'type'        => 'checkbox',
			'description' => __( 'Displays a map of delivery points on the checkout form for this shipping method.', 'apaczka-pl-mapa-punktow' ),
			'desc_tip'    => true,
			'default'     => '',
		);

		$settings['supplier_apaczka_map'] = array(
			'title'   => esc_html__( 'Apaczka.pl Supplier', 'apaczka-pl-mapa-punktow' ),
			'type'    => 'select',
			'default' => 'all',
			'options' => array(
				'all'        => __( 'All', 'apaczka-pl-mapa-punktow' ),
				'DHL_PARCEL' => __( 'DHL', 'apaczka-pl-mapa-punktow' ),
				'DPD'        => __( 'DPD', 'apaczka-pl-mapa-punktow' ),
				'INPOST'     => __( 'Inpost', 'apaczka-pl-mapa-punktow' ),
				'PWR'        => __( 'Orlen Paczka', 'apaczka-pl-mapa-punktow' ),
				'POCZTA'     => __( 'Poczta Polska', 'apaczka-pl-mapa-punktow' ),
				'UPS'        => __( 'UPS', 'apaczka-pl-mapa-punktow' ),
			),
		);

		$settings['only_cod_apaczka_map'] = array(
			'title'       => esc_html__( 'Apaczka.pl only C.O.D points', 'apaczka-pl-mapa-punktow' ),
			'type'        => 'checkbox',
			'description' => __( 'Displays only points with Collect on Delivery.', 'apaczka-pl-mapa-punktow' ),
			'desc_tip'    => true,
			'default'     => '',
		);

		return $settings;
	}
}
