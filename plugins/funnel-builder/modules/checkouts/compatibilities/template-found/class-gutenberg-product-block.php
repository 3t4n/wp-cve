<?php

/**
 *  WooCommerce Blocks
 * https://github.com/woocommerce/woocommerce-gutenberg-products-block
 * #[AllowDynamicProperties] 

  class WFACP_GutenBerg_Product_Block
 */
#[AllowDynamicProperties] 

  class WFACP_GutenBerg_Product_Block {
	public function __construct() {
		add_action( 'wfacp_after_template_found', [ $this, 'remove_gutenberg_action' ] );
	}

	public function remove_gutenberg_action() {
		WFACP_Common::remove_actions( 'wp_print_scripts', 'Automattic\WooCommerce\Blocks\Payments\Api', 'verify_payment_methods_dependencies' );
	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_GutenBerg_Product_Block(), 'gbpb' );
