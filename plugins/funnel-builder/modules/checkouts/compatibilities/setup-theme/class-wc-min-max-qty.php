<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * WooCommerce Min/Max Quantities
 * Author Name: WooCommerce
 * https://woocommerce.com/products/minmax-quantities/
 */

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_Min_Max_Qty {

	public function __construct() {
		add_filter( 'wfacp_product_item_min_max_quantity', [ $this, 'product_item_min_max_quantity' ], 10, 2 );
		add_filter( 'wfacp_cart_item_min_max_quantity', [ $this, 'cart_item_min_max_quantity' ], 10, 2 );


	}

	public function product_item_min_max_quantity( $MinMax, $product ) {
		if ( $product instanceof WC_Product ) {
			$MinMax['min'] = $product->get_meta( 'minimum_allowed_quantity' );
			$MinMax['max'] = $product->get_meta( 'maximum_allowed_quantity' );
		}

		return $MinMax;
	}

	public function cart_item_min_max_quantity( $MinMax, $cart_item ) {
		if ( empty( $cart_item ) || empty( $cart_item['data'] ) ) {
			return $MinMax;
		}
		$product = $cart_item['data'];
		if ( $product instanceof WC_Product ) {

			if ( in_array( $product->get_type(), WFACP_Common::get_variation_product_type() ) ) {
				$min_max_rule_allowed = $product->get_meta( 'min_max_rules' );
				if ( 'yes' == $min_max_rule_allowed ) {
					$MinMax['min'] = $product->get_meta( 'variation_minimum_allowed_quantity' );
					$MinMax['max'] = $product->get_meta( 'variation_maximum_allowed_quantity' );
				} else {
					$id            = $product->get_parent_id();
					$MinMax['min'] = get_post_meta( $id, 'minimum_allowed_quantity', true );
					$MinMax['max'] = get_post_meta( $id, 'maximum_allowed_quantity', true );
				}
			} else {
				$MinMax['min'] = $product->get_meta( 'minimum_allowed_quantity' );
				$MinMax['max'] = $product->get_meta( 'maximum_allowed_quantity' );
			}
		}

		return $MinMax;
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Min_Max_Qty(), 'wfacp-wc-min-max-qty' );
