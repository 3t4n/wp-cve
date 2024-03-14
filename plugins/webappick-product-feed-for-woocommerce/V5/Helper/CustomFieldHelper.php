<?php

namespace CTXFeed\V5\Helper;


/**
 * Class CustomFieldHelper
 *
 * @package    CTXFeed\V5\Helper
 * @subpackage CTXFeed\V5\Helper
 */
class CustomFieldHelper {
	public static function get_fields() {
		/**
		 * Here array of a field contain 3 elements
		 * 1. Name
		 * 2. Is this fields enabled by default
		 * 3. Is this fields is a custom taxonomy
		 */
		$custom_fields = array(
			'brand'                     => array( __( 'Brand', 'woo-feed' ), true, 'taxonomy' ),
			'gtin'                      => array( __( 'GTIN', 'woo-feed' ), true, 'text' ),
			'mpn'                       => array( __( 'MPN', 'woo-feed' ), true, 'text' ),
			'ean'                       => array( __( 'EAN', 'woo-feed' ), true, 'text' ),
			'isbn'                      => array( __( 'ISBN', 'woo-feed' ), true, 'text' ),
			'age_group'                 => array( __( 'Age group', 'woo-feed' ), true, 'text' ),
			'gender'                    => array( __( 'Gender', 'woo-feed' ), true, 'text' ),
			'material'                  => array( __( 'Material', 'woo-feed' ), true, 'text' ),
			'cost_of_good_sold'         => array( __( 'Cost of good sold', 'woo-feed' ), true, 'text' ),
			'availability_date'         => array( __( 'Availability Date', 'woo-feed' ), true, 'date' ),
			'unit'                      => array( __( 'Unit', 'woo-feed' ), true, 'text' ),
			'unit_pricing_measure'      => array( __( 'Unit Price Measure', 'woo-feed' ), true, 'text' ),
			'unit_pricing_base_measure' => array( __( 'Unit Price Base Measure', 'woo-feed' ), true, 'text' ),
			'custom_field_0'            => array( __( 'Custom field 0', 'woo-feed' ), true, 'text' ),
			'custom_field_1'            => array( __( 'Custom field 1', 'woo-feed' ), true, 'text' ),
			'custom_field_2'            => array( __( 'Custom field 2', 'woo-feed' ), true, 'text' ),
			'custom_field_3'            => array( __( 'Custom field 3', 'woo-feed' ), true, 'text' ),
			'custom_field_4'            => array( __( 'Custom field 4', 'woo-feed' ), true, 'text' ),
		);

		return apply_filters( 'woo_feed_product_custom_fields', $custom_fields );
	}

}
