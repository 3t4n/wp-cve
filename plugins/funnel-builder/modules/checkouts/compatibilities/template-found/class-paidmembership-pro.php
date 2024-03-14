<?php

/**
 * Paid Memberships Pro - WooCommerce Add On
 * Author: Paid Memberships Pro
 * https://www.paidmembershipspro.com/add-ons/pmpro-woocommerce/
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_PaidMembershipWCPro {

	public function __construct() {

		add_action( 'wfacp_before_product_switcher_html', [ $this, 'before' ] );
		add_action( 'wfacp_after_product_switcher_html', [ $this, 'after' ] );
	}

	public function before() {
		remove_filter( 'woocommerce_is_purchasable', 'pmprowoo_is_purchasable' );
	}

	public function after() {
		if ( ! class_exists( 'pmprowoo_is_purchasable' ) ) {
			return;
		}
		add_filter( 'woocommerce_is_purchasable', 'pmprowoo_is_purchasable', 10, 2 );
	}


}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_PaidMembershipWCPro(), 'paid-membership-pro-woocommerce' );


