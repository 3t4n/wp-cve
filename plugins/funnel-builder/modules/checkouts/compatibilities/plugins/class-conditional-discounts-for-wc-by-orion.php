<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Conditional Discounts for WooCommerce by ORION (v. 4.5.0)
 */

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_CDFWBO {


	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_action' ] );
	}

	public function is_enable() {
		return class_exists( 'WAD_UI_Builder' );
	}

	public function remove_action() {
		if ( ! $this->is_enable() ) {
			return;
		}
		WFACP_Common::remove_actions( 'woocommerce_before_checkout_form', 'WAD_UI_Builder', 'add_alternative_coupon_form' );
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_CDFWBO(), 'WPINC' );
