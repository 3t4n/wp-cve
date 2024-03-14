<?php

/**
 * WPPFM Local Product Attributes List Class.
 *
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Local_Product_Attributes_List' ) ) :

	/**
	 * Class Attributes List.
	 */
	class WPPFM_Local_Product_Attributes_List extends WPPFM_Support_Feed_Attributes_List {

		/**
		 * Mandatory attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the mandatory attributes.
		 */
		private static $mandatory_attributes = array(
			'rank',
			'product_item_id',
			'title',
			'description',
			'item_url',
			'image_url',
			'price',
			'store_code',
		);

		/**
		 * Optional attributes with the attribute name and xml type.
		 *
		 * @var array   Array with the optional attributes.
		 */
		private static $optional_attributes = array(
			'sale_price',
		);

		/**
		 * Returns an array filled with the Google Local Product Feed attribute objects.
		 *
		 * @return array with the Google Local Product Feed attributes
		 */
		public static function wppfm_get_local_product_feed_attributes() {
			return parent::wppfm_get_support_feed_attributes( self::$mandatory_attributes, self::$optional_attributes );
		}

	}

	// end of WPPFM_Local_Product_Attributes_List class.

endif;
