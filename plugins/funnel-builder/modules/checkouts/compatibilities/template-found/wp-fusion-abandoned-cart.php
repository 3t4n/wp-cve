<?php

#[AllowDynamicProperties] 

  class WFACP_WP_Fusion_abandoned_cart {
	public function __construct() {
		add_action( 'wfacp_before_form', [ $this, 'remove_actions' ] );
	}

	public function remove_actions() {
		if ( WC()->cart->is_empty() ) {
			WFACP_Common::remove_actions( 'woocommerce_before_checkout_form', 'WPF_Abandoned_Cart_Woocommerce', 'before_checkout_form' );
		}
	}
}

if ( ! class_exists( 'WPF_Abandoned_Cart_Woocommerce' ) ) {
	return;
}

new WFACP_WP_Fusion_abandoned_cart();