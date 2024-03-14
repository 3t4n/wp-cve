<?php

/**
 * Name: WooCommerce AvaTax by SkyVerge (1.15.0)
 * URL: http://www.woocommerce.com/products/woocommerce-avatax/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_WC_Avatax {

	public function __construct() {
		add_action( 'wfacp_template_load', [ $this, 'action' ] );
	}

	public function action() {
		if ( ! class_exists( 'WC_AvaTax_Checkout_Handler' ) ) {
			return;
		}
		WFACP_Common::remove_actions( 'woocommerce_get_price_excluding_tax', 'WC_AvaTax_Checkout_Handler', 'adjust_cart_item_prices' );
		WFACP_Common::remove_actions( 'woocommerce_get_price_including_tax', 'WC_AvaTax_Checkout_Handler', 'adjust_cart_item_prices' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_WC_Avatax(), 'wfacp-avatax' );
