<?php
/**
 * Contains code for cart util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

/**
 * Cart util class.
 *
 * Helper to manage cart.
 */
class Cart_Util {

	/**
	 * Get cart weight.
	 *
	 * @return float
	 */
	public static function get_weight() {
		$weight = 0;
		foreach ( WC()->cart->get_cart() as $item_id => $item ) {
			if ( $item['data']->needs_shipping() ) {
				$variation_id   = $item['variation_id'];
				$product_id     = ( '0' !== $variation_id && 0 !== $variation_id ) ? $variation_id : $item['product_id'];
				$product_weight = Product_Util::get_product_weight( $product_id );
				if ( false === $product_weight ) {
					$product_weight = 0;
				}
				$weight += $product_weight * $item['quantity'];
			}
		}
		return $weight;
	}
}
