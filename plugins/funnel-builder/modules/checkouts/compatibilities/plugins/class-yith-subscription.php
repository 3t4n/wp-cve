<?php

#[AllowDynamicProperties] 

  class WFACP_Compatibility_Yith_Subscription {
	public function __construct() {
		add_action( 'ywsbs_before_add_to_cart_subscription', [ $this, 'remove_aero_action' ] );
	}


	public function remove_aero_action() {
		remove_action( 'woocommerce_cart_loaded_from_session', [ WFACP_Core()->public, 'save_wfacp_session' ], 99 );
		remove_filter( 'woocommerce_cart_contents_changed', [ WFACP_Core()->public, 'set_save_session' ], 99 );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Yith_Subscription(), 'yith-subscription' );
