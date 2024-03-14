<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Textarea extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'textarea';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Textarea', 'custom-post-types' );
	}

	/**
	 * @return array
	 */
	public static function get_extra() {
		return array(
			cpt_utils()->get_ui_placeholder_field( 50 ),
			array( //min
				'key'      => 'rows',
				'label'    => __( 'Rows', 'custom-post-types' ),
				'info'     => false,
				'required' => false,
				'type'     => 'number',
				'extra'    => array(
					'placeholder' => '5',
					'min'         => '0',
				),
				'wrap'     => array(
					'width'  => 25,
					'class'  => '',
					'id'     => '',
					'layout' => '',
				),
			),
			array( //max
				'key'      => 'cols',
				'label'    => __( 'Columns', 'custom-post-types' ),
				'info'     => false,
				'required' => false,
				'type'     => 'number',
				'extra'    => array(
					'placeholder' => '50',
					'min'         => '0',
				),
				'wrap'     => array(
					'width'  => 25,
					'class'  => '',
					'id'     => '',
					'layout' => '',
				),
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
			'<textarea name="%s" id="%s" autocomplete="off" aria-autocomplete="none" rows="%s" cols="%s"%s%s>%s</textarea>',
			$input_name,
			$input_id,
			! empty( $field_config['extra']['rows'] ) ? $field_config['extra']['rows'] : 5,
			! empty( $field_config['extra']['cols'] ) ? $field_config['extra']['cols'] : 50,
			! empty( $field_config['extra']['placeholder'] ) ? ' placeholder="' . $field_config['extra']['placeholder'] . '"' : '',
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
		return sanitize_textarea_field( $meta_value );
	}
}

cpt_fields()->add_field_type( CPT_Field_Textarea::class );
