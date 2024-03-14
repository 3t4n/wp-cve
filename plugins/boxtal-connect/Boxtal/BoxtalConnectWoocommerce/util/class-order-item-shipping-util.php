<?php
/**
 * Contains code for order item shipping util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

/**
 * Order item shipping util class.
 *
 * Helper to manage consistency between woocommerce versions order item shipping getters and setters.
 */
class Order_Item_Shipping_Util {

	/**
	 * Get order item shipping method id.
	 *
	 * @param \WC_Order_Item_Shipping|array $order_item_shipping woocommerce order item shipping.
	 * @return string order item shipping key
	 */
	public static function get_method_id( $order_item_shipping ) {
		if ( is_object( $order_item_shipping ) && method_exists( $order_item_shipping, 'get_method_id' ) ) {
			return $order_item_shipping->get_method_id();
		}
		return is_array( $order_item_shipping ) && isset( $order_item_shipping['method_id'] ) ? $order_item_shipping['method_id'] : null;
	}

	/**
	 * Get order item shipping instance id.
	 *
	 * @param \WC_Order_Item_Shipping|array $order_item_shipping woocommerce order item shipping.
	 * @return string order item shipping key
	 */
	public static function get_instance_id( $order_item_shipping ) {
		if ( is_object( $order_item_shipping ) && method_exists( $order_item_shipping, 'get_instance_id' ) ) {
			return $order_item_shipping->get_instance_id();
		}
		return is_array( $order_item_shipping ) && isset( $order_item_shipping['instance_id'] ) ? $order_item_shipping['instance_id'] : null;
	}

	/**
	 * Get order item shipping name.
	 *
	 * @param \WC_Order_Item_Shipping|array $order_item_shipping woocommerce order item shipping.
	 * @return string order item shipping name
	 */
	public static function get_name( $order_item_shipping ) {
		if ( is_object( $order_item_shipping ) && method_exists( $order_item_shipping, 'get_name' ) ) {
			return $order_item_shipping->get_name();
		}
		return is_array( $order_item_shipping ) && isset( $order_item_shipping['name'] ) ? $order_item_shipping['name'] : null;
	}
}
