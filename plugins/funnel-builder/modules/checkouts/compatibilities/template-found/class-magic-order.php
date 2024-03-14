<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Magic_Order {

	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_hooks' ] );
	}

	public function remove_hooks() {
		if ( function_exists( 'load_custom_mgo_style_front' ) ) {
			remove_action( 'wp_enqueue_scripts', 'load_custom_mgo_style_front' );

		}
		if ( function_exists( 'magic_order_header' ) ) {
			remove_action( 'wp_head', 'magic_order_header' );

		}
	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Magic_Order(), 'magic-order' );

