<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_strolik_core {
	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_strolik_actions' ] );

	}


	public function remove_strolik_actions() {
		if ( function_exists( 'osf_checkout_before_customer_details_container' ) ) {
			remove_action( 'woocommerce_checkout_before_customer_details', 'osf_checkout_before_customer_details_container', 1 );
		}
		if ( function_exists( 'osf_checkout_after_customer_details_container' ) ) {
			remove_action( 'woocommerce_checkout_after_customer_details', 'osf_checkout_after_customer_details_container', 1 );
		}

	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_strolik_core(), 'sa' );
