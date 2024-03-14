<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Email extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'email';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Email', 'custom-post-types' );
	}

	/**
	 * @return array
	 */
	public static function get_extra() {
		return array(
			cpt_utils()->get_ui_placeholder_field( 34 ),
			cpt_utils()->get_ui_prepend_field( 33 ),
			cpt_utils()->get_ui_append_field( 33 ),
		);
	}

	/**
	 * @param $input_name
	 * @param $input_id
	 * @param $field_config
	 *
	 * @return string
	 */
	public static function render( $input_name, $input_id, $field_config ) {
		return sprintf(
			'%s<div style="display: none;"><input type="email" name="%s"></div><input type="email" name="%s" id="%s" value="%s" autocomplete="off" aria-autocomplete="none"%s%s>%s',
			! empty( $field_config['extra']['prepend'] ) ? '<span class="prepend">' . $field_config['extra']['prepend'] . '</span>' : '',
			$input_name,
			$input_name,
			$input_id,
			$field_config['value'],
			! empty( $field_config['extra']['placeholder'] ) ? ' placeholder="' . $field_config['extra']['placeholder'] . '"' : '',
			! empty( $field_config['required'] ) ? ' required' : '',
			! empty( $field_config['extra']['append'] ) ? '<span class="append">' . $field_config['extra']['append'] . '</span>' : ''
		);
	}

	/**
	 * @param $meta_value
	 *
	 * @return string
	 */
	public static function sanitize( $meta_value ) {
		return sanitize_email( $meta_value );
	}
}

cpt_fields()->add_field_type( CPT_Field_Email::class );
