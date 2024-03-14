<?php

/**
 * ECPay Logistics for WooCommerce
 * https://www.ecpay.com.tw
 */
#[AllowDynamicProperties] 

  class WFACP_Ecpay_Logistics_WC {
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'setup' ] );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'setup' ] );
	}

	public function setup() {
		add_action( 'woocommerce_review_order_before_shipping', [ $this, 'remove_action' ] );
	}

	public function remove_action() {
		if ( class_exists( 'ECPayShippingMethods' ) ) {
			$instance = WFACP_Common::remove_actions( 'woocommerce_review_order_after_shipping', 'ECPayShippingMethods', 'wcso_review_order_shipping_options' );
			if ( $instance instanceof ECPayShippingMethods ) {
				add_action( 'wfacp_woocommerce_review_order_after_shipping', array( $instance, 'wcso_review_order_shipping_options' ) );
			}
		}
	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Ecpay_Logistics_WC(), 'ecpay-wc' );
