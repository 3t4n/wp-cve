<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Name: WooCommerce Deposits
 * URI: https://www.webtomizer.com/
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_WC_Deposit {
	public function __construct() {

		/* checkout page */
		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'save_data_to_parent_order' ], 99, 2 );
		add_action( 'wfacp_analytics_custom_order_status', [ $this, 'add_custom_order_status' ] );
		add_filter( 'wfacp_maybe_update_order', [ $this, 'maybe_update_parent_order' ] );
	}

	public function save_data_to_parent_order( $order_id, $data ) {
		if ( ! class_exists( '\Webtomizer\WCDP\WC_Deposits' ) ) {
			return;
		}
		$order = wc_get_order( $order_id );;
		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$already_saved = wfacp_get_order_meta( $order, '_wfacp_post_id' );

		if ( absint( $already_saved ) > 0 ) {
			return;
		}
		WFACP_Common::update_aero_custom_fields( $order, $data, true );
	}

	public function add_custom_order_status( $status ) {
		if ( ! class_exists( '\Webtomizer\WCDP\WC_Deposits' ) ) {
			return $status;
		}
		$status[] = 'partially-paid';

		return $status;
	}

	public function maybe_update_parent_order( $order ) {

		if ( ! $order instanceof WC_Order ) {
			return $order;
		}

		if ( $order && $order->get_type() === 'wcdp_payment' ) {
			$order = wc_get_order( $order->get_parent_id() );
		}

		return $order;

	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_Deposit(), 'wc_deposit' );
