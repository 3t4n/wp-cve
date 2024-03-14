<?php

/**
 * WPPRFM Google Merchant Promotions Details Selector Element Class.
 *
 * @package WP Google Merchant Promotions Feed Manager/Classes/Elements
 * @since 2.39.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPPFM_Promotions_Details_Selector_Element' ) ) :

	class WPPPFM_Promotions_Details_Selector_Element {

		use WPPPFM_Product_Details_Selector_Box;

		public static function promotions_details_section_start( $promotion_nr ) {
			return '<section class="wpppfm-edit-promotions-feed-form-element-wrapper wpppfm-product-details-wrapper" id="wpppfm-product-details-map-' . $promotion_nr . '" style="display: none;">
				<div id="wpppfm-details-selector" class="wpppfm-details-selector wppfm-selector-box">';
		}

		public static function promotions_details_section_header() {
			return '<div id="wpppfm-details-header" class="wppfm-selector-box-header"><h2 class="wppfm-selector-box-header">' . __( 'Promotion Details Selector', 'wp-product-feed-manager' ) . ':</h2></div>';
		}

		/**
		 * Returns the Promotions Details content box code.
		 *
		 * @return string
		 */
		public static function promotions_details_content_box( $promotion_nr ) {
			$box_html  = '<div id="wpppfm-details-content-box' . $promotion_nr . '" class="wppfm-selector-box-content">';
			$box_html .= '<div class="wppfm-selector-box-content-panel-wrapper panel-wrap">';
			$box_html .= self::content_box( $promotion_nr );
			$box_html .= '</div></div>';

			return $box_html;
		}

		public static function promotions_details_section_close() {
			return '</div></section>';
		}
	}

	// end of WPPPFM_Promotions_Details_Selector_Element class

endif;
