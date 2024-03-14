<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Html extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'html';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Html', 'custom-post-types' );
	}

	/**
	 * @return array[]
	 */
	public static function get_extra() {
		return array(
			array( //content
				'key'      => 'content',
				'label'    => __( 'Content', 'custom-post-types' ),
				'info'     => false,
				'required' => false,
				'type'     => 'tinymce',
				'extra'    => array(),
				'wrap'     => array(
					'width'  => '',
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
	 * @return mixed|string
	 */
	public static function render( $input_name, $input_id, $field_config ) {
		return ! empty( $field_config['extra']['content'] ) ? wp_kses_post( $field_config['extra']['content'] ) : '';
	}
}

cpt_fields()->add_field_type( CPT_Field_Html::class );
