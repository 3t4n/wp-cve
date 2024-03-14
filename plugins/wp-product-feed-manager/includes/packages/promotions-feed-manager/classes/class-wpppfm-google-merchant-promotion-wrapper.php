<?php

/**
 * WPPPFM Google Merchant Promotion_Wrapper.
 *
 * @package WP Google Merchant Promotions Feed Manager/Classes
 * @since 2.41.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPPFM_Google_Merchant_Promotion_Wrapper' ) ) :

	class WPPPFM_Google_Merchant_Promotion_Wrapper {

		private $promotion_nr;

		public function __construct( $promotion_nr = 'template' ) {
			$this->promotion_nr = $promotion_nr;
		}

		/**
		 * Display the product feed attribute mapping table.
		 */
		public function display() {
			// Start with the promotion template code.
			$html  = $this->promotion_wrapper();

			$html .= $this->promotion_header_buttons();

			$html .= $this->mandatory_promotion_fields();

			$html .= $this->product_filters_table();

			$html .= $this->product_details_table();

			$html .= $this->end_of_promotion_wrapper();

			return $html;
		}

		/**
		 * Returns the html code for the promotion wrapper. The promotion wrapper contains all the promotion fields, divided over the header buttons, mandatory fields, product filters and product details.
		 */
		private function promotion_wrapper() {
			return '<section class="wpppfm-promotion-wrapper" id="wpppfm-promotion-wrapper-' . $this->promotion_nr . '" style="display: none">';
		}

		/**
		 * Returns the html code for the promotion header buttons.
		 */
		private function promotion_header_buttons() {
			$html  = '<section class="wpppfm-promotion-header-buttons" id="wpppfm-promotion-buttons-' . $this->promotion_nr . '">';
			$html .= '<a href="javascript:void(0);" id="wpppfm-promotion-add-button-' . $this->promotion_nr . '" class="wpppfm-promotion-header-button" onclick="wpppfm_addPromotion()">' . __( 'Add', 'wp-product-feed-manager' ) . '</a>  ';
			$html .= '<a href="javascript:void(0);" id="wpppfm-promotion-delete-button-' . $this->promotion_nr . '" class="wpppfm-promotion-header-button" onclick="wpppfm_deletePromotion(\'' . $this->promotion_nr . '\')">' . __( 'Delete', 'wp-product-feed-manager' ) . '</a>  ';
			$html .= '<a href="javascript:void(0);" id="wpppfm-promotion-duplicate-button-' . $this->promotion_nr . '" class="wpppfm-promotion-header-button" onclick="wpppfm_duplicatePromotion(\'' . $this->promotion_nr . '\')">' . __( 'Duplicate', 'wp-product-feed-manager' ) . '</a>';
			$html .= '</section>';
			return $html;
		}

		/**
		 * Returns the html code for the mandatory promotion fields.
		 */
		private function mandatory_promotion_fields() {
			$mandatory_fields_wrapper = new WPPPFM_Google_Merchant_Promotions_Feed_Mandatory_Input_Wrapper( $this->promotion_nr );
			return $mandatory_fields_wrapper->display();
		}

		/**
		 * Returns the html code for the product filters table.
		 */
		private function product_filters_table() {
			$product_filters_wrapper = new WPPPFM_Google_Merchant_Promotions_Feed_Product_Filters_Wrapper();
			return $product_filters_wrapper->display( $this->promotion_nr );
		}

		/**
		 * Returns the html code for the product details table.
		 */
		private function product_details_table() {
			$product_details_wrapper = new WPPPFM_Google_Merchant_Promotions_Feed_Product_Details_Wrapper();
			return $product_details_wrapper->display( $this->promotion_nr );
		}

		/**
		 * Returns the html code for the end of the promotion wrapper.
		 */
		private function end_of_promotion_wrapper() {
			return '</section>';
		}
	}

	// end of WPPPFM_Google_Merchant_Promotion_Wrapper class

endif;

