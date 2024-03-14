<?php

/**
 * WPPPFM Google Merchant Promotions Feed Mandatory Input Wrapper.
 *
 * @package WP Google Merchant Promotions Feed Manager/Classes
 * @since 2.41.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPPFM_Google_Merchant_Promotions_Feed_Mandatory_Input_Wrapper' ) ) :

	class WPPPFM_Google_Merchant_Promotions_Feed_Mandatory_Input_Wrapper {

		private $promotion_nr;

		public function __construct( $promotion_nr = 'template' ) {
			$this->promotion_nr = $promotion_nr;
		}

		public function display() {
			$html  = $this->mandatory_input_wrapper_table_start();

			// Promotion ID input
			$html .= WPPPFM_Main_Input_Selector_Element::promotion_id_input_element( $this->promotion_nr );

			// Products eligible for promotion selector
			$html .= WPPPFM_Main_Input_Selector_Element::products_eligible_for_promotion_select_element( $this->promotion_nr );

			// Coupon code required selector
			$html .= WPPPFM_Main_Input_Selector_Element::coupon_code_required_select_element( $this->promotion_nr );

			// Generic redemption code input
			$html .= WPPPFM_Main_Input_Selector_Element::generic_redemption_code_input_element( $this->promotion_nr );

			// Promotion Title input
			$html .= WPPPFM_Main_Input_Selector_Element::promotion_title_input_element( $this->promotion_nr );

			// Promotion Effective Date input
			$html .= WPPPFM_Main_Input_Selector_Element::promotion_effective_date_input_element( $this->promotion_nr );

			// Eligible for promotion selector
			$html .= WPPPFM_Main_Input_Selector_Element::eligible_channel_for_promotion_select_element( $this->promotion_nr );

			// Promotion destination selector
			$html .= WPPPFM_Main_Input_Selector_Element::promotion_destination_select_element( $this->promotion_nr );

			$html .= $this->mandatory_input_wrapper_table_end();

			return $html;
		}

		private function mandatory_input_wrapper_table_start() {
			return '<section class="wpppfm-edit-promotions-feed-form-element-wrapper wpppfm-mandatory-input-wrapper" id="wppfm-mandatory-input-wrapper-' . $this->promotion_nr . '"><table class="wppfm-feed-editor-main-input-table"><tbody id="wppfm-main-feed-data">';
		}

		private function mandatory_input_wrapper_table_end() {
			return '</tbody></table></section>';
		}
	}

	// end of WPPPFM_Google_Merchant_Promotions_Feed_Mandatory_Input_Wrapper class

endif;

