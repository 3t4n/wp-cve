<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
Name: WPC Product Quantity for WooCommerce (Premium)
URI: https://wpclever.net/
*/

#[AllowDynamicProperties] 

  class WFACP_WPC_Quantity_Premium {

	public function __construct() {
		add_filter( 'wfacp_cart_item_min_max_quantity', [ $this, 'cart_item_min_max_quantity' ], 10, 2 );
	}

	public function get_option_min_max( $MinMax ) {
		$type = get_option( '_woopq_type' );
		if ( 'default' == $type ) {
			$MinMax['step'] = get_option( '_woopq_step' );
		}
		$MinMax['min'] = get_option( '_woopq_min' );
		$MinMax['max'] = get_option( '_woopq_max' );

		return $MinMax;
	}

	public function get_product_min_max( $id, $MinMax ) {
		$type = get_post_meta( $id, '_woopq_type', true );
		if ( 'default' == $type ) {
			$MinMax['step'] = get_post_meta( $id, '_woopq_step', true );
		}
		$MinMax['min'] = get_post_meta( $id, '_woopq_min', true );
		$MinMax['max'] = get_post_meta( $id, '_woopq_max', true );

		return $MinMax;
	}

	public function get_parent_min_max( $id, $MinMax ) {
		$min_max_rule_allowed = get_post_meta( $id, '_woopq_quantity', true );

		if ( 'disable' == $min_max_rule_allowed ) {
			return $MinMax;
		}
		if ( 'default' == $min_max_rule_allowed || '' == $min_max_rule_allowed ) {

			return $this->get_option_min_max( $MinMax );
		}
		if ( 'overwrite' == $min_max_rule_allowed ) {
			return $this->get_product_min_max( $id, $MinMax );
		}

		return $MinMax;
	}


	public function cart_item_min_max_quantity( $MinMax, $cart_item ) {
		if ( empty( $cart_item ) || empty( $cart_item['data'] ) ) {
			return $MinMax;
		}

		$product = $cart_item['data'];
		if ( ! $product instanceof WC_Product ) {
			return $MinMax;
		}
		$min_max_rule_allowed = $product->get_meta( '_woopq_quantity' );
		if ( in_array( $product->get_type(), WFACP_Common::get_variation_product_type() ) ) {
			if ( 'disable' == $min_max_rule_allowed ) {
				return $MinMax;
			}
			if ( 'default' == $min_max_rule_allowed || '' == $min_max_rule_allowed ) {
				return $this->get_option_min_max( $MinMax );
			}
			if ( 'overwrite' == $min_max_rule_allowed ) {
				return $this->get_product_min_max( $product->get_id(), $MinMax );
			}
			if ( 'parent' == $min_max_rule_allowed ) {
				return $this->get_parent_min_max( $product->get_parent_id(), $MinMax );
			}
		} else {
			$MinMax = $this->get_parent_min_max( $product->get_id(), $MinMax );
		}

		return $MinMax;
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_WPC_Quantity_Premium(), 'wfacp-wpc-quantity' );
