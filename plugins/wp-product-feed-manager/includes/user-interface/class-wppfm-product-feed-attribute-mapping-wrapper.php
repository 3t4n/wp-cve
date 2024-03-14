<?php

/**
 * WPPFM Product Feed Attribute Mapping Wrapper Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Product_Feed_Attribute_Mapping_Wrapper' ) ) :

	class WPPFM_Product_Feed_Attribute_Mapping_Wrapper extends WPPFM_Attribute_Mapping_Wrapper {

		/**
		 * Display the product feed attribute mapping table.
		 */
		public function display() {
			$html = '';

			// Start the section code.
			$html .= $this->attribute_mapping_wrapper_table_start();

			// Add the header.
			$html .= $this->attribute_mapping_wrapper_table_header();

			$html .= '<div class="wppfm-feed-editor-form-section__body">';

			$html .= WPPFM_Attribute_Selector_Element::required_fields();

			$html .= WPPFM_Attribute_Selector_Element::highly_recommended_fields();

			$html .= WPPFM_Attribute_Selector_Element::recommended_fields();

			$html .= WPPFM_Attribute_Selector_Element::optional_fields();

			$html .= WPPFM_Attribute_Selector_Element::custom_fields();

			$html .= '</div>';

			// Close the section.
			$html .= $this->attribute_mapping_wrapper_table_end();

			return $html;
		}
	}

	// end of WPPFM_Product_Feed_Attribute_Mapping_Wrapper class

endif;
