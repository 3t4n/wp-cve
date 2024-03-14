<?php

/**
 * WPPRFM Google Merchant Promotions Main Input Selector Element Class.
 *
 * @package WP Google Merchant Promotions Feed Manager/Classes/Elements
 * @since 2.39.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPPFM_Main_Input_Selector_Element' ) ) :

	class WPPPFM_Main_Input_Selector_Element {

		/**
		 * Returns the file name input field code.
		 *
		 * @return string
		 */
		public static function file_name_input_element() {
			return '<tr class="wppfm-main-feed-input-row" id="wpppfm-file-name-input-row">
					<th id="wppfm-main-feed-input-label"><label
						for="wppfm-feed-file-name">' . __( 'File Name', 'wp-product-promotions-feed-manager' ) . '</label> :
					</th>
					<td><input type="text" name="wppfm-feed-file-name" id="wppfm-feed-file-name" /></td></tr>';
		}

		/**
		 * Returns the promotion id input field code.
		 *
		 * @return string
		 */
		public static function promotion_id_input_element( $promotion_nr ) {
			return '<tr class="wppfm-main-feed-input-row" id="wpppfm-promotion-id-input-row">
					<th id="wppfm-main-feed-input-label"><label
						for="wpppfm-promotion-id-input-field-' . $promotion_nr . '">' . __( 'Promotion ID', 'wp-product-promotions-feed-manager' ) . '</label> :
					</th>
					<td><input type="text" name="wpppfm-promotion-id" id="wpppfm-promotion-id-input-field-' . $promotion_nr . '" data-attribute-key="promotion_id" /></td></tr>';
		}

		/**
		 * Returns the eligible for promotion field code.
		 *
		 * @return string
		 */
		public static function products_eligible_for_promotion_select_element( $promotion_nr ) {
			return '<tr class="wppfm-main-feed-input-row" id="wpppfm-products-eligible-for-promotion-select-row">
					<th id="wppfm-main-feed-input-label"><label
						for="wpppfm-product-applicability-input-field-' . $promotion_nr . '">' . __( 'Products Eligible for Promotion', 'wp-product-promotions-feed-manager' ) . '</label> :
					</th>
					<td><select class="wppfm-main-input-selector" name="wppfm-products-eligible-for-promotion-select" id="wpppfm-product-applicability-input-field-' . $promotion_nr . '" data-attribute-key="product_applicability">
						<option value="all_products">' . __( 'All Products', 'wp-product-promotions-feed-manager' ) . '</option>
						<option value="specific_products">' . __( 'Specific Products', 'wp-product-promotions-feed-manager' ) . '</option>
					</select></td></tr>';
		}

		/**
		 * Returns the coupon code required selector code.
		 *
		 * @return string
		 */
		public static function coupon_code_required_select_element( $promotion_nr ) {
			return '<tr class="wppfm-main-feed-input-row" id="wpppfm-coupon-code-required-select-row">
					<th id="wppfm-main-feed-input-label"><label
						for="wpppfm-offer-type-input-field-' . $promotion_nr . '">' . __( 'Coupon Code Required', 'wp-product-promotions-feed-manager' ) . '</label> :
					</th>
					<td><select class="wppfm-main-input-selector" name="wppfm-coupon-code-required-select" id="wpppfm-offer-type-input-field-' . $promotion_nr . '" data-attribute-key="offer_type">
						<option value="no_code">' . __( 'No code', 'wp-product-promotions-feed-manager' ) . '</option>
						<option value="generic_code">' . __( 'Generic code', 'wp-product-promotions-feed-manager' ) . '</option>
					</select></td></tr>';
		}

		public static function generic_redemption_code_input_element( $promotion_nr ) {
			return '<tr class="wppfm-main-feed-input-row" id="wpppfm-generic-redemption-code-input-row-'. $promotion_nr . '" style="display: none">
					<th id="wppfm-main-feed-input-label"><label
						for="wpppfm-generic-redemption-code-input-field-' . $promotion_nr . '">' . __( 'Generic Redemption Code', 'wp-product-promotions-feed-manager' ) . '</label> :
					</th>
					<td><input type="text" name="wppfm-generic-redemption-code" id="wpppfm-generic-redemption-code-input-field-' . $promotion_nr . '" data-attribute-key="generic_redemption_code" /></td></tr>';
		}

		/**
		 * Returns the promotions title input field code.
		 *
		 * @return string
		 */
		public static function promotion_title_input_element( $promotion_nr ) {
			return '<tr class="wppfm-main-feed-input-row" id="wpppfm-promotion-title-input-row">
				<th id="wppfm-main-feed-input-label"><label
					for="wpppfm-long-title-input-field-' . $promotion_nr . '">' . __( 'Promotion Title', 'wp-product-promotions-feed-manager' ) . '</label> :
				</th>
				<td><input type="text" name="wppfm-promotion-title" id="wpppfm-long-title-input-field-' . $promotion_nr . '" data-attribute-key="long_title" /></td></tr>';
		}

		public static function promotion_effective_date_input_element( $promotion_nr ) {
			return '<tr class="wppfm-main-feed-input-row" id="wpppfm-promotion-effective-date-input-row">
				<th id="wppfm-main-feed-input-label"><label
					for="wppfm-promotion-effective-date-' . $promotion_nr . '">' . __( 'Promotion Effective Dates', 'wp-product-promotions-feed-manager' ) . '</label> :
				</th>
				<td>' . __( 'from ', 'wp-product-promotions-feed-manager' ) . '<input type="text" class="datepicker date-time-picker wpppfm-date-time-picker" name="wppfm-promotion-effective-start-date" id="wpppfm-promotion-effective-start-date-input-field-' . $promotion_nr . '" data-attribute-key="promotion_effective_start_date" />'
				. __( ' till ', 'wp-product-promotions-feed-manager' ) . '<input type="text" class="datepicker date-time-picker wpppfm-date-time-picker" name="wppfm-promotion-effective-end-date" id="wpppfm-promotion-effective-end-date-input-field-' . $promotion_nr . '" data-attribute-key="promotion_effective_end_date" /></td></tr>';
		}

		public static function eligible_channel_for_promotion_select_element( $promotion_nr ) {
			return '<tr class="wppfm-main-feed-input-row" id="wpppfm-eligible-channel-for-promotion-select-row">
				<th id="wppfm-main-feed-input-label"><label
					for="wpppfm-redemption-channel-input-field-' . $promotion_nr . '">' . __( 'Eligible Channel for Promotion', 'wp-product-promotions-feed-manager' ) . '</label> :
				</th>
				<td><select class="wppfm-main-input-selector" name="wppfm-eligible-channel-for-promotion-select" id="wpppfm-redemption-channel-input-field-' . $promotion_nr . '" data-attribute-key="redemption_channel">
					<option value="online">' . __( 'Online', 'wp-product-promotions-feed-manager' ) . '</option>
					<option value="in_store">' . __( 'In store', 'wp-product-promotions-feed-manager' ) . '</option>
				</select></td></tr>';
		}

		public static function promotion_destination_select_element( $promotion_nr ) {
			return '<tr class="wppfm-main-feed-input-row" id="wpppfm-promotion-destination-select-row">
				<th id="wppfm-main-feed-input-label"><label
					for="wppfm-promotion-destination-select-' . $promotion_nr . '">' . __( 'Promotion Destination', 'wp-product-promotions-feed-manager' ) . '</label> :
				</th>
				<td><select class="wppfm-main-input-selector wppfm-select2-promotion-destination-pillbox-selector" name="wppfm-promotion-destination-select" id="wpppfm-promotion-destination-input-field-' . $promotion_nr . '" multiple="multiple" data-attribute-key="promotion_destination"></select>
				</td></tr>';
		}
	}

	// end of WPPRPM_Main_Input_Selector_Element class

endif;
