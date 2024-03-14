<?php

/**
 * === Chronopost and Chronofood by WooChrono ===
 * Chronopost and Chronofood for woocommerce
 */
#[AllowDynamicProperties] 

  class WFACP_Chronopost_Food {
	public function __construct() {
		add_action( 'wfacp_mini_cart_before_order_total', [ $this, 'remove_actions' ] );
		add_action( 'wfacp_mini_cart_after_order_total', [ $this, 'add_actions' ] );
	}

	public function remove_actions() {
		remove_filter( 'woocommerce_locate_template', 'wc_cart_shipping_template', 10, 6 );
		remove_filter( 'woocommerce_locate_template', 'wc_cart_totals_template', 10, 6 );
	}

	public function add_actions() {
		add_filter( 'woocommerce_locate_template', 'wc_cart_shipping_template', 10, 6 );
		add_filter( 'woocommerce_locate_template', 'wc_cart_totals_template', 10, 6 );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Chronopost_Food(), 'chronopost_food' );


