<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Woo_Order_Signature_Pro {
	public function __construct() {
		add_action( 'wfacp_checkout_page_found', [ $this, 'call_wosl_hook' ] );
	}

	public function call_wosl_hook() {
		$final_arr = [
			'woocommerce_before_checkout_billing_form',
			'woocommerce_after_checkout_billing_form',
			'woocommerce_before_order_notes',
			'woocommerce_after_order_notes',
			'woocommerce_checkout_before_order_review',
			'woocommerce_checkout_after_order_review'
		];
		if ( ( ! empty( get_option( 'wc_settings_tab_signature_pad_display_position' ) ) ) ) {
			$signature_pad_display_position = get_option( 'wc_settings_tab_signature_pad_display_position' );
			if ( ! empty( $signature_pad_display_position ) && in_array( $signature_pad_display_position, $final_arr ) ) {
				remove_action( $signature_pad_display_position, 'swph_display_signature_pad' );
				add_action( 'wfacp_template_before_payment', 'swph_display_signature_pad', 10 );
			}

		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Woo_Order_Signature_Pro(), 'wosl-pro' );
