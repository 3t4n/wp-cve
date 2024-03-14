<?php
/**
 * Product Factory.
 *
 * @package CTXFeed\V5\Product
 */

namespace CTXFeed\V5\Product;

use CTXFeed\V5\Compatibility\CompatibilityFactory;
use CTXFeed\V5\File\FileFactory;
use CTXFeed\V5\Filter\ValidateProduct;
use CTXFeed\V5\Helper\ProductHelper;
use CTXFeed\V5\Override\OverrideFactory;
use CTXFeed\V5\Utility\Logs;
use WC_Product;

/**
 * Class ProductFactory
 *
 * @package CTXFeed\V5\Product
 */
class ProductFactory {

	/**
	 * Get content for the specified products.
	 *
	 * Retrieves product information based on provided IDs, configuration, and structure.
	 *
	 * @param array                      $ids       Array of product IDs.
	 * @param \CTXFeed\V5\Utility\Config $config    Configuration object.
	 * @param array                      $structure Data structure for the content.
     * @return \CTXFeed\V5\File\FileInfo Information about the fetched content.
	 * @throws \Exception If an error occurs during content retrieval.
	 */
	public static function get_content( $ids, $config, $structure ) {//phpcs:ignore
		$product_info = array();
		Logs::write_log( $config->get_feed_file_name(), 'Getting Products Information.' );
		Logs::write_log( $config->get_feed_file_name(), 'Validating Product' );

		/**
		 * Load Merchant Template Override File.
		 *
		 * Based current feed config all filters in the "ProductInfo" class will be added to respective class
		 * Example: If template is "google" then "Override\GoogleTemplate" class will be initialized
		 * and all filter from "ProductInfo" class will be applied for Google merchant specific requirement.
		 */
		OverrideFactory::TemplateOverride( $config );

		if ( $config->get_feed_template() !== 'googlereview' ) {
			foreach ( $ids as $id ) {
				$product = ProductHelper::get_product_object( $id, $config );

				// If product is a variation, then get the parent product.
				$parent_product = null;
				if($product instanceof  WC_Product) {
					if ( $product && $product->is_type( 'variation' ) ) {
						$parent_product = wc_get_product( $product->get_parent_id() );
					}

					// Validate Product and add for feed.
					if ( ! ValidateProduct::is_valid( $product, $config, $id ) ) {
						continue;
					}
					$product_info[] = self::get_product_info( $product, $structure, $config, array(), $parent_product );
				}else if( count( $product ) ) {
					foreach ( $product as $variation ) {

						if($variation instanceof  WC_Product) {
							if ( $variation && $variation->is_type( 'variation' ) ) {
								$parent_product = wc_get_product( $variation->get_parent_id() );
							}

							// Validate Product and add for feed.
							if ( ! ValidateProduct::is_valid( $variation, $config, $id ) ) {
								continue;
							}
							$product_info[] = self::get_product_info( $variation, $structure, $config, array(), $parent_product );
						}
					}

				}

			}
		} else {
			$product_info[] = $structure;
		}

		return FileFactory::get_file_data( $product_info, $config );
	}

	/**
	 * Get product information.
	 *
	 * Processes and returns information for a given product based on the specified structure and configuration.
	 *
	 * @param \WC_Product                $product        The product object.
	 * @param array                      $structure      The structure defining the attributes to retrieve.
	 * @param \CTXFeed\V5\Utility\Config $config         Configuration object.
	 * @param array                      $info           Additional information (if any).
	 * @param null                       $parent_product The parent product object (if any).
     * @return array The processed product information.
	 */

	public static function get_product_info( $product, $structure, $config, $info, $parent_product = null ) {
		$product_info = array();

		foreach ( $structure as $merchant_attribute => $attribute ) {
			if ( is_array( $attribute ) ) {
				$product_info[ $merchant_attribute ] = self::get_product_info( $product, $attribute, $config, $info, $parent_product );
			} elseif ( $config->get_feed_file_type() === 'xml' ) {
				$product_info[ $merchant_attribute ] = ProductHelper::get_attribute_value_by_type( $attribute, $product, $config, $merchant_attribute, $parent_product );
			} else {
				$product_info[ $merchant_attribute ] = self::get_csv_attribute_value( $attribute, $product, $config, $merchant_attribute, $parent_product );
			}
		}

		return $product_info;
	}

	/**
	 * Get CSV attribute value.
	 *
	 * Retrieves the value of a CSV-formatted attribute for a product.
	 *
	 * @param string                     $attribute          The attribute to be processed.
	 * @param mixed                      $product            The product object.
	 * @param \CTXFeed\V5\Utility\Config $config             Configuration object.
	 * @param mixed                      $merchant_attribute Merchant-specific attribute information.
	 * @param null                       $parent_product     The parent product object (if any).
     * @return mixed The value of the attribute or void if not found.
	 */
	public static function get_csv_attribute_value(
		$attribute,
		$product,
		$config,
		$merchant_attribute,
		$parent_product = null
	) {
		if ( ! $attribute ) {
			$attribute = '';
		}

		$values = array();

		// Check if attribute contains a comma and process accordingly
		if ( strpos( $attribute, ',' ) !== false ) {
			$separator  = ',';
			$attributes = explode( ',', $attribute );

			foreach ( $attributes as $attr ) {
				if ( strpos( $attr, ':' ) !== false ) {
					$values[] = self::get_csv_attribute_value( $attr, $product, $config, $merchant_attribute );
				} else {
					$values[] = ProductHelper::get_attribute_value_by_type( $attr, $product, $config, $merchant_attribute, $parent_product );
				}
			}

			return implode( $separator, array_filter( $values ) );
		}

		// Check if attribute contains a colon and process accordingly
		if ( strpos( $attribute, ':' ) !== false ) {
			$separator  = ':';
			$attributes = explode( ':', $attribute );

			foreach ( $attributes as $attr ) {
				$values[] = ProductHelper::get_attribute_value_by_type( $attr, $product, $config, $merchant_attribute, $parent_product );
			}

			return implode( $separator, array_filter( $values ) );
		}

		// Return the attribute value directly
		return ProductHelper::get_attribute_value_by_type( $attribute, $product, $config, $merchant_attribute, $parent_product );
	}

}
