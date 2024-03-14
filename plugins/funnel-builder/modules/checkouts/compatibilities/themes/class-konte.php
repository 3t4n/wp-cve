<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Konte_Theme {
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_actions' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function remove_actions() {
		if ( ! class_exists( 'Konte_WooCommerce_Template_Checkout' ) ) {
			return;
		}
		WFACP_Common::remove_actions( 'woocommerce_before_checkout_form', 'Konte_WooCommerce_Template_Checkout', 'checkout_login_form' );
		WFACP_Common::remove_actions( 'woocommerce_before_checkout_form', 'Konte_WooCommerce_Template_Checkout', 'checkout_coupon_form' );


	}

	public function internal_css() {
		if ( ! class_exists( 'Konte_WooCommerce_Template_Checkout' ) ) {
			return;
		}
		?>
        <style>
            .woocommerce-info .svg-icon, .woocommerce-info .svg-icon {
                display: none;
            }
        </style>

		<?php
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Konte_Theme(), 'konte' );
