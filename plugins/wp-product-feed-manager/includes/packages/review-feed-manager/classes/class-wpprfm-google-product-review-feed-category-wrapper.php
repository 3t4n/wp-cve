<?php

/**
 * WPPRFM Google Product Review Feed Category Wrapper.
 *
 * @package WP Product Review Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPRFM_Google_Product_Review_Feed_Category_Wrapper' ) ) :

	class WPPRFM_Google_Product_Review_Feed_Category_Wrapper extends WPPFM_Category_Wrapper {

		public function display() {
			// Start with the section code.
			$html  = '<section class="wppfm-category-mapping-and-filter-wrapper">';
			$html .= '<section class="wpprfm-edit-review-feed-form-element-wrapper wppfm-category-mapping-wrapper" id="wppfm-category-map" style="display:none;">';
			$html .= '<div id="wppfm-review-feed-editor-category-mapping-header" class="wppfm-feed-editor-section__header"><h3>' . __( 'Category Selector', 'wp-product-review-feed-manager' ) . ':</h3></div>';
			$html .= '<table class="wppfm-category-mapping-table wppfm-table widefat" id="wppfm-review-feed-category-mapping-table">';
			// The category mapping table header.
			$html .= WPPFM_Category_Selector_Element::category_selector_table_head();
			$html .= '<tbody id="wppfm-category-selector-body">';
			// The content of the table.
			$html .= $this->category_table_content();
			$html .= '</tbody>';
			// Closing the section.
			$html .= '</table></section>';
			// Add the product filter element.
			$html .= $this->product_filter();
			$html .= '</section>';

			return $html;
		}
	}

	// end of WPPRFM_Google_Product_Review_Feed_Category_Wrapper class

endif;
