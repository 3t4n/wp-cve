<?php

/**
 * PaysonCheckout for WooCommerce BY  Krokedril
 *
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_PaysonCheckout_For_WC
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_PaysonCheckout_For_WC {
	public function __construct() {
		add_filter( 'wfacp_skip_checkout_page_detection', [ $this, 'disable_checkout_page_if_paysonCheckout_set' ], 100 );

	}

	public function disable_checkout_page_if_paysonCheckout_set( $status ) {
		if ( is_null( WC()->cart ) || is_null( WC()->session ) || WC()->cart->is_empty() || false === WFACP_Core()->public->is_checkout_override() ) {
			return $status;
		}

		$current_gateway = WC()->session->get( 'chosen_payment_method', '' );

		if ( 'paysoncheckout' !== $current_gateway ) {
			return $status;
		}

		return true;
	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_PaysonCheckout_For_WC(), 'paysoncheckout-for-wc' );
