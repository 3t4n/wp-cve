<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_payonecw {

	public function __construct() {
		add_filter( 'wfacp_skip_checkout_page_detection', [ $this, 'skip_detection' ] );
	}

	public function skip_detection( $status ) {
		if ( isset( $_REQUEST['cwcontroller'] ) && 'redirection' == $_REQUEST['cwcontroller'] ) {
			return true;
		}
		// We need to be in checkout, to calculate the complete order total
		if ( isset( $GLOBALS['cwExternalCheckoutOrderTotal'] ) && $GLOBALS['cwExternalCheckoutOrderTotal'] ) {
			return true;
		}
		if ( function_exists( 'woocommerce_payonecw_is_plugin_page' ) && woocommerce_payonecw_is_plugin_page() ) {
			return true;
		}

		return $status;
	}
}

add_action( 'wfacp_start_page_detection', function () {
	if ( ! function_exists( 'woocommerce_payonecw_is_plugin_page' ) ) {
		return;
	}
	WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_payonecw(), 'payonecw' );
} );

