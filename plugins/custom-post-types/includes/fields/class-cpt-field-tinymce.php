<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Tinymce extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'tinymce';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'WYSIWYG editor', 'custom-post-types' );
	}

	/**
	 * @return array
	 */
	public static function get_extra() {
		return array(
			cpt_utils()->get_ui_placeholder_field( 50 ),
			cpt_utils()->get_ui_yesno_field(
				'autoresize',
				__( 'Auto-resize', 'custom-post-types' ),
				false,
				'NO',
				'',
				'50',
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
			'<textarea name="%s" id="%s" autocomplete="off" aria-autocomplete="none"%s%s%s>%s</textarea>',
			$input_name,
			$input_id,
			! empty( $field_config['extra']['placeholder'] ) ? ' placeholder="' . $field_config['extra']['placeholder'] . '"' : '',
			! empty( $field_config['extra']['autoresize'] ) ? ' autoresize' : '',
			! empty( $field_config['required'] ) ? ' required' : '',
			$field_config['value']
		);
	}

	/**
	 * @param $meta_value
	 *
	 * @return string
	 */
	public static function sanitize( $meta_value ) {
		return wp_kses_post( $meta_value );
	}
}

cpt_fields()->add_field_type( CPT_Field_Tinymce::class );
