<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Address_Auto_Populate {
	public function __construct() {
		add_action( 'woocommerce_before_checkout_form', [ $this, 'dequeue_js' ] );

	}

	public function dequeue_js() {
		wp_enqueue_script( 'wfacp_address_populate', WFACP_PLUGIN_URL . '/compatibilities/js/address-populate.min.js', [], WFACP_VERSION, true );
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Address_Auto_Populate(), 'address-autofill' );
