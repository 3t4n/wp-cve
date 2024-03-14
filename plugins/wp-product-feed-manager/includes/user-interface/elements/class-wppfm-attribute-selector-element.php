<?php

/**
 * WPPFM Attribute Selector Element Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Attribute_Selector_Element' ) ) :

	class WPPFM_Attribute_Selector_Element {

		/**
		 * Returns the code for the required fields.
		 *
		 * @return string
		 */
		public static function required_fields() {
			return '<div class="wppfm-feed-editor-attributes-wrapper" id="wppfm-required-fields" style="display:block;">
				<legend class="wppfm-feed-editor-attributes__label">
				<h4 id="wppfm-required-attributes-header">' . __( 'Required attributes', 'wp-product-feed-manager' ) . ':</h4>
				</legend>'
				. self::attributes_wrapper_table_header() .
				   '<div class="wppfm-feed-editor-attributes__table" id="wppfm-required-field-table"></div>
				</div>';
		}

		/**
		 * Returns the code for the highly recommended fields.
		 *
		 * @return string
		 */
		public static function highly_recommended_fields() {
			return '<div class="wppfm-feed-editor-attributes-wrapper" id="wppfm-highly-recommended-fields" style="display:none;">
				<legend class="wppfm-feed-editor-attributes__label">
				<h4 id="wppfm-highly-recommended-attributes-header">' . __( 'Highly recommended attributes', 'wp-product-feed-manager' ) . ':</h4>
				</legend>'
				. self::attributes_wrapper_table_header() .
				   '<div class="wppfm-feed-editor-attributes__table" id="wppfm-highly-recommended-field-table"></div>
				</div>';
		}

		/**
		 * Returns the code for the recommended fields.
		 *
		 * @return string
		 */
		public static function recommended_fields() {
			return '<div class="wppfm-feed-editor-attributes-wrapper" id="wppfm-recommended-fields" style="display:none;">
				<legend class="wppfm-feed-editor-attributes__label">
				<h4 id="wppfm-recommended-attributes-header">' . __( 'Recommended attributes', 'wp-product-feed-manager' ) . ':</h4>
				</legend>'
				. self::attributes_wrapper_table_header() .
				   '<div class="wppfm-feed-editor-attributes__table" id="wppfm-recommended-field-table"></div>
				</div>';
		}

		/**
		 * Returns the code for the optional fields.
		 *
		 * @return string
		 */
		public static function optional_fields() {
			return '<div class="wppfm-feed-editor-attributes-wrapper" id="wppfm-optional-fields" style="display:block;">
				<legend class="wppfm-feed-editor-attributes__label">
				<h4 id="wppfm-optional-attributes-header">' . __( 'Optional attributes', 'wp-product-feed-manager' ) . ':</h4>
				</legend>'
				. self::attributes_wrapper_table_header() .
				   '<div class="wppfm-feed-editor-attributes__table" id="wppfm-optional-field-table"></div>
				</div>';
		}

		/**
		 * Returns the code for the custom fields.
		 *
		 * @return string
		 */
		public static function custom_fields() {
			return '<div class="wppfm-feed-editor-attributes-wrapper" id="wppfm-custom-fields" style="display:block;">
				<legend class="wppfm-feed-editor-attributes__label">
				<h4 id="wppfm-custom-attributes-header">' . __( 'Custom attributes', 'wp-product-feed-manager' ) . ':</h4>
				</legend>'
				. self::attributes_wrapper_table_header() .
				   '<div class="wppfm-feed-editor-attributes__table" id="wppfm-custom-field-table"></div>
				</div>';
		}

		/**
		 * Returns the feed form table titles
		 *
		 * @return string
		 */
		private static function attributes_wrapper_table_header() {
			return '<div class="wppfm-feed-editor-attributes__table-header">
				<div class="wppfm-column-header wppfm-col20w">' . __( 'Attributes', 'wp-product-feed-manager' ) . '</div>
				<div
					class="wppfm-column-header wppfm-col30w">' . __( 'From WooCommerce source', 'wp-product-feed-manager' ) . '</div>
				<div class="wppfm-column-header wppfm-col40w">' . __( 'Condition', 'wp-product-feed-manager' ) . '</div>
			</div>';
		}
	}

	// end of WPPFM_Attribute_Selector_Element class

endif;
