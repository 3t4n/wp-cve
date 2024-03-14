<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Radio extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'radio';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Radio', 'custom-post-types' );
	}

	/**
	 * @return array[]
	 */
	public static function get_extra() {
		return array(
			array( //options
				'key'      => 'options',
				'label'    => __( 'Options', 'custom-post-types' ),
				'info'     => __( 'One per row (value|label).', 'custom-post-types' ),
				'required' => true,
				'type'     => 'textarea',
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
	 * @return false|string
	 */
	public static function render( $input_name, $input_id, $field_config ) {
		ob_start();
		foreach ( $field_config['extra']['options'] as $value => $label ) {
			printf(
				'<label><input type="radio" name="%s" value="%s"%s%s>%s<label><br>',
				$input_name,
				$value,
				$value == $field_config['value'] ? ' checked="checked"' : '', //phpcs:ignore Universal.Operators.StrictComparisons
				! empty( $field_config['required'] ) ? ' required' : '',
				$label
			);
		}
		return ob_get_clean();
	}
}

cpt_fields()->add_field_type( CPT_Field_Radio::class );
