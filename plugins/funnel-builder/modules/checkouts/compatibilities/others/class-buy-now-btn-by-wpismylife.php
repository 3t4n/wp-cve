<?php

/**
 * By WpISMyLife
 * https://wordpress.org/plugins/buy-now-woo/
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_Buy_Now_btn
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_Buy_Now_btn_WpISMyLife {
	private $instance = null;

	public function __construct() {
		$this->remove_action();
	}

	public function remove_action() {
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_is_checkout', 'Buy_Now_Woo\Plugin', 'woocommerce_is_checkout' );
		if ( $this->instance instanceof Buy_Now_Woo\Plugin ) {
			add_action( 'wp', [ $this, 'attach_action' ] );
		}
	}

	public function attach_action() {
		if ( $this->instance instanceof Buy_Now_Woo\Plugin ) {
			add_filter( 'woocommerce_is_checkout', [ $this->instance, 'woocommerce_is_checkout' ] );
		}
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Buy_Now_btn_WpISMyLife(), 'Buy_Now_btn_WpISMyLife' );
