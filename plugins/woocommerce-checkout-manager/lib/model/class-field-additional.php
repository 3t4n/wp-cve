<?php

namespace QuadLayers\WOOCCM\Model;

use QuadLayers\WOOCCM\Model\Field as Field;

if ( ! class_exists( 'Field_Additional' ) ) {

	/**
	 * Field_Additional_Model class
	 */
	class Field_Additional extends Field {

		protected static $_instance;
		protected $prefix = 'additional';
		protected $table  = 'wooccm_additional';

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
	}
}
