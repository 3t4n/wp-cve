<?php
/**
 * Compatibility for WooCommerce Composite Product.
 *
 * @package CTXFeed\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

use CTXFeed\V5\Helper\ProductHelper;

/**
 * Class WCCompositeProduct
 *
 * @package CTXFeed\V5\Compatibility
 */
class WC_Composite_ProductsCompatibility {

	/**
	 * WCCompositeProduct constructor.
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
	 * @param \WC_Product_Composite      $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool                       $with_tax price with tax or without tax.
	 * @param string                     $price_type price type regular_price, price, sale_price.
	 * @return float|int|string
	 */
	public function composite_price( $price, $product, $config, $with_tax = false, $price_type = 'price' ) {//phpcs:ignore
		$method = 'get_' . $price_type;

		if ( $product instanceof \WC_Product ) {
			$price = $product->$method();
		}

		if ( ! class_exists( 'WC_Product_Composite' ) || ! $product->is_type( 'composite' ) ) {
			return $price;
		}

		$feed_rules = $config->get_config();

		if ( isset( $feed_rules['composite_price'] ) && 'all_product_price' === $feed_rules['composite_price'] ) {
			if ( $price_type === 'regular_price' ) {
				if ( $with_tax ) {
					$price = $product->get_composite_regular_price_including_tax();
				} else {
					$price = $product->get_composite_regular_price();
				}
			} elseif ( $with_tax ) {
					$price = $product->get_composite_price_including_tax();
			} else {
				$price = $product->get_composite_price();
			}

			// Get WooCommerce Multi language Price by Currency.
			$price = $this->convert_currency( $price, $price_type, $product, $config );
		}

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
		return $this->composite_price( $price, $product, $config, $with_tax, 'regular_price', $price_type );
	}

	/**
	 * Get Price.
	 *
	 * @param int                        $price product price.
	 * @param \WC_Product                $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool                       $with_tax price with tax or without tax.
	 * @param string                     $price_type price type regular_price, price, sale_price.
     * @return float|int
	 */
	public function price( $price, $product, $config, $with_tax, $price_type ) {
		return $this->composite_price( $price, $product, $config, $with_tax, $price_type );
	}

	/**
	 * Get Sale Price.
	 *
	 * @param int                        $price product price.
	 * @param \WC_Product_Composite      $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool                       $with_tax price with tax or without tax.
	 * @param string                     $price_type price type regular_price, price, sale_price.
	 * @return float|int|string
	 */
	public function sale_price( $price, $product, $config, $with_tax, $price_type ) {
		return $this->composite_price( $price, $product, $config, $with_tax, $price_type );
	}

	/**
	 * Convert currency if WooCommerce Multi language plugin is active.
	 *
	 * @param int                        $price product price.
	 * @param string                     $price_type price type regular_price, price, sale_price.
	 * @param \WC_Product_Composite      $product product object.
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
	 * @param int                   $price product price.
	 * @param \WC_Product_Composite $product product object.
	 * @param bool                  $with_tax price with tax or without tax.
	 * @return float|int|string
	 */
	public function add_tax( $price, $product, $with_tax = false ) {
		if ( true === $with_tax ) {
			return ProductHelper::get_price_with_tax( $price, $product );
		}

		return $price;
	}

}
