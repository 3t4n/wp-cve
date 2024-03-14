<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WooCommerce_Payments {
	public function __construct() {
		add_action( 'wfacp_internal_css', [ $this, 'enqueue_scripts' ] );
		add_action( 'wfacp_outside_header', [ $this, 'detect_woo_payment' ] );
	}

	/*
	 * This code only work if woo_payment enabled from WooCommerce Settings
	 */
	public function detect_woo_payment() {
		$instance = WFACP_Common::remove_actions( 'woocommerce_checkout_billing', 'WC_Payments', 'woopay_fields_before_billing_details' );
		if ( $instance == 'WC_Payments' ) {
			add_action( 'wfacp_internal_css', [ $this, 'css' ] );
			add_action( 'wfacp_after_billing_email_field', [ 'WC_Payments', 'woopay_fields_before_billing_details' ] );
			add_filter( 'woocommerce_form_field_args', [ $this, 'add_aero_basic_classes' ], 10, 2 );
		}

	}

	public function css() {
		echo "<style>div#contact_details > h3{display:none}div#contact_details {clear: both;}</style>";
	}

	public function add_aero_basic_classes( $field, $key ) {
		if ( $key === 'billing_email' ) {
			$field['input_class'][] = 'wfacp-form-control';
			$field['class'][]       = 'wfacp-form-control-wrapper';
			$field['label_class'][] = 'wfacp-form-control-label';
		}

		return $field;
	}

	public function enqueue_scripts() {
		if ( is_null( WC()->cart ) || WC()->cart->needs_payment() ) {
			return;
		}
		$gateways = WC()->payment_gateways()->get_available_payment_gateways();

		if ( ! isset( $gateways['woocommerce_payments'] ) ) {
			return;
		}

		$gateway = $gateways['woocommerce_payments'];

		/**
		 * @var $gateway WC_Payment_Gateway_WCPay
		 */
		if ( method_exists( $gateway, 'get_payment_fields_js_config' ) ) {
			wp_localize_script( 'wcpay-checkout', 'wcpay_config', $gateway->get_payment_fields_js_config() );
			wp_enqueue_script( 'wcpay-checkout' );
			wp_enqueue_style( 'wcpay-checkout', plugins_url( 'dist/checkout.css', WCPAY_PLUGIN_FILE ), [], WC_Payments::get_file_version( 'dist/checkout.css' ) );
		}

	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WooCommerce_Payments(), 'woocommerce_checkout' );

