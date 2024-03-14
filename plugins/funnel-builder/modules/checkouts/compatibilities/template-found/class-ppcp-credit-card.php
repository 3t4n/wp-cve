<?php

#[AllowDynamicProperties] 

  class WFACP_PPCP_WooCommerce {
	public function __construct() {
		add_action( 'wfacp_internal_css', [ $this, 'remove_visibility_hidden' ] );
	}

	public function remove_visibility_hidden() {
		echo "<style>body .wfacp-section.wfacp_payment.wfacp_hide_payment_part {visibility: visible;position: relative;z-index: 0;left: 0}
body span#ppcp-credit-card-gateway-card-number {height: 40px !important;}
body span#ppcp-credit-card-gateway-card-expiry {height: 40px !important;}
body span#ppcp-credit-card-gateway-card-cvc {height: 40px !important;}</style>";
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_PPCP_WooCommerce(), 'wfacp-ppcp-credit-card' );
