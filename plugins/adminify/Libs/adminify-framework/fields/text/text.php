<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: text
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Field_text' ) ) {
	class ADMINIFY_Field_text extends ADMINIFY_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			$type = ( ! empty( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : 'text';

			echo wp_kses_post( $this->field_before() );

			echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . wp_kses_post( $this->field_attributes() ) . ' />';

			echo wp_kses_post( $this->field_after() );
		}

	}
}
