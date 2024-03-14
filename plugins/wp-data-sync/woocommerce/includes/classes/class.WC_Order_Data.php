<?php
/**
 * WC_Order_Data
 *
 * Create an order data array.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\App\Log;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Order_Data {

	/**
	 * @var WC_Order_Data
	 */

	public static $instance;

	/**
	 * WC_Order_Data constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * @return WC_Order_Data
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Get order array.
	 *
	 * @param \WC_Order $order
	 *
	 * @return mixed
	 */

	public function get( $order ) {

		$_order                  = $order->get_data();
		$_order                  = $this->formatted_data( $_order, $order );
		$_order['meta_data']     = $this->format_meta( $order );
		$_order['items']         = $this->get_items( $order );
		$_order['shipping_data'] = $this->get_shipping_data( $order );

		Log::write( 'order', $_order );

		return apply_filters( 'wp_data_sync_order_data', $_order, $order );

	}

	/**
	 * @param $order \WC_Order
	 *
	 * @return array
	 */

	public function get_items( $order ) {

        $i           = 0;
		$order_items = [];

		foreach ( $order->get_items() as $item ) {

			if ( apply_filters( 'wp_data_sync_include_order_item', true, $item, $order ) ) {

				$order_item              = $item->get_data();
				$order_item['meta_data'] = $this->format_meta( $item );

				if ( $product = wc_get_product( $item->get_product_id() ) ) {

					$order_item['sku'] = $product->get_sku();
					$order_item        = apply_filters( 'wp_data_sync_order_items_product', $order_item, $product->get_id(), $product );

				} else {
					$order_item['sku'] = 'NA';
				}

                $order_items[ $i ] = $order_item;
                $i++;

			}

		}

		return apply_filters( 'wp_data_sync_order_items', $order_items, $order );

	}

	/**
	 * Formatted data.
	 *
	 * @param $_order
	 * @param $order
	 *
	 * @return mixed
	 */

	public function formatted_data( $_order, $order ) {

		// Billing fields
		$_order['billing']['full_name']     = $order->get_formatted_billing_full_name();
		$_order['billing']['address_full']  = $this->format_address( $order, 'billing' );

		// Shipping fields
		$_order['shipping']['full_name']    = $order->get_formatted_shipping_full_name();
		$_order['shipping']['address_full']  = $this->format_address( $order, 'shipping' );

		return $_order;

	}

	/**
	 * Format Address.
	 *
	 * @param $order
	 * @param $type
	 *
	 * @return string
	 */

	public function format_address( $order, $type ) {

		$data  = $order->get_data();

		return join( ' ', array_filter( [
			$data[ $type ]['address_1'],
			$data[ $type ]['address_2'],
			$data[ $type ]['city'],
			$data[ $type ]['state'],
			$data[ $type ]['postcode'],
			$data[ $type ]['country']
		] ) );

	}

	/**
	 * Format meta.
	 *
	 * @param \WC_Order|\WC_Order_Item $order
	 *
	 * @return array
	 */

	public function format_meta( $order ) {

		$meta_data =  $order->get_meta_data();

		if ( ! is_array( $meta_data ) ) {
			return $meta_data;
		}

		$_meta_data = [];

		foreach ( $meta_data as $meta ) {

			$data = $meta->get_data();

			$_meta_data[ $data['key'] ] = $data['value'];

		}

		return apply_filters( 'wp_data_sync_order_meta', $_meta_data, $order );

	}

	/**
	 * Get shipping data.
	 *
	 * @param $order
	 *
	 * @return mixed|array
	 */

	public function get_shipping_data( $order ) {

		$order_shipping = [];

		foreach( $order->get_items( 'shipping' ) as $shipping_item_obj ){
			$order_shipping = $shipping_item_obj->get_data();
			break;
		}

		return apply_filters( 'wp_data_sync_order_shipping', $order_shipping, $order );

	}

}
