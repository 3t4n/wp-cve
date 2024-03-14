<?php

namespace CTXFeed\V5\Output;

use CTXFeed\V5\Helper\CommonHelper;
use CTXFeed\V5\Helper\ProductHelper;
/**
 * Class CategoryMapping
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Output
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class CategoryMapping {


	 /**
	  * Return Category Mapping Values by Product Id [Parent Product for variation]
	  * @param            $categoryMappingName Category Mapping Name
	  * @param            $product_id Product ID / Parent Product ID for variation product
	 *
	  * @return string
	 */
	public static function getCategoryMappingValue( $categoryMappingName, $product_id ) {

		$getValue                           = maybe_unserialize( get_option( $categoryMappingName ) );
		$cat_map_value                      = '';
		$suggestive_category_list_merchants = array(
			'google',
			'facebook',
			'pinterest',
			'bing',
			'bing_local_inventory',
			'snapchat',
		);

		if ( ! isset( $getValue['cmapping'] ) && ! isset( $getValue['gcl-cmapping'] ) ) {
			return '';
		}

		//get product terms
		$categories = get_the_terms( $product_id, 'product_cat' );


		//get cmapping value
		if ( isset( $getValue['gcl-cmapping'] ) && in_array( $getValue['mappingprovider'], $suggestive_category_list_merchants, true ) ) {
			$cmapping = is_array( $getValue['gcl-cmapping'] ) ? array_reverse( $getValue['gcl-cmapping'], true ) : $getValue['gcl-cmapping'];
		} else {
			$cmapping = is_array( $getValue['cmapping'] ) ? array_reverse( $getValue['cmapping'], true ) : $getValue['cmapping'];
		}

		// Fixes empty mapped category issue
		if ( ! empty( $categories ) && is_array( $categories ) && count( $categories ) ) {
			$categories = array_reverse( $categories );
			foreach ( $categories as $category ) {
				if ( isset( $cmapping[ $category->term_id ] ) && ! empty( $cmapping[ $category->term_id ] ) ) {
					$cat_map_value = $cmapping[ $category->term_id ];
					break;
				}
			}
		}

		return $cat_map_value;
	}


	/**
	 * Get Category Mapping.
	 *
	 * @param $category
	 *
	 * @return false|mixed|null
	 */

	public function getCategoryMapping( $category ) {
		if ( strpos( $category, ProductHelper::PRODUCT_CATEGORY_MAPPING_PREFIX ) === false ) {
			$category = ProductHelper::PRODUCT_CATEGORY_MAPPING_PREFIX . $category;
		}

		return get_option( $category );
	}

	public function getCategoryMappings() {

		$category_mappings = CommonHelper::get_options( ProductHelper::PRODUCT_CATEGORY_MAPPING_PREFIX );
		$data              = array();
		if ( ! empty( $category_mappings ) ) {
			foreach ( $category_mappings as $mapping ) {
				$data[ $mapping->option_name ] = get_option( $mapping->option_name );
			}

			return $data;
		}

		return false;
	}

	/**
	 * Save Category Mapping.
	 *
	 * @param array $categoryMappingConfig
	 *
	 * @return bool
	 */
	public function saveCategoryMapping( $categoryMappingConfig ) {

		$data = array();

		$data['name'] = '';
		if ( isset( $categoryMappingConfig['mappingname'] ) ) {
			$data['name'] = sanitize_text_field( $categoryMappingConfig['mappingname'] );
		}

		// Set Option Name.
		if ( isset( $categoryMappingConfig['option'] ) &&
			 ! empty( $categoryMappingConfig['option'] ) &&
			 false !== strpos( $categoryMappingConfig['option'], ProductHelper::PRODUCT_CATEGORY_MAPPING_PREFIX ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		) {
			$option = sanitize_text_field( $categoryMappingConfig['option'] );
		} else {
			// generate unique one.
			$option = CommonHelper::unique_option_name( ProductHelper::PRODUCT_CATEGORY_MAPPING_PREFIX . $data['name'] );
		}

		if ( is_array( $categoryMappingConfig ) ) {
			$data = serialize( woo_feed_array_sanitize( $categoryMappingConfig ) );
		}

		return update_option( $option, $data );

	}

	/**
	 * Delete Category Mapping.
	 *
	 * @param $category
	 *
	 * @return bool
	 */
	public function deleteCategoryMapping( $category ) {
		if ( strpos( $category, ProductHelper::PRODUCT_CATEGORY_MAPPING_PREFIX ) === false ) {
			$category = ProductHelper::PRODUCT_CATEGORY_MAPPING_PREFIX . $category;
		}

		return delete_option( $category );
	}

}
