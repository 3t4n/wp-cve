<?php
/**
 * Compatibility for WooCommerce Bundle Product.
 *
 * @package CTXFeed\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

use CTXFeed\V5\Helper\ProductHelper;

/**
 * Class IconicBundleProduct
 *
 * @package CTXFeed\V5\Compatibility
 */
class IconicBundleProduct {

	/**
	 * WCBundleProduct constructor.
	 */
	public function __construct() {
		add_filter( 'woo_feed_filter_product_regular_price', array( $this, 'regular_price' ), 10, 5 );// regular_price
		add_filter( 'woo_feed_filter_product_price', array( $this, 'price' ), 10, 5 ); // price
		add_filter( 'woo_feed_filter_product_sale_price', array( $this, 'sale_price' ), 10, 5 ); // sale_price
		add_filter(
			'woo_feed_filter_product_regular_price_with_tax',
			array(
				$this,
				'regular_price',
			),
			10,
			5
		); // regular_price with tax
		add_filter( 'woo_feed_filter_product_price_with_tax', array( $this, 'price' ), 10, 5 ); // price with tax
		add_filter(
			'woo_feed_filter_product_sale_price_with_tax',
			array(
				$this,
				'sale_price',
			),
			10,
			5
		); // sale_price with tax
	}

	/**
	 *  Get Bundle Price, Regular Price, Sale Price.
	 *
	 * @param int                        $price product price.
	 * @param \WC_Product_Bundled         $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool                       $with_tax price with tax or without tax.
	 * @param string                     $price_type price type regular_price, price, sale_price.
     * @return float|int|string
	 */
	public function bundle_price( $price, $product, $config, $with_tax = false, $price_type = 'price' ) {
		if ( ! class_exists( 'WC_Product_Bundled' ) || ! $product->is_type( 'bundle' )  ) {
			return $price;
		}

		$bundle = new \WC_Product_Bundled( $product->get_id() );

		$price_display = '';

		if ( ! is_null( $bundle->options['price_display'] ) ) {
			$price_display = $bundle->options['price_display'];
		}

		$product_ids = $bundle->options['product_ids'];

		// Set discount
		$discount = 0;

		if ( ! empty( $bundle->options['fixed_discount'] ) ) {
			$discount = $bundle->options['fixed_discount'];
		}

		// Get price
		if ( is_array( $product_ids ) ) {
			$prices = array();

			foreach ( $product_ids as $pid ) {
				$product = wc_get_product( $pid );

				switch ( $price_type ) {
					case 'regular_price':
						$prices[] = $product->get_regular_price();

						break;

					case 'sale_price':
						$prices[] = $product->get_sale_price();

						break;

					default:
						$prices[] = $product->get_price();

						break;
				}
			}

			if ( 'range' === $price_display ) {
				$price = min( $prices );
			} else {
				$price = array_sum( $prices );
			}
		}

		// Get sale price if discount enabled
		if ( $discount && 'regular_price' !== $price_type ) {
			$price -= $discount;
		}

		// Get WooCommerce Multi language Price by Currency.
		$price = $this->convert_currency( $price, $price_type, $product, $config );

		// Get Price with tax
		$price = $this->add_tax( $price, $product, $with_tax );

		if ( $price > 0 ) {
			return $price;
		}

		return '';
	}

	/**
	 * Get Regular Price.
	 *
	 * @param int                        $price product price.
	 * @param \WC_Product                $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool                       $with_tax price with tax or without tax.
	 * @param string                     $price_type price type regular_price, price, sale_price.
     * @return float|int|string
	 */
	public function regular_price( $price, $product, $config, $with_tax, $price_type ) {
		return $this->bundle_price( $price, $product, $config, $with_tax, 'regular_price', $price_type );
	}

	/**
	 * Get Price.
	 *
	 * @param int                        $price product price.
	 * @param \WC_Product                $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool                       $with_tax price with tax or without tax.
	 * @param string                     $price_type price type regular_price, price, sale_price.
     * @return float|int|string
	 */
	public function price( $price, $product, $config, $with_tax, $price_type ) {
		return $this->bundle_price( $price, $product, $config, $with_tax, $price_type );
	}

	/**
	 * Get Sale Price.
	 *
	 * @param int                        $price product price.
	 * @param \WC_Product_Bundled         $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool                       $with_tax price with tax or without tax.
	 * @param string                     $price_type price type regular_price, price, sale_price.
     * @return float|int
	 */
	public function sale_price( $price, $product, $config, $with_tax, $price_type ) {
		return $this->bundle_price( $price, $product, $config, $with_tax, $price_type );
	}

	/**
	 * Convert currency if WooCommerce Multi language plugin is active.
	 *
	 * @param int                        $price product price.
	 * @param string                     $price_type price type regular_price, price, sale_price.
	 * @param \WC_Product_Bundled         $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
     * @return float|int|string
	 */
	public function convert_currency( $price, $price_type, $product, $config ) {
		return apply_filters(
			'woo_feed_wcml_price',
			$price,
			$product->get_id(),
			$config->get_feed_currency(),
			'_' . $price_type
		);
	}

	/**
	 * Should add tax or not.
	 *
	 * @param int                $price product price.
	 * @param \WC_Product_Bundled $product product object.
	 * @param bool               $with_tax price with tax or without tax.
     * @return float|int|string
	 */
	public function add_tax( $price, $product, $with_tax = false ) {
		if ( true === $with_tax ) {
			return ProductHelper::get_price_with_tax( $price, $product );
		}

		return $price;
	}

}
