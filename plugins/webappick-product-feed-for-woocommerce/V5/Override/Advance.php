<?php

namespace CTXFeed\V5\Override;

use CTXFeed\V5\Utility\Config;

/**
 * Class Advance
 *
 * @package    CTXFeed\V5\Override
 * @subpackage CTXFeed\V5\Override
 */
class Advance {
	public function __construct() {
		add_filter( 'woo_feed_filter_product_attribute', [ $this, 'get_product_variation_attribute' ], 10, 4 );
	}

	/**
	 * Get Variation attribute values for only in stock variations.
	 *
	 * @param                                  $value
	 * @param                                  $attr
	 * @param \WC_Product|\WC_Product_Variable $product
	 * @param \CTXFeed\V5\Utility\Config       $config
	 *
	 * @return mixed|string
	 */
	public function get_product_variation_attribute( $value, $attr, $product, $config ) {
		//TODO: Make an Option on Filter tab and apply the logic here.
		// Also add the option to settings class

		if ( $config instanceof Config ) {
			$outOfStockEnabled = $config->remove_outofstock_product();
		} else {
			$outOfStockEnabled = $config['is_outOfStock'];
		}

		if ( $outOfStockEnabled && $product->is_type( 'variable' ) && $product->has_child() ) {
			$attr                = "pa_" . $attr;
			$child_ids           = $product->get_children();
			$variationAttributes = array_keys( $product->get_variation_attributes() );

			if ( ! in_array( $attr, $variationAttributes ) ) {
				return $value;
			}

			$newValue = []; //reset the value to concat by child values
			foreach ( $child_ids as $id ) {
				$child_product = wc_get_product( $id );
				$attr_value    = $child_product->get_attribute( $attr );

				if ( ! empty( $attr_value ) && ( $child_product->is_in_stock() || ( $child_product->managing_stock() && 0 < $child_product->get_stock_quantity() ) ) ) {
					$newValue [] = $attr_value;
				}
			}
			$value = ! empty( $newValue ) ? implode( ', ', $newValue ) : $value;
		}

		return $value;
	}
}
