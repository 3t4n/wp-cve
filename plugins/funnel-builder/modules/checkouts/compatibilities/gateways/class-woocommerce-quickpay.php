<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *  WooCommerce QuickPay
 * By Perfect Solution
 * http://wordpress.org/plugins/woocommerce-quickpay/
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_QuickPay
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_QuickPay {
	public function __construct() {
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'remove_hooks' ] );
	}

	public function remove_hooks() {
		if ( 'mobilepay_checkout' !== filter_input( INPUT_POST, "payment_method", FILTER_UNSAFE_RAW ) ) {
			return;
		}
		$template = wfacp_template();
		if ( is_null( $template ) ) {
			return;
		}

		remove_action( 'woocommerce_checkout_fields', [ $template, 'woocommerce_checkout_fields' ], 0 );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_QuickPay(), 'quick_pay' );
