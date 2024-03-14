<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Field: heading
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Field_heading' ) ) {
	class KIANFR_Field_heading extends KIANFR_Fields
	{

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			echo ( ! empty( $this->field['content'] ) ) ? $this->field['content'] : '';
		}

	}
}
