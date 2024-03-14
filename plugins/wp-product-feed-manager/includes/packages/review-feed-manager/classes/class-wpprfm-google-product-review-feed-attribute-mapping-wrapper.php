<?php

/**
 * WPPRFM Google Product Review Feed Attribute Mapping Wrapper.
 *
 * @package WP Product Review Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPRFM_Google_Product_Review_Feed_Attribute_Mapping_Wrapper' ) ) :

	class WPPRFM_Google_Product_Review_Feed_Attribute_Mapping_Wrapper extends WPPFM_Attribute_Mapping_Wrapper {

		public function display() {
			// return the code for the attribute mapping area
			$html  = $this->attribute_mapping_wrapper_table_start( 'none' );

			// Add the header.
			$html .= $this->attribute_mapping_wrapper_table_header();

			$html .= '<div class="wppfm-feed-editor-form-section__body">';

			$html .= WPPFM_Attribute_Selector_Element::required_fields();

			$html .= WPPFM_Attribute_Selector_Element::optional_fields();

			$html .= '</div>';

			$html .= $this->attribute_mapping_wrapper_table_end();

			return $html;
		}
	}

	// end of WPPRFM_Google_Product_Review_Feed_Attribute_Mapping_Wrapper class

endif;
