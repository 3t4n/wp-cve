<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Date extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'date';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Date picker', 'custom-post-types' );
	}

	/**
	 * @return array
	 */
	public static function get_extra() {
		return array(
			cpt_utils()->get_ui_placeholder_field( 50 ),
			cpt_utils()->get_ui_min_field( 25, 'date' ),
			cpt_utils()->get_ui_max_field( 25, 'date' ),
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
		$value = ! empty( $field_config['value'] ) && DateTime::createFromFormat( 'Y-m-d', $field_config['value'] ) ? DateTime::createFromFormat( 'Y-m-d', $field_config['value'] )->format( 'd/m/Y' ) : $field_config['value'];
		return sprintf(
			'<div class="cpt-date-section"><input type="text" name="%s" id="%s" value="%s" autocomplete="off" aria-autocomplete="none"%s%s%s%s></div>',
			$input_name,
			$input_id,
			$value,
			! empty( $field_config['extra']['placeholder'] ) ? ' placeholder="' . $field_config['extra']['placeholder'] . '"' : '',
			! empty( $field_config['extra']['min'] ) ? ' data-min="' . $field_config['extra']['min'] . '"' : '',
			! empty( $field_config['extra']['max'] ) ? ' data-max="' . $field_config['extra']['max'] . '"' : '',
			! empty( $field_config['required'] ) ? ' required' : ''
		);
	}

	/**
	 * @param $meta_value
	 *
	 * @return string
	 */
	public static function sanitize( $meta_value ) {
		$date = DateTime::createFromFormat( 'd/m/Y', $meta_value );
		return $date ? $date->format( 'Y-m-d' ) : '';
	}

	/**
	 * @param $meta_value
	 *
	 * @return false|string|void
	 */
	public static function get( $meta_value ) {
		if ( empty( $meta_value ) ) {
			return;
		}
		$config_format = get_option( 'date_format' );
		return ! empty( $config_format ) ? date( $config_format, strtotime( $meta_value ) ) : $meta_value;
	}
}

cpt_fields()->add_field_type( CPT_Field_Date::class );
