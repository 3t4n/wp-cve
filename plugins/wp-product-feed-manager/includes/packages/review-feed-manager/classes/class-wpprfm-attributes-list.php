<?php

/**
 * WPPRFM Attributes List Class.
 *
 * @package WP Product Review Feed Manager/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPRFM_Attributes_List' ) ) :

	/**
	 * Class Attributes List.
	 */
	class WPPRFM_Attributes_List {

		/**
		 * Mandatory attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the mandatory attributes.
		 */
		private static $mandatory_attributes = array(
			'reviewer_name',
			'review_timestamp',
			'content',
			'review_url',
			'ratings_overall',
			'ratings_overall_min',
			'ratings_overall_max',
			'product_ids_sku',
			'product_url',
		);

		/**
		 * Optional attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the optional attributes.
		 */
		private static $optional_attributes = array(
			'reviewer_id',
			'title',
			'pro',
			'con',
			'reviewer_image_url',
			'collection_method',
			'transaction_id',
			'product_ids_gtin',
			'product_ids_mpn',
			'product_ids_brand',
			'product_name',
			'is_spam',
			'deleted_review_id',
		);

		/**
		 * Main elements that the Review feed can have.
		 *
		 * @var array   Array with the main feed elements and the functions that handle them.
		 */
		private static $main_xml_feed_elements = array(
			'reviewer'          => 'wpprfm_handle_reviewer',
			'review_timestamp'  => 'wpprfm_handle_simple_element',
			'title'             => 'wpprfm_handle_simple_element',
			'content'           => 'wpprfm_handle_simple_element',
			'pros'              => 'wpprfm_handle_pros',
			'cons'              => 'wpprfm_handle_cons',
			'review_url'        => 'wpprfm_handle_review_url',
			'reviewer_images'   => 'wpprfm_handle_reviewer_images',
			'ratings'           => 'wpprfm_handle_ratings',
			'products'          => 'wpprfm_handle_products',
			'is_spam'           => 'wpprfm_handle_simple_element',
			'collection_method' => 'wpprfm_handle_simple_element',
			'transaction_id'    => 'wpprfm_handle_simple_element',
		);

		/**
		 * Returns an array filled with the Google Review Feed attribute objects.
		 *
		 * @return array with the Google Review Feed attributes
		 */
		public static function wpprfm_get_review_feed_attributes() {
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
		 * Returns the review feed elements and the functions that are to be used to fill these elements.
		 *
		 * @return array    Array with the feed elements and functions.
		 */
		public static function wpprfm_get_review_feed_main_elements() {
			return self::$main_xml_feed_elements;
		}

		/**
		 * Gets the WooCommerce inputs for the review feed.
		 *
		 * @return stdClass     Object with the feed inputs.
		 */
		public static function wpprfm_get_woocommerce_to_review_feed_inputs() {
			$fields = new stdClass();

			// ALERT! Any changes made to this object also need to be done to the wpprfm_defaultAttributeSettings() function in the wpprfm-attribute-mapping.js file.
			$fields->reviewer_name       = 'comment_author';
			$fields->review_timestamp    = 'comment_date';
			$fields->content             = 'comment_content';
			$fields->review_url          = 'comment_url';
			$fields->ratings_overall     = 'rating';
			$fields->ratings_overall_min = 'comment_rating_min';
			$fields->ratings_overall_max = 'comment_rating_max';
			$fields->product_url         = 'permalink';
			$fields->product_name        = 'post_title';
			$fields->reviewer_id         = 'user_id';
			$fields->reviewer_image_url  = 'comment_author_url';

			return $fields;
		}
	}

	// end of WPPRFM_Attributes_List class.

endif;
