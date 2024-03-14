<?php
/**
 * Compatibility class for AWDP_DiscountCompatibility plugin
 *
 * @package CTXFeed\V5\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

use CTXFeed\V5\Helper\ProductHelper;

/**
 * Class AWDP_DiscountCompatibility
 *
 * PLUGIN: Dynamic Pricing With Discount Rules for WooCommerce
 * URL: https://wordpress.org/plugins/aco-woo-dynamic-pricing/
 *
 * @package CTXFeed\V5\Compatibility
 */
class AWDP_DiscountCompatibility {

	/**
	 * AWDP_DiscountCompatibility Constructor.
	 */
	public function __construct() {
		// Get price with discount.
		add_filter( 'woo_feed_filter_product_regular_price', array( $this, 'get_discounted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_price', array( $this, 'get_discounted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_sale_price', array( $this, 'get_discounted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_regular_price_with_tax', array( $this, 'get_discounted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_price_with_tax', array( $this, 'get_discounted_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_sale_price_with_tax', array( $this, 'get_discounted_price' ), 10, 5 );
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
		if ( class_exists( 'AWDP_Discount' ) ) {
			$prices = \AWDP_Discount::instance()->wdpWCPAPrice( $price, $product );

			if (
				isset( $prices['originalPrice'] )
				&& 'regular_price' === $price_type
				&& isset( $prices['price'] )
				&& $prices['price']
			) {
				$price = $prices['originalPrice'];
			} elseif ( isset( $prices['price'] ) && $prices['price'] ) {
				$price = $prices['price'];
			}
		}

		if ( $with_tax ) {
			$price = ProductHelper::get_price_with_tax( $price, $product );
		}

		return $price;
	}

}
