<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Active_Hestia {

	public function __construct() {
		/* checkout page */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_actions' ] );
	}

	public function remove_actions() {

		remove_action( 'woocommerce_before_checkout_form', 'hestia_coupon_after_order_table_js' );
		remove_action( 'woocommerce_checkout_order_review', 'hestia_coupon_after_order_table' );

	}

}

if ( ! defined( 'HESTIA_VERSION' ) ) {
	return;
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Active_Hestia(), 'hestia' );
