<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_Theme_Leka {
	public function __construct() {

		add_action( 'wfacp_checkout_page_found', [ $this, 'unhook_dynamic_style' ] );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'unhook_func' ] );

	}

	public function unhook_func() {
		remove_action( 'woocommerce_before_checkout_form', 'arexworks_woocommerce_before_checkout_form', 10 );

	}

	public function unhook_dynamic_style() {

		remove_action( 'wp_head', 'arexworks_add_custom_header_css', 999 );
	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Theme_Leka(), 'wfacp-leka' );
