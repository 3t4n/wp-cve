<?php

#[AllowDynamicProperties] 

  class WFACP_Theme_Understrap {
	public function __construct() {

		add_action( 'wfacp_internal_css', [ $this, 'remove_actions' ] );
	}

	public function remove_actions() {
		remove_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form' );
		remove_filter( 'woocommerce_form_field_args', 'understrap_wc_form_field_args' );
	}
}


new WFACP_Theme_Understrap();
