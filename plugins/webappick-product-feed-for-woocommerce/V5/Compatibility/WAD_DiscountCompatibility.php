<?php
/**
 * Compatibility class for WAD_DiscountCompatibility plugin
 *
 * @package CTXFeed\V5\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

use CTXFeed\V5\Helper\ProductHelper;
use CTXFeed\V5\Utility\Cache;

/**
 * Class WAD_DiscountCompatibility
 *
 * PLUGIN: Conditional Discounts for WooCommerce
 * URL: https://wordpress.org/plugins/woo-advanced-discounts/
 *
 * @package CTXFeed\V5\Compatibility
 */
class WAD_DiscountCompatibility {

	/**
	 * WAD_DiscountCompatibility Constructor.
	 */
	public function __construct() {
		// Get price with discount.
		add_filter( 'woo_feed_filter_product_price', array( $this, 'get_discounted_price' ), 99999999, 5 );
		add_filter( 'woo_feed_filter_product_sale_price', array( $this, 'get_discounted_price' ), 99999999, 5 );
		add_filter( 'woo_feed_filter_product_price_with_tax', array( $this, 'get_discounted_price' ), 99999999, 5 );
		add_filter( 'woo_feed_filter_product_sale_price_with_tax', array( $this, 'get_discounted_price' ), 99999999, 5 );
	}

	/**
	 * Get Discounted Price
	 *
	 * @param int                        $price product price.
	 * @param \WC_Product                $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool                       $with_tax price with tax or without tax.
	 * @param string                     $price_type price type regular_price, price, sale_price.
     * @return int
	 */
	public function get_discounted_price( $price, $product, $config, $with_tax, $price_type ) { //phpcs:ignore
		if ( ! Cache::get( 'wad_discounts' ) ) {
			Cache::set( 'wad_discounts', wad_get_active_discounts( true ) );
			$wad_discounts = Cache::get( 'wad_discounts' );
			$price         = self::add_discounts( $wad_discounts, $price, $product, $config, $with_tax, $price_type );
		} else {
			$wad_discounts = Cache::get( 'wad_discounts' );
			$price         = self::add_discounts( $wad_discounts, $price, $product, $config, $with_tax, $price_type );
		}

		return $price;
	}

	/**
	 * Get Discounted Price
	 *
	 * @param array                      $wad_discounts product price.
	 * @param int                        $price product price.
	 * @param \WC_Product                $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool                       $with_tax price with tax or without tax.
	 * @param string                     $price_type price type regular_price, price, sale_price.
     * @return int
	 */
	private static function add_discounts( $wad_discounts, $price, $product, $config, $with_tax, $price_type ) { // phpcs:ignore
		if ( ! isset( $wad_discounts['product'] ) ) {
			return $price;
		}

		$original_price = $price;

		if ( isset( $wad_discounts['product'] ) ) {
			$discounted_amount = 0;

			foreach ( $wad_discounts['product'] as $discount_id ) {
				$wad_obj    = new \WAD_Discount( $discount_id );
				$is_disable = $wad_obj->settings['disable-on-product-pages'];

				if ( !$wad_obj->is_applicable( $product->get_id() ) || $is_disable !== 'no' ) {
                    continue;
                }

                $discounted_amount += (float) $wad_obj->get_discount_amount( $product->get_price() );
			}

			if ( $discounted_amount > 0 && empty( $price ) ) {
				$price = (float) $product->get_price() - $discounted_amount;
			} else {
				$price = $original_price;
			}
		}

		if ( $with_tax ) {
			$price = ProductHelper::get_price_with_tax( $price, $product );
		}

		return $price;
	}

}
