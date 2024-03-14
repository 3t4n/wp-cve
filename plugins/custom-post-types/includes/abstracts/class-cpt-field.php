<?php

defined( 'ABSPATH' ) || exit;

abstract class CPT_Field {
	/**
	 * @param $meta_value
	 * @param $meta_key
	 * @param $meta_type
	 *
	 * @return mixed
	 */
	public static function sanitize_value( $meta_value, $meta_key, $meta_type ) {
		/** @var CPT_Field $field_class */
		$field_class = get_called_class();
		if ( $field_class::get_type() == $meta_type ) { //phpcs:ignore Universal.Operators.StrictComparisons
			return $field_class::sanitize( $meta_value );
		}
		return $meta_value;
	}

	/**
	 * @param $meta_value
	 * @param $meta_key
	 * @param $meta_type
	 *
	 * @return mixed
	 */
	public static function get_value( $meta_value, $meta_key, $meta_type ) {
		/** @var CPT_Field $field_class */
		$field_class = get_called_class();
		if ( $field_class::get_type() == $meta_type ) { //phpcs:ignore Universal.Operators.StrictComparisons
			return $field_class::get( $meta_value );
		}
		return $meta_value;
	}

	/**
	 * @return mixed
	 */
	abstract public static function get_type();

	/**
	 * @return mixed
	 */
	abstract public static function get_label();

	/**
	 * @return mixed
	 */
	abstract public static function get_extra();

	/**
	 * @param $input_name
	 * @param $input_id
	 * @param $field_config
	 *
	 * @return mixed
	 */
	abstract public static function render( $input_name, $input_id, $field_config );

	/**
	 * @param $meta_value
	 *
	 * @return mixed
	 */
	public static function sanitize( $meta_value ) {
		return $meta_value;
	}

	/**
	 * @param $meta_value
	 *
	 * @return mixed
	 */
	public static function get( $meta_value ) {
		return $meta_value;
	}

	/**
	 * @return string
	 */
	public static function get_pro_label() {
		return ' <sup>[' . __( 'PRO only', 'custom-post-types' ) . ']</sup>';
	}
}
