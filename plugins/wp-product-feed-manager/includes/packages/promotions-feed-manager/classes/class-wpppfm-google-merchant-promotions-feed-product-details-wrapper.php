<?php

/**
 * WPPPFM Google Merchant Promotions Feed Details Wrapper.
 *
 * @package WP Google Merchant Promotions Feed Manager/Classes
 * @since 2.39.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPPFM_Google_Merchant_Promotions_Feed_Product_Details_Wrapper' ) ) :

	class WPPPFM_Google_Merchant_Promotions_Feed_Product_Details_Wrapper extends WPPFM_Filter_Wrapper {

		public function display( $promotion_nr ) {

			// Start with the section code.
			$html  = WPPPFM_Promotions_Details_selector_Element::promotions_details_section_start( $promotion_nr );

			$html .= WPPPFM_Promotions_Details_selector_Element::promotions_details_section_header();

			$html .= WPPPFM_Promotions_Details_selector_Element::promotions_details_content_box( $promotion_nr );

			$html .= WPPPFM_Promotions_Details_Selector_Element::promotions_details_section_close();

			return $html;
		}
	}

	// end of WPPPFM_Google_Merchant_Promotions_Feed_Product_Details_Wrapper class

endif;

