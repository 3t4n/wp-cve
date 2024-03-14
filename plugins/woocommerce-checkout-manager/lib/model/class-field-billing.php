<?php

namespace QuadLayers\WOOCCM\Model;

use QuadLayers\WOOCCM\Model\Field as Field;

if ( ! class_exists( 'Field_Billing' ) ) {

	/**
	 * Field_Billing_Model class
	 */
	class Field_Billing extends Field {

		protected static $_instance;
		protected $prefix   = 'billing';
		protected $table    = 'wooccm_billing';
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
			'email',
			'phone',
		);

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
	}
}
