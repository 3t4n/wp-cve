<?php

/**
 * WooCommerce DHL
 * By WP Desk
 *
 */
#[AllowDynamicProperties] 

  class WFACP_Shipping_DHL_WpDesk {
	public function __construct() {
		add_action( 'wfacp_after_template_found', [ $this, 'remove_action' ] );

	}

	public function remove_action() {
		$instance = WFACP_Common::remove_actions( 'woocommerce_review_order_after_shipping', 'WPDesk_WooCommerce_DHL', 'woocommerce_review_order_after_shipping' );
		if ( $instance instanceof WPDesk_WooCommerce_DHL ) {
			add_action( 'wfacp_woocommerce_review_order_after_shipping', array( $instance, 'woocommerce_review_order_after_shipping' ) );
		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Shipping_DHL_WpDesk(), 'wpdesk_dhl' );

