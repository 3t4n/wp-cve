<?php
/**
 * Compatibility class for RP_WCDPDCompatibility plugin
 *
 * @package CTXFeed\V5\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

use CTXFeed\V5\Helper\ProductHelper;

/**
 * Class RP_WCDPDCompatibility
 *
 * PLUGIN: WooCommerce Dynamic Pricing & Discounts
 * URL: https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts/7119279
 *
 * @package CTXFeed\V5\Compatibility
 */
class RP_WCDPDCompatibility {

	/**
	 * RP_WCDPDCompatibility Constructor.
	 */
	public function __construct() {
		// Get price with discount.
		add_filter( 'woo_feed_filter_product_regular_price', array( $this, 'get_discounted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_price', array( $this, 'get_discounted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_sale_price', array( $this, 'get_discounted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_regular_price_with_tax', array( $this, 'get_discounted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_price_with_tax', array( $this, 'get_discounted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_sale_price_with_tax', array( $this, 'get_discounted_price' ), 10, 5 );

		add_filter( 'rightpress_product_price_shop_calculate_by_price_test', '__return_false', 9999 );
	}

	/**
	 * Get Discounted Price
	 *
	 * @param int $price product price.
	 * @param \WC_Product $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool $with_tax price with tax or without tax.
	 * @param string $price_type price type regular_price, price, sale_price.
	 *
	 * @return int
	 */
	public function get_discounted_price( $price, $product, $config, $with_tax, $price_type ) { //phpcs:ignore

		$discount = new \RightPress_Product_Price_Shop;

		$price = $discount->get_price_for_product( $price, $price_type, $product );

		if ( $with_tax ) {
			$price = ProductHelper::get_price_with_tax( $price, $product );
		}

		return $price;
	}

}
