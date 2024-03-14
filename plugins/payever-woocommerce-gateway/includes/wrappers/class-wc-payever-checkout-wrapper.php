<?php

class WC_Payever_Checkout_Wrapper {

	/**
	 * @param $input
	 *
	 * @return string
	 */
	public function get_value( $input ) {
		/** @var WooCommerce $woocommerce */
		global $woocommerce;

		return $woocommerce->checkout()->get_value( $input );
	}
}
