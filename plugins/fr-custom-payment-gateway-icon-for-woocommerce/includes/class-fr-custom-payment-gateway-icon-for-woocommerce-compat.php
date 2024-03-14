<?php

/**
 * Define compatibility with other plugins.
 *
 * @since      1.1.2
 * @package    Fr_Custom_Payment_Gateway_Icon_For_WooCommerce
 * @subpackage Fr_Custom_Payment_Gateway_Icon_For_WooCommerce/includes
 * @author     Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Custom_Payment_Gateway_Icon_For_WooCommerce_Compat {

	/**
	 * Declare HPOS compatibility. 
	 */
	public function custom_order_tables() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', FR_CUSTOM_PAYMENT_GATEWAY_ICON_FOR_WOOCOMMERCE_FILE, true );
		}
	}

}
