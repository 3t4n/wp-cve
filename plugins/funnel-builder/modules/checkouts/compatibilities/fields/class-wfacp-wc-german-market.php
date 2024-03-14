<?php

/**
 * German Market By MarketPress
 * Plugin URI: https://marketpress.de/shop/plugins/woocommerce-german-market/
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_WC_German_Market {

	public function __construct() {
		if ( WFACP_Common::is_funnel_builder_3() ) {
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'add_field' ] );
		} else {
			add_action( 'init', [ $this, 'add_field' ], 20 );
		}

		add_action( 'wfacp_checkout_preview_form_start', [ $this, 're_display_payment_section' ] );
		add_action( 'wfacp_template_class_found', [ $this, 'paypal_payments' ] );
	}

	public function add_field() {
		new WFACP_Add_Address_Field( 'vat', array(
			'label'    => get_option( 'vat_options_label', __( 'EU VAT Identification Number (VATIN)', 'woocommerce-german-market' ) ),
			'cssready' => [ 'wfacp-col-left-half' ],
			'required' => false,
			'priority' => apply_filters( 'wcvat_vat_field_priority', 49 ),
		) );
	}

	public function re_display_payment_section() {
		add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
	}

	public function paypal_payments() {

		// Removing all Position of button render because we break the order - review fragment into multipart i.e Payment,Order Summary,Shipping Method
		remove_all_actions( 'woocommerce_paypal_payments_checkout_button_renderer_hook' );
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_German_Market(), 'wfacp-wc-german-market' );
