<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Number extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'number';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Number', 'custom-post-types' );
	}

	/**
	 * @return array
	 */
	public static function get_extra() {
		return array(
			cpt_utils()->get_ui_placeholder_field( 20 ),
			cpt_utils()->get_ui_min_field( 20 ),
			cpt_utils()->get_ui_max_field( 20 ),
			cpt_utils()->get_ui_prepend_field( 20 ),
			cpt_utils()->get_ui_append_field( 20 ),
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
			'%s<input type="number" name="%s" id="%s" value="%s" autocomplete="off" aria-autocomplete="none"%s%s%s%s>%s',
			! empty( $field_config['extra']['prepend'] ) ? '<span class="prepend">' . $field_config['extra']['prepend'] . '</span>' : '',
			$input_name,
			$input_id,
			$field_config['value'],
			isset( $field_config['extra']['placeholder'] ) && is_string( $field_config['extra']['placeholder'] ) ? ' placeholder="' . $field_config['extra']['placeholder'] . '"' : '',
			! empty( $field_config['extra']['min'] ) ? ' min="' . $field_config['extra']['min'] . '"' : '',
			! empty( $field_config['extra']['max'] ) ? ' max="' . $field_config['extra']['max'] . '"' : '',
			! empty( $field_config['required'] ) ? ' required' : '',
			! empty( $field_config['extra']['append'] ) ? '<span class="append">' . $field_config['extra']['append'] . '</span>' : '',
		);
	}

	/**
	 * @param $meta_value
	 *
	 * @return string
	 */
	public static function sanitize( $meta_value ) {
		return sanitize_text_field( $meta_value );
	}
}

cpt_fields()->add_field_type( CPT_Field_Number::class );
