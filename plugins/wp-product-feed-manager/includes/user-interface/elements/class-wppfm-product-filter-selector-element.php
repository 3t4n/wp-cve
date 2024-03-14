<?php

/**
 * WPPFM Product Filter Selector Element Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.39.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Product_Filter_Selector_Element' ) ) :

	class WPPFM_Product_Filter_Selector_Element {

		/**
		 * Returns the code for the product filter selector that is used to select products that have to be included.
		 *
		 * @return string
		 */
		public static function include_products_input( $promotion_nr = 'template' ) {
			return '<tr class="wpppfm-main-feed-input-row" id="wpppfm-promotion-destination-select-row">
				<th id="wppfm-filter-selector-label"><label
					for="wpppfm-product-filter-selector-include-input-field-' . $promotion_nr . '">' . __( 'Include', 'wp-product-promotions-feed-manager' ) . '</label> :
				</th>
				<td><select class="wppfm-main-input-selector wppfm-select2-pillbox-selector" name="wpppfm-promotion-destination-select" id="wpppfm-product-filter-selector-include-input-field-' . $promotion_nr . '" data-attribute-key="product_filter_selector_include" multiple="multiple"></select>
				</td></tr>';
		}

		/**
		 * Returns the code for the product filter selector that is used to select product that have to be excluded.
		 *
		 * @return string
		 */
		public static function exclude_products_input( $promotion_nr = 'template' ) {
			return '<tr class="wpppfm-main-feed-input-row" id="wpppfm-promotion-destination-select-row">
				<th id="wppfm-filter-selector-label"><label
					for="wpppfm-product-filter-selector-exclude-input-field-' . $promotion_nr . '">' . __( 'Exclude', 'wp-product-promotions-feed-manager' ) . '</label> :
				</th>
				<td><select class="wppfm-main-input-selector wppfm-select2-pillbox-selector" name="wpppfm-promotion-destination-select" id="wpppfm-product-filter-selector-exclude-input-field-' . $promotion_nr . '" data-attribute-key="product_filter_selector_exclude" multiple="multiple"></select>
				</td></tr>';
		}
	}

	// end of WPPFM_Product_Filter_Selector_Element class

endif;
