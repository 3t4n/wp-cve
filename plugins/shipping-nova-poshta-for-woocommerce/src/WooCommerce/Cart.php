<?php
/**
 * Cart
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\WooCommerce;

use NovaPoshta\Settings\Settings;

/**
 * Class Cart
 *
 * @package NovaPoshta\WooCommerce
 */
class Cart {

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_filter( 'woocommerce_cart_get_total', [ $this, 'cart_total' ] );
	}

	/**
	 * Update cart total.
	 *
	 * @param float $total Total.
	 *
	 * @return float
	 */
	public function cart_total( float $total ): float {

		$shipping_methods = wc_get_chosen_shipping_method_ids();
		$shipping_method  = array_shift( $shipping_methods );

		if ( empty( $shipping_method ) ) {
			return $total;
		}

		$is_free = apply_filters( 'shipping_nova_poshta_for_woocommerce_free_shipping', false, $shipping_method );

		if ( $is_free ) {
			return $total - WC()->cart->get_shipping_total();
		}

		return $total;
	}
}
