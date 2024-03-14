<?php

/**
 * Compatibility class for WCCS_PricingCompatibility plugin
 *
 * @package CTXFeed\V5\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

/**
 * Class WCCS_PricingCompatibility
 *
 * PLUGIN: Easy woo-commerce discount plugin
 * URL: https://wordpress.org/plugins/easy-woocommerce-discounts/
 *
 * @package CTXFeed\V5\Compatibility
 */
class WCCS_PricingCompatibility {

	/**
	 * WCCS_PricingCompatibility Constructor.
	 */
	public function __construct() {
		add_action( 'before_woo_feed_generate_batch_data', array( $this, 'apply_discount' ) );
	}

	/**
	 * Apply discount for WCCS_PricingCompatibility plugin
	 *
	 * @return void
	 */
	public function apply_discount() {
		WCCS()->WCCS_Product_Price_Replace->set_should_replace_prices( true )->set_change_regular_price( false )->enable_hooks();
	}

}
