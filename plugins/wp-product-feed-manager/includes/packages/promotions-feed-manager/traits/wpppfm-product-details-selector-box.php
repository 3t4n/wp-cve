<?php

/**
 * @package WP Google Merchant Promotions Feed Manager/Classes/Traits
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait WPPPFM_Product_Details_Selector_Box {

	protected static function content_box( $promotion_nr ) {
		$html  = '<div class="wppfm-content-box-tab-list-back"></div>';
		$html .= '<ul class="wpppfm-details-tab-list wppfm-content-box-tab-list wppfm-tabs" style="display:none;">';

		$details_selector_tabs = apply_filters(
			'wpppfm_promotion_details_selector_tabs',
			array(
				'preconditions'         => array(
					'label'  => __( 'Preconditions', 'wp-product-feed-manager' ),
					'target' => 'wpppfm-promotions-details-preconditions-tab-' . $promotion_nr,
					'class'  => 'wpppfm-promotions-details-preconditions-tab',
				),
				'promotion_categories'  => array(
					'label'  => __( 'Promotion categories', 'wp-product-feed-manager' ),
					'target' => 'wpppfm-promotions-details-promotion-categories-tab-' . $promotion_nr,
					'class'  => '',
				),
				'limits'                => array(
					'label'  => __( 'Limits', 'wp-product-feed-manager' ),
					'target' => 'wpppfm-promotions-details-limits-tab-' . $promotion_nr,
					'class'  => '',
				),
				'additional_attributes' => array(
					'label'  => __( 'Additional attributes', 'wp-product-feed-manager' ),
					'target' => 'wpppfm-promotions-details-additional-attributes-tab-' . $promotion_nr,
					'class'  => '',
				),
			)
		);

		foreach ( $details_selector_tabs as $key => $tab ) {
			$html .= '<li class="' . $key . '_options ' . $key . '_tab ' . implode( ' ', (array) $tab['class'] ) . ' wppfm-content-box-tab-list-item">
				<a href="#' . $tab['target'] . '">
					<span>' . esc_html( $tab['label'] ) . '</span>
				</a>
			</li>';
		}

		$html .= '</ul>';

		$html .=
		'<!-- Preconditions Tab -->
		<div id="wpppfm-promotions-details-preconditions-tab-' . $promotion_nr . '" name="wpppfm-promotions-details-preconditions-tab-' . $promotion_nr . '" class="wppfm-panel wpppfm-promotions-details-panel">
			<p class="wpppfm-input-field form-field"><label for="wpppfm-minimum-purchase-amount-input-field-' . $promotion_nr . '">' . __( 'Minimum purchase amount', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-minimum-purchase-amount-input-field-' . $promotion_nr . '" data-attribute-key="minimum_purchase_amount"></p>
			<p class="wpppfm-input-field form-field"><label for="wpppfm-buy-this-quantity-input-field-' . $promotion_nr . '">' . __( 'Minimum purchase quantity for promotion', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-buy-this-quantity-input-field-' . $promotion_nr . '" data-attribute-key="buy_this_quantity"></p>
		</div>

		<!-- Promotion categories Tab -->
		<div id="wpppfm-promotions-details-promotion-categories-tab-' . $promotion_nr . '" name="wpppfm-promotions-details-promotion-categories-tab-' . $promotion_nr . '" class="wppfm-panel wpppfm-promotions-details-panel hidden">
			<p class="wpppfm-input-field form-field"><label for="wpppfm-percent-off-input-field-' . $promotion_nr . '">' . __( 'Percentage discount amount', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-percent-off-input-field-' . $promotion_nr . '" data-attribute-key="percent_off"></p>
			<p class="wpppfm-input-field form-field"><label for="wpppfm-money-off-amount-input-field-' . $promotion_nr . '">' . __( 'Monetary discount amount of a promotion', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-money-off-amount-input-field-' . $promotion_nr . '" data-attribute-key="money_off_amount"></p>
			<p class="wpppfm-input-field form-field"><label for="wpppfm-get-this-quantity-discounted-input-field-' . $promotion_nr . '">' . __( 'Quantity eligible for promotion', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-get-this-quantity-discounted-input-field-' . $promotion_nr . '" data-attribute-key="get_this_quantity_discounted"></p>
			<p class="wpppfm-select-field form-field"><label for="wpppfm-free-shipping-input-field-' . $promotion_nr . '">' . __( 'Free shipping', 'wp-product-feed-manager' ) . '</label>
				<select class="short wpppfm-select-field" id="wpppfm-free-shipping-input-field-' . $promotion_nr . '" data-attribute-key="free_shipping">
					<option value="">' . __( '-- optional --', 'wp-product-feed-manager' ) . '</option>
					<option value="free_shipping_standard">' . __( 'Free standard shipping', 'wp-product-feed-manager' ) . '</option>
					<option value="free_shipping_overnight">' . __( 'Free overnight shipping', 'wp-product-feed-manager' ) . '</option>
			</select></p>
			<p class="wpppfm-input-field form-field"><label for="wpppfm-free-gift-value-input-field-' . $promotion_nr . '">' . __( 'Free gift of monetary value', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-free-gift-value-input-field-' . $promotion_nr . '" data-attribute-key="free_gift_value"></p>
			<p class="wpppfm-input-field form-field"><label for="wpppfm-free-gift-description-input-field-' . $promotion_nr . '">' . __( 'Free gift description', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-free-gift-description-input-field-' . $promotion_nr . '" data-attribute-key="free_gift_description"></p>
			<p class="wpppfm-input-field form-field"><label for="wpppfm-free-gift-item-id-input-field-' . $promotion_nr . '">' . __( 'Free gift item ID', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-free-gift-item-id-input-field-' . $promotion_nr . '" data-attribute-key="free_gift_item_id"></p>
			<p class="wpppfm-select-field form-field"><label for="wpppfm-coupon-value-type-input-field-' . $promotion_nr . '">' . __( 'Coupon value type', 'wp-product-feed-manager' ) . '</label>
				<select class="short wpppfm-select-field" id="wpppfm-coupon-value-type-input-field-' . $promotion_nr . '" data-attribute-key="coupon_value_type">
					<option value="">' . __( '-- optional --', 'wp-product-feed-manager' ) . '</option>
					<option value="no_structured_data">' . __( 'No structured data', 'wp-product-feed-manager' ) . '</option>
					<option value="money_off">' . __( 'Money off', 'wp-product-feed-manager' ) . '</option>
					<option value="percent_off">' . __( 'Percent off', 'wp-product-feed-manager' ) . '</option>
					<option value="buy_m_get_n_money_off">' . __( 'Buy M get N money off', 'wp-product-feed-manager' ) . '</option>
					<option value="buy_m_get_n_percent_off">' . __( 'Buy M get N percent off', 'wp-product-feed-manager' ) . '</option>
					<option value="buy_m_get_percent_off">' . __( 'Buy M get percent off', 'wp-product-feed-manager' ) . '</option>
					<option value="buy_m_get_money_off">' . __( 'Buy M get money off', 'wp-product-feed-manager' ) . '</option>
					<option value="free_gift">' . __( 'Free gift', 'wp-product-feed-manager' ) . '</option>
					<option value="free_gift_with_value">' . __( 'Free gift with value', 'wp-product-feed-manager' ) . '</option>
					<option value="free_gift_with_item_id">' . __( 'Free gift with item ID', 'wp-product-feed-manager' ) . '</option>
					<option value="free_shipping_standard">' . __( 'Free shipping standard', 'wp-product-feed-manager' ) . '</option>
					<option value="free_shipping_overnight">' . __( 'Free shipping overnight', 'wp-product-feed-manager' ) . '</option>
					<option value="free_shipping_two_day">' . __( 'Free shipping two day', 'wp-product-feed-manager' ) . '</option>
					<option value="free_shipping_with_shipping_config">' . __( 'Free shipping with shipping config', 'wp-product-feed-manager' ) . '</option>
			</select></p>
		</div>

		<!-- Limits Tab -->
		<div id="wpppfm-promotions-details-limits-tab-' . $promotion_nr . '" name="wpppfm-promotions-details-limits-tab-' . $promotion_nr . '" class="wppfm-panel wpppfm-promotions-details-panel hidden">
			<p class="wpppfm-input-field form-field"><label for="wpppfm-limit-quantity-input-field-' . $promotion_nr . '">' . __( 'Maximum purchase quantity for promotion', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-limit-quantity-input-field-' . $promotion_nr . '" data-attribute-key="limit_quantity"></p>
			<p class="wpppfm-input-field form-field"><label for="wpppfm-limit-value-input-field-' . $promotion_nr . '">' . __( 'Maximum product price for promotion', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-limit-value-input-field-' . $promotion_nr . '" data-attribute-key="limit_value"></p>
		</div>

		<!-- Additional attributes Tab -->
		<div id="wpppfm-promotions-details-additional-attributes-tab-' . $promotion_nr . '" name="wpppfm-promotions-details-additional-attributes-tab-' . $promotion_nr . '" class="wppfm-panel wpppfm-promotions-details-panel hidden">
			<p class="wpppfm-input-field form-field wpppfm-text-input-row"><label for="wpppfm-promotion-display-dates-input-field-' . $promotion_nr . '">' . __( 'Display dates for promotion', 'wp-product-feed-manager' ) . '</label>
				<td>' . __( 'from ', 'wp-product-promotions-feed-manager' ) . '<input type="text" class="datepicker date-time-picker wpppfm-date-time-picker" name="wppfm-promotion-display-start-date" id="wpppfm-promotion-display-start-date-input-field-' . $promotion_nr . '" data-attribute-key="promotion_display_start_date" />'
				. __( ' till ', 'wp-product-promotions-feed-manager' ) . '<input type="text" class="datepicker date-time-picker wpppfm-date-time-picker" name="wppfm-promotion-display-end-date" id="wpppfm-promotion-display-end-date-input-field-' . $promotion_nr . '" data-attribute-key="promotion_display_end_date" /></td></tr>
			<p class="wpppfm-input-field form-field wpppfm-textarea-row"><label for="wpppfm-description-input-field-' . $promotion_nr . '">' . __( 'Description', 'wp-product-feed-manager' ) . '</label>
				<textarea class="short wpppfm-text-area-field" id="wpppfm-description-input-field-' . $promotion_nr . '" data-attribute-key="description"></textarea></p>
			<p class="wpppfm-input-field form-field wpppfm-text-input-row"><label for="wpppfm-image-link-input-field-' . $promotion_nr . '">' . __( 'Image link', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-image-link-input-field-' . $promotion_nr . '" data-attribute-key="image_link"></p>
			<p class="wpppfm-input-field form-field wpppfm-textarea-row"><label for="wpppfm-fine-print-input-field-' . $promotion_nr . '">' . __( 'Fine print for promotion', 'wp-product-feed-manager' ) . '</label>
				<textarea class="short wpppfm-text-area-field" id="wpppfm-fine-print-input-field-' . $promotion_nr . '" data-attribute-key="fine_print"></textarea></p>
			<p class="wpppfm-input-field form-field wpppfm-text-input-row"><label for="wpppfm-promotion-price-input-field-' . $promotion_nr . '">' . __( 'Price for promotion', 'wp-product-feed-manager' ) . '</label>
				<input type="text" class="short wpppfm-text-input-field" id="wpppfm-promotion-price-input-field-' . $promotion_nr . '" data-attribute-key="promotion_price"></p>
		</div>';

		return $html;
	}
}
