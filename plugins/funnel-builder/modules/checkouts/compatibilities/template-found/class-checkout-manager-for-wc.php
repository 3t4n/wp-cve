<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checkout Manager for WooCommerce By QuadLayers
 * Plugin URI: https://wordpress.org/plugins/woocommerce-checkout-manager/
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Checkout_Manager_For_WC {
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_locale' ] );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'remove_posted_data' ] );

	}

	public function remove_locale() {

		WFACP_Common::remove_actions( 'woocommerce_get_country_locale_default', 'WOOCCM_Fields_Handler', 'remove_fields_priority' );
		WFACP_Common::remove_actions( 'woocommerce_get_country_locale_base', 'WOOCCM_Fields_Handler', 'remove_fields_priority' );
		WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'WOOCCM_Checkout_Controller', 'enqueue_scripts' );

	}

	public function remove_posted_data() {
		WFACP_Common::remove_actions( 'woocommerce_checkout_posted_data', 'WOOCCM_Fields_Handler', 'remove_address_fields' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Checkout_Manager_For_WC(), 'wfacp-checkout-manager-wc' );


