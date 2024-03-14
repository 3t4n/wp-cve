<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Checkbox extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'checkbox';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Checkbox', 'custom-post-types' );
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
				'<label><input type="checkbox" name="%s[]" value="%s"%s%s>%s<label><br>',
				$input_name,
				$value,
				is_array( $field_config['value'] ) && in_array( $value, $field_config['value'], true ) ? ' checked="checked"' : '',
				! empty( $field_config['required'] ) ? ' required' : '',
				$label
			);
		}
		return ob_get_clean();
	}

	/**
	 * @param $meta_value
	 *
	 * @return string
	 */
	public static function get( $meta_value ) {
		if ( empty( $meta_value ) ) {
			return '';
		}
		return is_array( $meta_value ) ? implode( ', ', $meta_value ) : $meta_value;
	}
}

cpt_fields()->add_field_type( CPT_Field_Checkbox::class );
