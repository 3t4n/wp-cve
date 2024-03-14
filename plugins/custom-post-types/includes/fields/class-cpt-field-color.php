<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Color extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'color';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Color picker', 'custom-post-types' );
	}

	/**
	 * @return array
	 */
	public static function get_extra() {
		return array(
			cpt_utils()->get_ui_yesno_field( //alpha
				'alpha',
				__( 'Alpha color', 'custom-post-types' ),
				false,
				'NO',
				'',
				'',
				''
			),
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
			'<div class="cpt-color-section"><input type="text" name="%s" id="%s" value="%s" autocomplete="off" aria-autocomplete="none"%s%s></div>',
			$input_name,
			$input_id,
			$field_config['value'],
			! empty( $field_config['extra']['alpha'] ) && 'true' == $field_config['extra']['alpha'] ? ' data-alpha-enabled="true" data-alpha-color-type="hex"' : '', //phpcs:ignore Universal.Operators.StrictComparisons
			! empty( $field_config['required'] ) ? ' required' : ''
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

cpt_fields()->add_field_type( CPT_Field_Color::class );
