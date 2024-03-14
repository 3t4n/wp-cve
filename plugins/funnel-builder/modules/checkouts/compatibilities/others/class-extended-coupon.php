<?php

/**
 * WooCommerce Extended Coupon Features PRO
 * By Soft79
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_Extended_Coupon_Pro
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_Extended_Coupon_Pro {
	public function __construct() {
		add_action( 'wfacp_before_coupon_apply', [ $this, 'remove_action' ] );

	}
	public function remove_action() {
		remove_action( 'woocommerce_before_calculate_totals', 'WC_Subscriptions_Coupon::remove_coupons' );
	}
}


new WFACP_Compatibility_Extended_Coupon_Pro();
