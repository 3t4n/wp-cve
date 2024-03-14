<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Wc_Custom_Thankyou {

	public function __construct() {
		/* checkout page */
		add_filter( 'wfacp_skip_checkout_page_detection', [ $this, 'remove_order_recieved_hook' ] );
	}

	public function remove_order_recieved_hook( $status ) {
		if ( ! function_exists( 'wc_custom_thankyou' ) ) {
			return $status;
		}
		global $wp;
		if ( ! isset( $wp->query_vars['order-received'] ) ) {
			remove_filter( 'woocommerce_is_order_received_page', array( wc_custom_thankyou(), 'custom_thankyou_is_order_received_page' ) );
		}

		return $status;
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Wc_Custom_Thankyou(), 'wc_custom_thankyou' );
