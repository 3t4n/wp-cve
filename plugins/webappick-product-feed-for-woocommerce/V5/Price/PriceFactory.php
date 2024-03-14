<?php

namespace CTXFeed\V5\Price;

/**
 * Class PriceFactory
 *
 * @package CTXFeed\V5\Price
 */
class PriceFactory {

	/**
	 * @param \WC_Product|\WC_Product_Simple|\WC_Product_Variable|\WC_Product_Variation|\WC_Product_Grouped $product WC
	 *                                                                                                               Product.
	 * @param \CTXFeed\V5\Utility\Config                                                                    $config  Config.
     * @return \CTXFeed\V5\Price\ProductPrice Product Price.
	 */
	public static function get( $product, $config ) {// phpcs:ignore
		if ( $product->is_type( 'variable' ) ) {
			/**
			 * Variable Product does not have its price. So it depends on variations.
			 */
			$class = new ProductPrice( new VariableProductPrice( $product, $config ), $product );
		} elseif ( $product->is_type( 'grouped' ) ) {
			/**
			 * Grouped Product does not have its price. So it depends on a group of simple Products.
			 */
			$class = new ProductPrice( new GroupProductPrice( $product, $config ), $product );
		}elseif ( is_plugin_active( 'wpc-grouped-product/wpc-grouped-product.php' ) && $product->is_type( 'woosg' ) ) {
			/**
			 * Grouped Product does not have its price. So its depends on a group of simple Products.
			 * Plugin Name: WPC Grouped Product for WooCommerce.
			 */
			$class = new ProductPrice( new SgGroupProductPrice( $product, $config ), $product );
		}  else {
			/**
			 * Simple Product, External Product, Product Variation, YITH Composite etc.
			 * Note*: YITH does not auto select components. So no need to calculate component price.
			 */
			$class = new ProductPrice( new SimpleProductPrice( $product, $config ), $product );
		}

		return $class;
	}

}
