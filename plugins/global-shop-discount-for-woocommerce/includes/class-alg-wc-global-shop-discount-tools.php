<?php
/**
 * Global Shop Discount for WooCommerce - Tools Class
 *
 * @version 1.9.0
 * @since   1.9.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Global_Shop_Discount_Tools' ) ) :

class Alg_WC_Global_Shop_Discount_Tools {

	/**
	 * Constructor.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function __construct() {
		add_action( 'alg_wc_global_shop_discount_settings_saved', array( $this, 'run_tools' ) );
	}

	/**
	 * run_tools.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function run_tools() {

		// Save prices in DB for all products
		if ( 'yes' === get_option( 'alg_wc_global_shop_discount_tool_save_all_products', 'no' ) ) {
			update_option( 'alg_wc_global_shop_discount_tool_save_all_products', 'no' );
			$counter = $this->save_prices_for_all_products();
			if ( method_exists( 'WC_Admin_Settings', 'add_message' ) ) {
				WC_Admin_Settings::add_message( sprintf( esc_html__( 'Price saved for %d product(s).', 'global-shop-discount-for-woocommerce' ), $counter ) );
			}
		}

	}

	/**
	 * save_prices_for_all_products.
	 *
	 * @version 1.9.0
	 * @since   1.9.0
	 */
	function save_prices_for_all_products() {
		$counter = 0;

		$core = alg_wc_global_shop_discount()->core;

		if ( ! isset( $core->groups ) ) {
			$core->init();
		}

		foreach ( wc_get_products( array( 'limit' => -1 ) ) as $product ) {

			$price_raw = $core->get_product_price_raw( $product );
			$price_new = $core->add_global_shop_discount( $price_raw, $product, 'price' );

			if ( $price_raw != $price_new ) {
				$product->set_sale_price( $price_new );
				$product->save();
				$counter++;
			}

		}

		return $counter;
	}

}

endif;

return new Alg_WC_Global_Shop_Discount_Tools();
