<?php

/**
 * WOOF - WooCommerce Products Filter
 * https://products-filter.com/
 * https://pluginus.net/support/forum/woof-woocommerce-products-filter/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Product_Filter_By_RealMag {
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_actions' ] );
	}

	public function remove_actions() {
		WFACP_Common::remove_actions( 'wp_head', 'WOOF', 'wp_head' );
		WFACP_Common::remove_actions( 'wp_head', 'WOOF', 'wp_load_js' );
		WFACP_Common::remove_actions( 'wp_footer', 'WOOF', 'wp_load_js' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Product_Filter_By_RealMag(), 'price_filter_realMag' );
