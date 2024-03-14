<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * By https://www.themehigh.com
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_CheckoutFields {
	public function __construct() {

		add_action( 'wfacp_before_get_address_field_admin', [ $this, 'remove_locale_my_parcel_nederlend' ] );
		add_action( 'wfacp_checkout_page_found', [ $this, 'actions' ] );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'actions' ] );
	}

	public function remove_locale_my_parcel_nederlend() {
		WFACP_Common::remove_actions( 'woocommerce_get_country_locale', 'Woocommerce_MyParcel_Postcode_Fields', 'woocommerce_locale_nl' );
	}

	public function actions() {
		// Woocommerce checkout field editor by theme high
		if ( function_exists( 'thwcfd_init_checkout_field_editor_lite' ) ) {

			remove_filter( 'woocommerce_default_address_fields', 'thwcfd_woo_default_address_fields' );
			remove_filter( 'woocommerce_get_country_locale_default', 'thwcfd_prepare_country_locale' );
			remove_filter( 'woocommerce_get_country_locale_base', 'thwcfd_prepare_country_locale' );

			remove_filter( 'woocommerce_get_country_locale', 'thwcfd_woo_get_country_locale' );
			remove_filter( 'woocommerce_billing_fields', 'thwcfd_billing_fields_lite', apply_filters( 'thwcfd_billing_fields_priority', 1000 ) );
			remove_filter( 'woocommerce_shipping_fields', 'thwcfd_shipping_fields_lite', apply_filters( 'thwcfd_shipping_fields_priority', 1000 ) );
			remove_filter( 'woocommerce_checkout_fields', 'thwcfd_checkout_fields_lite', apply_filters( 'thwcfd_checkout_fields_priority', 1000 ) );
		}
		// Woocommerce checkout manager
		if ( defined( 'WOOCCM_PATH' ) ) {
			remove_filter( 'woocommerce_checkout_fields', 'wooccm_remove_fields_filter_billing', 15 );
			remove_filter( 'woocommerce_checkout_fields', 'wooccm_remove_fields_filter_shipping', 1 );
			remove_filter( 'woocommerce_billing_fields', 'wooccm_checkout_billing_fields' );
			remove_filter( 'woocommerce_default_address_fields', 'wooccm_checkout_default_address_fields' );
			remove_filter( 'woocommerce_shipping_fields', 'wooccm_checkout_shipping_fields' );
			remove_action( 'woocommerce_checkout_fields', 'wooccm_order_notes' );
		}

		// Official
		if ( function_exists( 'woocommerce_init_checkout_field_editor' ) ) {
			remove_filter( 'woocommerce_checkout_fields', 'wc_checkout_fields_modify_order_fields', 1000 );
		}
	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_CheckoutFields(), 'CheckoutFields' );


