<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Field_switcher' ) ) {
	class ADMINIFY_Field_switcher extends ADMINIFY_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			$active     = ( ! empty( $this->value ) ) ? ' adminify--active' : '';
			$text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'adminify' );
			$text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'adminify' );
			$text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: ' . esc_attr( $this->field['text_width'] ) . 'px;"' : '';

			echo wp_kses_post( $this->field_before() );

			echo '<div class="adminify--switcher' . esc_attr( $active ) . '"' . wp_kses_post( $text_width ) . '>';
			echo '<span class="adminify--on">' . wp_kses_post( $text_on ) . '</span>';
			echo '<span class="adminify--off">' . wp_kses_post( $text_off ) . '</span>';
			echo '<span class="adminify--ball"></span>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . wp_kses_post( $this->field_attributes() ) . ' />';
			echo '</div>';

			echo ( ! empty( $this->field['label'] ) ) ? '<span class="adminify--label">' . wp_kses_post( $this->field['label'] ) . '</span>' : '';

			echo wp_kses_post( $this->field_after() );
		}

	}
}
