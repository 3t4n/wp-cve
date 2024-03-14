<?php
/**
 * Theme Name:  Biagiotti
 * Version: 2.0
 * Author: Mikado Themes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Biagiotti {

	public function __construct() {
		add_action( 'wfacp_checkout_page_found', [ $this, 'unhook_theme_actions' ] );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'unhook_theme_actions' ] );
		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );


	}

	public function unhook_theme_actions() {
		if ( function_exists( 'ts_product_image_on_checkout' ) ) {
			remove_filter( 'woocommerce_cart_item_name', 'ts_product_image_on_checkout' );
		}
		if ( function_exists( 'woocommerce_checkout_coupon_form_custom' ) ) {
			remove_action( 'woocommerce_review_order_after_cart_contents', 'woocommerce_checkout_coupon_form_custom' );
		}


	}

	public function wfacp_internal_css() {
		if ( ! function_exists( 'ts_product_image_on_checkout' ) ) {
			return;
		}
		?>
        <style>
            table tbody tr, table thead tr {
                border: none;
            }
        </style>
		<?php
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Biagiotti(), 'wfacp-Biagiotti' );
