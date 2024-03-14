<?php

#[AllowDynamicProperties] 

  class WFACP_Compatibility_PP_one_page_checkout {
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_actions' ] );
	}

	public function remove_actions() {
		WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'PP_One_Page_Checkout', 'enqueue_scripts' );
		WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'PP_One_Page_Checkout', 'maybe_enqueue_single_product_styles_scripts' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_PP_one_page_checkout(), 'pp_one_page_checkout' );