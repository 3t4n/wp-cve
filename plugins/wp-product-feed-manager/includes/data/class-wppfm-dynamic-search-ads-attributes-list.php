<?php

/**
 * WPPFM Dynamic Search Ads Attributes List Class.
 *
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Dynamic_Search_Ads_Attributes_List' ) ) :

	/**
	 * Class Attributes List.
	 */
	class WPPFM_Dynamic_Search_Ads_Attributes_List extends WPPFM_Support_Feed_Attributes_List {

		/**
		 * Mandatory attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the mandatory attributes.
		 */
		private static $mandatory_attributes = array(
			'page_url',
			'custom_label',
		);

		/**
		 * Optional attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the optional attributes.
		 */
		private static $optional_attributes = array();

		/**
		 * Returns an array filled with the Google Dynamic Search Ads Feed attribute objects.
		 *
		 * @return array with the Google Dynamic Search Ads Feed attributes
		 */
		public static function wppfm_get_dynamic_search_ads_feed_attributes() {
			return parent::wppfm_get_support_feed_attributes( self::$mandatory_attributes, self::$optional_attributes );
		}

	}

	// end of WPPFM_Dynamic_Search_Ads_Attributes_List class.

endif;
