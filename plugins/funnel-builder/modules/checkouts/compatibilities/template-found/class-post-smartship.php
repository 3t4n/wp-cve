<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Posts_SmartShip {

	public function __construct() {

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
	}

	public function actions() {
		add_filter( 'wfacp_show_shipping_options', '__return_true' );
		remove_action( 'woocommerce_checkout_order_review', 'wb_smartpost_uf_noutopistehaku', 20 );
		add_action( 'wfacp_before_payment_section', 'wb_smartpost_uf_noutopistehaku', 20 );
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Posts_SmartShip(), 'posts_smartship' );
