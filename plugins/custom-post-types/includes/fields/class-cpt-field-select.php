<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Select extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'select';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Dropdown', 'custom-post-types' );
	}

	/**
	 * @return array
	 */
	public static function get_extra() {
		return array(
			cpt_utils()->get_ui_placeholder_field( 50 ),
			cpt_utils()->get_ui_yesno_field( // multiple
				'multiple',
				__( 'Multiple', 'custom-post-types' ),
				false,
				'NO',
				'',
				'50',
				''
			),
			array( // options
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
	 * @return string
	 */
	public static function render( $input_name, $input_id, $field_config ) {
		$options = '';
		if ( 'true' != $field_config['extra']['multiple'] ) {
			$options .= '<option value=""></option>';
		}
		foreach ( $field_config['extra']['options'] as $value => $label ) {
			if ( is_array( $label ) ) {
				$child_options = '';
				foreach ( $label as $child_value => $child_label ) {
					$child_options .= sprintf(
						'<option value="%s"%s>%s</option>',
						$child_value,
						( is_array( $field_config['value'] ) && in_array( $child_value, $field_config['value'], true ) ) ||
						( ! is_array( $field_config['value'] ) && $child_value == $field_config['value'] ) ? //phpcs:ignore Universal.Operators.StrictComparisons
							'  selected="selected"' :
							'',
						$child_label
					);
				}
				$options .= sprintf(
					'<optgroup label="%s">%s</optgroup>',
					$value,
					$child_options
				);
			} else {
				$options .= sprintf(
					'<option value="%s"%s>%s</option>',
					$value,
					( is_array( $field_config['value'] ) && in_array( $value, $field_config['value'], true ) ) ||
					( ! is_array( $field_config['value'] ) && $value == $field_config['value'] ) ? //phpcs:ignore Universal.Operators.StrictComparisons
						'  selected="selected"' :
						'',
					$label
				);
			}
		}
		return sprintf(
			'<select name="%s" id="%s" autocomplete="off" aria-autocomplete="none" style="width: 100%%;"%s%s%s>%s</select>',
			$input_name . ( ! empty( $field_config['extra']['multiple'] ) && 'true' == $field_config['extra']['multiple'] ? '[]' : '' ), //phpcs:ignore Universal.Operators.StrictComparisons
			$input_id,
			! empty( $field_config['extra']['placeholder'] ) ? ' placeholder="' . $field_config['extra']['placeholder'] . '"' : '',
			! empty( $field_config['extra']['multiple'] ) && 'true' == $field_config['extra']['multiple'] ? ' multiple' : '', //phpcs:ignore Universal.Operators.StrictComparisons
			! empty( $field_config['required'] ) ? ' required' : '',
			$options
		);
	}
}

cpt_fields()->add_field_type( CPT_Field_Select::class );
