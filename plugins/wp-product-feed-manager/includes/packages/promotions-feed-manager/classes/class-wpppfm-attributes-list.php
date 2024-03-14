<?php

/**
 * WPPPFM Attributes List Class.
 *
 * @package WP Merchant Promotions Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPPFM_Attributes_List' ) ) :

	/**
	 * Class Attributes List.
	 */
	class WPPPFM_Attributes_List {

		/**
		 * Mandatory attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the mandatory attributes.
		 */
		private static $mandatory_attributes = array(
			'promotion_id',
			'product_applicability',
			'offer_type',
			'long_title',
			'promotion_effective_dates',
			'redemption_channel',
			'promotion_destination',
		);

		/**
		 * Optional attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the optional attributes.
		 */
		private static $optional_attributes = array(
			'item_id',
			'product_type',
			'brand',
			'item_group_id',
			'item_id_exclusion',
			'product_type_exclusion',
			'brand_exclusion',
			'item_group_id_exclusion',
			'minimum_purchase_amount',
			'buy_this_quantity',
			'percent_off',
			'money_off_amount',
			'get_this_quantity_discounted',
			'free_shipping',
			'free_gift_value',
			'free_gift_description',
			'free_gift_item_id',
			'coupon_value_type',
			'limit_quantity',
			'limit_value',
			'promotion_display_dates',
			'description',
			'generic_redemption_code',
			'image_link',
			'fine_print',
			'promotion_price',
		);

		/**
		 * Returns an array filled with the Google Merchant Promotions Feed attribute objects.
		 *
		 * @return array with the Google Merchant Promotions Feed attributes
		 */
		public static function wpppfm_get_promotions_feed_attributes() {
			$attributes        = array();
			$attribute_counter = 1;

			foreach ( self::$mandatory_attributes as $mandatory_attribute ) {
				$mandatory_field_object = new stdClass();

				$mandatory_field_object->field_id    = $attribute_counter++;
				$mandatory_field_object->category_id = '1';
				$mandatory_field_object->field_label = $mandatory_attribute;

				$attributes[] = $mandatory_field_object;
			}

			foreach ( self::$optional_attributes as $optional_attribute ) {
				$optional_field_object = new stdClass();

				$optional_field_object->field_id    = $attribute_counter++;
				$optional_field_object->category_id = '4';
				$optional_field_object->field_label = $optional_attribute;

				$attributes[] = $optional_field_object;
			}

			return $attributes;
		}

		/**
		 * Returns an empty object because there is no connection between the Promotions Feed attributes and the WooCommerce attributes.
		 *
		 * @return stdClass     Object with the feed inputs.
		 */
		public static function wpppfm_get_woocommerce_to_promotions_feed_inputs() {
			return new stdClass();
		}

	}

	// end of WPPPFM_Attributes_List class.

endif;
