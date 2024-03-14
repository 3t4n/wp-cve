<?php

namespace QuadLayers\WOOCCM\Model;

use QuadLayers\WOOCCM\Model\Field as Field;

if ( ! class_exists( 'Field_Shipping' ) ) {

	/**
	 * Field_Shipping_Model class
	 */
	class Field_Shipping extends Field {

		protected static $_instance;
		protected $prefix   = 'shipping';
		protected $table    = 'wooccm_shipping';
		protected $defaults = array(
			'country',
			'first_name',
			'last_name',
			'company',
			'address_1',
			'address_2',
			'city',
			'state',
			'postcode',
		);

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
	}
}
