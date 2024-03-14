<?php

/**
 * WPPFM Vehicle Ads Attributes List Class.
 *
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Vehicle_Ads_Attributes_List' ) ) :

	/**
	 * Class Attributes List.
	 */
	class WPPFM_Vehicle_Ads_Attributes_List extends WPPFM_Support_Feed_Attributes_List {

		/**
		 * Mandatory attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the mandatory attributes.
		 */
		private static $mandatory_attributes = array(
			'google_product_category',
			'vehicle_fullfilment-option',
			'vehicle_fullfilment-store_code',
			'VIN',
			'id',
			'store_code',
			'image_link',
			'link_template',
			'link',
			'price',
			'vehicle_price_type',
			'vehicle_msrp',
			'vehicle_all_in_price',
			'condition',
			'brand',
			'model',
			'year',
			'mileage',
			'color',
		);

		/**
		 * Optional attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the optional attributes.
		 */
		private static $optional_attributes = array(
			'title',
			'product_type',
			'additional_image_link',
			'mobile_link_template',
			'mobile_link',
			'ads_redirect',
			'certified_pre-owned',
			'trim',
			'vehicle_option',
			'body_style',
			'engine',
			'description',
			'custom_label_0',
			'custom_label_1',
			'custom_label_2',
			'custom_label_3',
			'custom_label_4',
			'included_destination',
			'excluded_destination',
		);

		/**
		 * Returns an array filled with the Google Vehicle Ads Feed attribute objects.
		 *
		 * @return array with the Google Vehicle Ads Feed attributes
		 */
		public static function wppfm_get_vehicle_ads_feed_attributes() {
			return parent::wppfm_get_support_feed_attributes( self::$mandatory_attributes, self::$optional_attributes );
		}

	}

	// end of WPPFM_Vehicle_Ads_Attributes_List class.

endif;
