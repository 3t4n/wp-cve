<?php

/**
 * Free Gift For WooCommerce (Official WooCommerce)
 * By Developed by FantasticPlugins
 * https://woocommerce.com/products/free-gifts-for-woocommerce/
 * #[AllowDynamicProperties] 

  class WFACP_Free_Gift_By_WooCommerce
 */
#[AllowDynamicProperties] 

  class WFACP_Free_Gift_By_WooCommerce {
	public function __construct() {
		add_filter( 'wfacp_display_quantity_increment', [ $this, 'remove_quantity_incrementer' ], 10, 2 );
	}

	public function remove_quantity_incrementer( $status, $cart_item ) {
		if ( class_exists( 'FP_Free_Gift' ) && isset( $cart_item['fgf_gift_product'] ) ) {
			$status = false;
		}

		return $status;
	}
}
new WFACP_Free_Gift_By_WooCommerce();
