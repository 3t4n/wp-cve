<?php
/**
 * Compatibility for WooCommerce Composite Product.
 *
 * @package CTXFeed\Compatibility
 */

namespace CTXFeed\V5\Compatibility;

/**
 * Class WPCCompositeProduct
 *
 * @package CTXFeed\V5\Compatibility
 */
class WPCleverWoocoCompatibility {

	/**
	 * WPCCompositeProductCompatibility constructor.
	 */
	public function __construct() {
		add_filter( 'woo_feed_filter_product_regular_price', array( $this, 'composite_price' ), 10, 5 );// regular_price
		add_filter( 'woo_feed_filter_product_price', array( $this, 'composite_price' ), 10, 5 ); // price
		add_filter( 'woo_feed_filter_product_sale_price', array( $this, 'composite_price' ), 10, 5 ); // sale_price
		add_filter( 'woo_feed_filter_product_regular_price_with_tax', array( $this, 'composite_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_price_with_tax', array( $this, 'composite_price' ), 10, 5 );
		add_filter( 'woo_feed_filter_product_sale_price_with_tax', array( $this, 'composite_price' ), 10, 5 );
	}


	/**
	 *  Get Bundle Price, Regular Price, Sale Price.
	 *
	 * @param int $price product price.
	 * @param \WC_Product_Composite $product product object.
	 * @param \CTXFeed\V5\Utility\Config $config config object.
	 * @param bool $with_tax price with tax or without tax.
	 * @param string $price_type price type regular_price, price, sale_price.
	 *
	 * @return float|int|mixed|string
	 */
	public function composite_price( $price, $product, $config, $with_tax = false, $price_type = 'price' ) {//phpcs:ignore
		if ( ! class_exists( 'WPCleverWooco' ) || ! $product->is_type( 'composite' ) ) {
			return $price;
		}

		// Parent Component Price
		$base_price = $price;
		$feed_rules = $config->get_config();

		if ( isset( $feed_rules['composite_price'] ) && 'all_product_price' === $feed_rules['composite_price'] ) {
			$components_price = 0;
			$components       = $product->get_components();
			if ( ! empty( $components ) && is_array( $components ) ) {
				foreach ( $components as $component ) {
					if ( ! isset( $component['products'] ) || empty( $component['products'] ) ) {
						continue;
					}
					$products = $component['products'];
					foreach ( $products as $product_id ) {
						$default_product = wc_get_product( $product_id );

						if ( ! is_object( $default_product ) || ! $default_product->is_in_stock() ) {
							continue;
						}

						$quantity = 1;

						if ( isset( $component['qty'] ) && $component['qty'] > 0 ) {
							$quantity = $component['qty'];
						}

						if ( 'products' === $component['type'] && empty( $component['price'] ) ) {
							$components_price += $price;
							$components_price *= $quantity;
						} elseif ( 'products' === $component['type'] && ! empty( $component['price'] ) ) {
							$clever           = new \WPCleverWooco;
							$old_price        = $price;
							$new_price        = $component['price'];
							$components_price += $clever::get_new_price( $old_price, $new_price );
							$components_price *= $quantity;
						}

						break; // Get first in stock product from component options.
					}
				}

				// Apply discount to components price.
				$discount = $product->get_discount();

				if ( $discount > 0 ) {
					$components_price -= $discount / 100 * $components_price;
				}
			}

			if ( 'exclude' === $product->get_pricing() ) {
				$price = $components_price;
			} elseif ( 'include' === $product->get_pricing() ) {
				$price = $components_price + $base_price;
			} elseif ( 'only' === $product->get_pricing() ) {
				$price = $base_price;
			}
		} else {
			$price = $base_price;
		}

		if ( $price > 0 ) {
			return $price;
		}

		return '';
	}

}
