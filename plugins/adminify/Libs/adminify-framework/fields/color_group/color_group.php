<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: color_group
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Field_color_group' ) ) {
	class ADMINIFY_Field_color_group extends ADMINIFY_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			$options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : [];

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $options ) ) {
				foreach ( $options as $key => $option ) {
					$color_value  = ( ! empty( $this->value[ $key ] ) ) ? $this->value[ $key ] : '';
					$default_attr = ( ! empty( $this->field['default'][ $key ] ) ) ? ' data-default-color="' . esc_attr( $this->field['default'][ $key ] ) . '"' : '';

					echo '<div class="adminify--left adminify-field-color">';
					echo '<div class="adminify--title">' . wp_kses_post( $option ) . '</div>';
					echo '<input type="text" name="' . esc_attr( $this->field_name( '[' . $key . ']' ) ) . '" value="' . esc_attr( $color_value ) . '" class="adminify-color"' . wp_kses_post( $default_attr . $this->field_attributes() ) . '/>';
					echo '</div>';
				}
			}

			echo wp_kses_post( $this->field_after() );
		}

	}
}
