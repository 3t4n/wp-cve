<?php

/**
 * WPPFM Support Feed Attributes List Class.
 *
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Support_Feed_Attributes_List' ) ) :

	/**
	 * Class Attributes List.
	 */
	class WPPFM_Support_Feed_Attributes_List {

		/**
		 * Returns an array filled with the Google Dynamic Remarketing Feed attribute objects.
		 *
		 * @return array with the Google Dynamic Remarketing Feed attributes
		 */
		public static function 	wppfm_get_support_feed_attributes( $mandatory_attributes, $optional_attributes ) {
			$attributes        = array();
			$attribute_counter = 1;

			foreach ( $mandatory_attributes as $mandatory_attribute ) {
				$mandatory_field_object = new stdClass();

				$mandatory_field_object->field_id    = $attribute_counter++;
				$mandatory_field_object->category_id = '1';
				$mandatory_field_object->field_label = $mandatory_attribute;

				$attributes[] = $mandatory_field_object;
			}

			foreach ( $optional_attributes as $optional_attribute ) {
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
		public static function wppfm_get_woocommerce_to_promotions_feed_inputs() {
			return new stdClass();
		}

	}

	// end of WPPFM_Support_Feed_Attributes_List class.

endif;
