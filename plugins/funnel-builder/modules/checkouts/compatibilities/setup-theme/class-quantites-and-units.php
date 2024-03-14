<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Quantities and Units for WooCommerce by Nicholas Verwymeren
 * URL: https://wordpress.org/plugins/quantities-and-units-for-woocommerce/
 */


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Quantities_And_Unites {

	public $instance = null;

	public function __construct() {
		add_filter( 'wfacp_product_item_min_max_quantity', [ $this, 'product_item_min_max_quantity' ], 10, 2 );
		add_filter( 'wfacp_cart_item_min_max_quantity', [ $this, 'cart_item_min_max_quantity' ], 10, 2 );
		add_action( 'wfacp_template_load', [ $this, 'action' ] );


	}

	public function enable() {
		$is_global_checkout = WFACP_Core()->public->is_checkout_override();
		if ( ! class_exists( 'WC_Quantities_and_Units_Filters' ) || false === $is_global_checkout ) {
			return false;
		}

		return true;
	}

	public function action() {
		if ( ! $this->enable() ) {
			return;
		}

		$this->instance = WFACP_Common::remove_actions( 'woocommerce_quantity_input_min', 'WC_Quantities_and_Units_Filters', 'input_min_value' );

	}


	public function cart_item_min_max_quantity( $MinMax, $cart_item ) {


		if (! $this->enable() || empty( $cart_item ) || empty( $cart_item['data'] ) || ! $this->instance instanceof WC_Quantities_and_Units_Filters ) {
			return $MinMax;
		}


		$product = $cart_item['data'];


		$minVal = $this->instance->input_min_value( 1, $product );

		$maxVal  = $this->instance->input_max_value( $MinMax['max'], $product );
		$stepVal = $this->instance->input_step_value( $MinMax['step'], $product );

		if ( $product instanceof WC_Product ) {

			if ( in_array( $product->get_type(), WFACP_Common::get_variation_product_type() ) ) {
				return $MinMax;

			} else {

				$MinMax['min']  = $minVal;
				$MinMax['max']  = $maxVal;
				$MinMax['step'] = $stepVal;
			}
		}


		return $MinMax;
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Quantities_And_Unites(), 'wfacp-wc-min-max-qty' );
