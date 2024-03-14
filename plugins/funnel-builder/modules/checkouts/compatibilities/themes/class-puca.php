<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Puca {

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'unhook_head_script' ] );
	}

	public function unhook_head_script() {
		if ( function_exists( 'puca_woocommerce_custom_action_check_out' ) ) {
			remove_action( 'woocommerce_before_checkout_form', 'puca_woocommerce_custom_action_check_out', 20 );
		}
		if ( function_exists( 'puca_tbay_head_scripts' ) ) {
			remove_action( 'wp_head', 'puca_tbay_head_scripts', - 9999 );
		}
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Puca(), 'wfacp-puca' );
