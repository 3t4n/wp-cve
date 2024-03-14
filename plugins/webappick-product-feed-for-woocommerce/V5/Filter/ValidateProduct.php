<?php

namespace CTXFeed\V5\Filter;

use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\Utility\Config;
use CTXFeed\V5\Helper\CommonHelper;
use CTXFeed\V5\Utility\Logs;
use WC_Product;
use CTXFeed\V5\Filter\AdvanceFilter;

/**
 *
 */
class ValidateProduct {
	/**
	 * Validate Product.
	 *
	 * @param     $product
	 * @param     $config
	 * @param int $id Product id.
	 *
	 * @return mixed|void
	 */
	public static function is_valid( $product, $config, $id ) {
		$valid = true;
		// Skip for invalid products
		if ( ! is_object( $product ) ) {
			$valid = false;
			Logs::write_log( $config->filename, sprintf( 'Product with id: %s is not a valid object', $id ) );
		}

		// Skip orphaned variation.
		if ( $product->is_type( 'variation' ) && ! $product->get_parent_id() ) {
			$valid = false;
			Logs::write_log( $config->filename, sprintf( 'Orphaned Variation %s is skipped', $id ) );
		}


		// Remove unsupported product types.
		if ( ! in_array( $product->get_type(), CommonHelper::supported_product_types(), true ) ) {
			$valid = false;
			Logs::write_log( $config->filename, sprintf( 'Product with id: %s is a %s product. Product Type %s is not supported.', $id, $product->get_type(), $product->get_type() ) );
		}


		/**
		 * IMPORTANT:  all filters functionality can be done by database query ( only by WP_Query )
		 *      All filters are implemented on branch feature/CBT-160 with WP_Query.
		 *
		 * The benefit of using WP_Query is we don't have to use this Filter class. This Filter class are taking more time during feed generation.
		 *      Example: suppose after database query we get 10000 product ids. We have to check for every product is it valid or not.
		 *      On the Filter class there is a loop of 7 iterations. so ( 10000*7 = 70000 ) which is a huge time.
		 *
		 * On the other hand if we implement all types filter through WP_Query, these 70000 iterations can be skipped. Which is huge time saving.
		 *
		 */

		/**
		 * This filter hook should return false to exclude the product from feed.
		 */

		$filer = new Filter( $product, $config );
		if ( $filer->exclude() ) {
			$valid = false;
		}
		// Advance filter is only applied for pro version
		if ( Helper::is_pro() && ! AdvanceFilter::filter_product( $product, $config ) ) {
			$valid = false;
		}

		return apply_filters( 'ctx_validate_product_before_include', $valid, $product, $config );
	}
}
