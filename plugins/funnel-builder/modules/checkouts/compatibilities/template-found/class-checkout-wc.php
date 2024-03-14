<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Checkout_WC_Objectiv {
	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_checkout_page_found', [ $this, 'actions' ] );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
	}

	public function actions() {
		add_filter( 'cfw_checkout_is_enabled', '__return_false' );
		add_filter( 'cfw_is_checkout', '__return_false' );
		add_filter( 'wfacp_css_js_removal_paths', function ( $paths ) {
			$paths[] = 'checkout-for-woocommerce';

			return $paths;
		} );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Checkout_WC_objectiv(), 'objectiv' );
