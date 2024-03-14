<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Name: Rey Core
 * Version: 1.9.5
 * Author: ReyTheme
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_ReyCore {

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'add_action' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function add_action() {
		if ( function_exists( 'reycore_wc__cart_progress' ) ) {
			remove_action( 'woocommerce_before_checkout_form', 'reycore_wc__cart_progress', 5 );
		}
	}

	public function internal_css() {
		?>
        <style>
            body .wfacp_main_form.woocommerce #wfacp_checkout_form .form-row,
            body .wfacp_main_form.woocommerce #wfacp_checkout_form .rey-form-row {
                display: block;
            }

            body .wfacp_shipping_options #wfacp_checkout_form .cart-discount {
                display: none;
            }

            table tbody tr,
            table thead tr {
                border: none;
            }
        </style>
		<?php
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_ReyCore(), 'wfacp-reycore' );
