<?php

/**
 * Compatibility  for 'PayPal Express Checkout Payment Gateway for WooCommerce ( Basic )' plugin
 * By webtoffee
 */
#[AllowDynamicProperties] 

  class WFACP_EH_PAYPAL_Express {
	public function __construct() {
		add_action( 'woocommerce_checkout_create_order', [ $this, 'update_custom_fields' ], 10, 2 );
	}

	/**
	 * Update Aero Custom field using WC_Order Object
	 * @param $order WC_Order
	 * @param $posted_data
	 *
	 * @return void
	 */
	public function update_custom_fields( $order, $posted_data ) {

		if ( ! isset( $posted_data['_wfacp_post_id'] ) || ( ! isset( $posted_data['payment_method'] ) || $posted_data['payment_method'] !== 'eh_paypal_express' ) ) {
			return;
		}
		WFACP_Common::update_aero_custom_fields( $order, $posted_data);
	}

}

new WFACP_EH_PAYPAL_Express();
