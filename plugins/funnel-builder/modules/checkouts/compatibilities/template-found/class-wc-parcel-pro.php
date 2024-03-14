<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Parcel Pro
 * Plugin URI: https://www.parcelpro.nl/koppelingen/woocommerce/
 * Author:    Parcel Pro
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_Parcel_Pro {
	private $instance = null;
	private $settings = null;

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_actions' ] );
	}

	public function remove_actions() {
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_checkout_after_order_review', 'Parcelpro_Public', 'add_input' );
		if ( ! $this->instance instanceof Parcelpro_Public || is_null( $this->instance ) ) {
			return;
		}
		add_action( 'woocommerce_checkout_before_customer_details', function () {
			$this->settings = get_option( 'woocommerce_parcelpro_shipping_settings' );
			if ( isset( $this->settings['login_id'] ) && ! empty( $this->settings['login_id'] ) ) {
				$this->instance->add_input();
			}
		} );

	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Parcel_Pro(), 'wfacp-wc-parcel-pro' );


