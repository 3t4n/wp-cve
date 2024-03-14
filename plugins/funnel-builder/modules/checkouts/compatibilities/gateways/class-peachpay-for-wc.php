<?php

/*
 * PeachPay for WooCommerce | One-Click Checkout by PeachPay, Inc. v(1.54.5)
 * URL https://woocommerce.com/products/peachpay
 *
 */

#[AllowDynamicProperties] 

  class WFACP_PeachPay_For_WC {
	public function __construct() {
		add_action( 'init', [ $this, 'init_class' ], 4 );
	}

	public function init_class() {

		if ( ! WFACP_Common::is_theme_builder() ) {
			return;
		}
		remove_action( 'init', 'peachpay_init' );
	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_PeachPay_For_WC(), 'wfacp-peachpay' );
