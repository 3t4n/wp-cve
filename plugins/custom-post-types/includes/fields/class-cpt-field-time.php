<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Time extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'time';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Time picker', 'custom-post-types' );
	}

	/**
	 * @return array
	 */
	public static function get_extra() {
		return array(
			cpt_utils()->get_ui_placeholder_field( 50 ),
			cpt_utils()->get_ui_min_field( 25, 'time' ),
			cpt_utils()->get_ui_max_field( 25, 'time' ),
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
		$value   = ! empty( $field_config['value'] ) && DateTime::createFromFormat( 'H:i', $field_config['value'] ) ? $field_config['value'] : '';
		$options = '<option value=""></option>';
		return sprintf(
			'<div class="cpt-time-section"><select name="%s" id="%s" autocomplete="off" aria-autocomplete="none" style="width: 100%%;"%s%s%s%s%s>%s</select></div>',
			$input_name,
			$input_id,
			! empty( $value ) ? ' data-value="' . $value . '"' : '',
			! empty( $field_config['extra']['placeholder'] ) ? ' placeholder="' . $field_config['extra']['placeholder'] . '"' : '',
			! empty( $field_config['extra']['min'] ) ? ' data-min="' . $field_config['extra']['min'] . '"' : '',
			! empty( $field_config['extra']['max'] ) ? ' data-max="' . $field_config['extra']['max'] . '"' : '',
			! empty( $field_config['required'] ) ? ' required' : '',
			$options
		);
	}

	/**
	 * @param $meta_value
	 *
	 * @return string
	 */
	public static function sanitize( $meta_value ) {
		$date = DateTime::createFromFormat( 'H:i', $meta_value );
		return $date ? $meta_value : '';
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
		$config_format = get_option( 'time_format' );
		return ! empty( $config_format ) ? date( $config_format, strtotime( $meta_value ) ) : $meta_value;
	}
}

cpt_fields()->add_field_type( CPT_Field_Time::class );
