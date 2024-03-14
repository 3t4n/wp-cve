<?php

/**
 * WooCommerce Bulk Discount
 * http://wordpress.org/plugins/woocommerce-bulk-discount/
 * #[AllowDynamicProperties] 

  class WFACP_Woo_Bulk_Discounting
 */
#[AllowDynamicProperties] 

  class WFACP_Woo_Bulk_Discounting {
	public function __construct() {
		add_action( 'wfacp_after_template_found', [ $this, 'actions' ] );
	}

	public function actions() {
		if ( ! WFACP_Core()->public->is_checkout_override() ) {
			WFACP_Common::remove_actions( 'woocommerce_cart_product_subtotal', 'Woo_Bulk_Discount_Plugin_t4m', 'filter_cart_product_subtotal' );
		}
	}
}

new WFACP_Woo_Bulk_Discounting();
