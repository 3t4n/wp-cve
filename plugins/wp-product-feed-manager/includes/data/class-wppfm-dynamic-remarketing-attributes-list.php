<?php

/**
 * WPPFM Dynamic Remarketing Attributes List Class.
 *
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Dynamic_Remarketing_Attributes_List' ) ) :

	/**
	 * Class Attributes List.
	 */
	class WPPFM_Dynamic_Remarketing_Attributes_List extends WPPFM_Support_Feed_Attributes_List {

		/**
		 * Mandatory attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the mandatory attributes.
		 */
		private static $mandatory_attributes = array(
			'program_id',
			'program_name',
			'destination_id',
			'flight_description',
			'property_id',
			'property_name',
			'job_id',
			'title',
			'deal_id',
			'deal_name',
			'listing_id',
			'listing_name',
			'id',
			'item_title',
		);

		/**
		 * Optional attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the optional attributes.
		 */
		private static $optional_attributes = array(
			'location_id',
			'school_name',
			'final_url',
			'thumbnail_image_url',
			'image_url',
			'area_of_study',
			'program_description',
			'contextual_keywords',
			'address',
			'tracking_template',
			'custom_parameter',
			'destination_url',
			'final_mobile_url',
			'android_app_link',
			'ios_app_link',
			'ios_app_store_id',
			'similar_program_ids',
			'origin_id',
			'destination_name',
			'origin_name',
			'flight_price',
			'flight_sale_price',
			'formatted_price',
			'formatted_sale_price',
			'similar_destination_ids',
			'price',
			'sale_price',
			'star_rating',
			'category',
			'similar_property_ids',
			'subtitle',
			'salary',
			'similar_job_ids',
			'similar_deal_ids',
			'city_name',
			'property_type',
			'listing_type',
			'similar_listing_ids',
			'destination_address',
			'item_subtitle',
			'item_description',
			'item_category',
			'item_address',
			'similar_ids',
		);

		/**
		 * Returns an array filled with the Google Dynamic Remarketing Feed attribute objects.
		 *
		 * @return array with the Google Dynamic Remarketing Feed attributes
		 */
		public static function wppfm_get_dynamic_remarketing_feed_attributes() {
			return parent::wppfm_get_support_feed_attributes( self::$mandatory_attributes, self::$optional_attributes );
		}

	}

	// end of WPPFM_Dynamic_Remarketing_Attributes_List class.

endif;
