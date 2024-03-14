<?php

/**
 * Compatibility added for webtoffee
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_HF_Woocommerce_Subscription
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_HF_Woocommerce_Subscription {
	public function __construct() {
		add_action( 'wfacp_woocommerce_cart_item_subtotal_except_subscription', [ $this, 'do_not_display_price' ], 10, 2 );
		add_action( 'wfacp_woocommerce_cart_item_subtotal_except_subscription_placeholder', [ $this, 'display_subscription_price' ], 10, 3 );
	}

	/**
	 * @param $status boolean
	 * @param $_product WC_Product
	 *
	 * @return boolean
	 */
	public function do_not_display_price( $status, $_product ) {
		if ( ! $_product instanceof WC_Product ) {
			return $status;
		}
		if ( in_array( $_product->get_type(), [ 'variable-subscription', 'subscription', 'subscription_variation' ] ) && class_exists( 'HF_Woocommerce_Subscription' ) ) {
			$status = false;
		}

		return $status;
	}

	/**
	 * @param $_product WC_Product
	 * @param $cart_item
	 * @param $cart_item_key
	 */
	public function display_subscription_price( $_product, $cart_item, $cart_item_key ) {
		if ( ! $_product instanceof WC_Product ) {
			return '';
		}
		if ( in_array( $_product->get_type(), [ 'variable-subscription', 'subscription', 'subscription_variation' ] ) && class_exists( 'HF_Woocommerce_Subscription' ) ) {
			echo WFACP_Common::display_subscription_price( $_product, $cart_item, $cart_item_key );
		}
	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_HF_Woocommerce_Subscription(), 'webtoffee' );
