<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Uncode {

	public function __construct() {

		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_action' ] );

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_filter' ] );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'remove_filter' ] );

	}

	public function remove_filter() {
		if ( function_exists( 'uncode_woocommerce_order_button_html' ) ) {
			remove_filter( 'woocommerce_order_button_html', 'uncode_woocommerce_order_button_html', 10 );
		}
	}

	public function remove_action() {
		if ( function_exists( 'uncode_remove_woo_scripts' ) ) {
			remove_action( 'wp_enqueue_scripts', 'uncode_remove_woo_scripts', 99 );
		}
	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Uncode(), 'uncode' );
