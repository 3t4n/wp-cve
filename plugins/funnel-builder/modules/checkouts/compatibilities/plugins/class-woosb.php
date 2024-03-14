<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * WPC Product Bundles for WooCommerce (Premium) by WPClever v.6.0.6
 */

#[AllowDynamicProperties] 

  class WFACP_Woosb {
	public function __construct() {
		add_action( 'wfacp_template_load', [ $this, 'action' ], 8 );
	}

	public function action() {

		if ( ! class_exists( 'WPCleverWoosb' ) ) {
			return;
		}

		WFACP_Common::remove_actions( 'woocommerce_add_cart_item_data', 'WPCleverWoosb', 'woosb_add_cart_item_data' );
	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Woosb(), 'woo-product-bundle' );

