<?php
/**
 * Thank you page customize
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\WooCommerce;

use WC_Order;
use NovaPoshta\Api\Api;
use WC_Order_Item_Shipping;

/**
 * Class ThankYou
 *
 * @package NovaPoshta\WooCommerce
 */
class ThankYou {

	/**
	 * API for Nova Poshta
	 *
	 * @var Api
	 */
	protected $api;

	/**
	 * Thank_You constructor.
	 *
	 * @param Api $api API for Nova Poshta.
	 */
	public function __construct( Api $api ) {

		$this->api = $api;
	}

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_filter( 'woocommerce_get_order_item_totals', [ $this, 'shipping' ], 10, 2 );
	}


	/**
	 * Get current shipping method.
	 *
	 * @param WC_Order $order Current order.
	 *
	 * @return false|WC_Order_Item_Shipping
	 */
	protected function get_current_shipping_method( WC_Order $order ) {

		$shipping_methods = $order->get_shipping_methods();
		if ( empty( $shipping_methods ) ) {
			return false;
		}

		$shipping_method = array_shift( $shipping_methods );
		if ( 'shipping_nova_poshta_for_woocommerce' !== $shipping_method->get_method_id() ) {
			return false;
		}

		return $shipping_method;
	}


	/**
	 * Get shipping method info.
	 *
	 * @param WC_Order_Item_Shipping $shipping_method Current shipping method.
	 *
	 * @return array
	 */
	protected function get_shipping_method_info( WC_Order_Item_Shipping $shipping_method ): array {

		$city_id      = $shipping_method->get_meta( 'city_id' );
		$warehouse_id = $shipping_method->get_meta( 'warehouse_id' );
		if ( ! $city_id || ! $warehouse_id ) {
			return [];
		}

		$info = [
			'city'      => $this->api->city( $city_id ),
			'warehouse' => $this->api->warehouse( $city_id, $warehouse_id ),
		];

		$internet_document = $shipping_method->get_meta( 'internet_document' );
		if ( ! $internet_document ) {
			return $info;
		}

		$info['invoice'] = $internet_document;

		return $info;
	}

	/**
	 * Modify shipping information on thank you page
	 *
	 * @param array    $total_rows Total rows on thank you page.
	 * @param WC_Order $order      Current order.
	 *
	 * @return array
	 */
	public function shipping( array $total_rows, WC_Order $order ): array {

		$shipping_method = $this->get_current_shipping_method( $order );
		if ( ! $shipping_method ) {
			return $total_rows;
		}

		$method_info = $this->get_shipping_method_info( $shipping_method );

		foreach ( $method_info as $info_line ) {
			$total_rows['shipping']['value'] .= sprintf( '<br>%s', $info_line );
		}

		return $total_rows;
	}
}
