<?php
/**
 * Framework switcher fields.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider.
 * @subpackage Woo_Product_Slider/admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Field_switcher' ) ) {
	/**
	 *
	 * Field: switcher
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_switcher extends SPF_WPSP_Fields {

		/**
		 * Constructor function.
		 *
		 * @param array  $field field.
		 * @param string $value field value.
		 * @param string $unique field unique.
		 * @param string $where field where.
		 * @param string $parent field parent.
		 * @since 2.0
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			$active     = ( ! empty( $this->value ) ) ? ' spwps--active' : '';
			$text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'woo-product-slider' );
			$text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'woo-product-slider' );
			$text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: ' . esc_attr( $this->field['text_width'] ) . 'px;"' : '';

			echo wp_kses_post( $this->field_before() );
			// Ignore phpcs errors because already $text_width escaped.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<div class="spwps--switcher' . esc_attr( $active ) . '"' . $text_width . '>';
			echo '<span class="spwps--on">' . esc_attr( $text_on ) . '</span>';
			echo '<span class="spwps--off">' . esc_attr( $text_off ) . '</span>';
			echo '<span class="spwps--ball"></span>';
			// Ignore phpcs errors because already $this->field_attributes escaped.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<input type="hidden" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes() . ' />';
			echo '</div>';

			echo ( ! empty( $this->field['label'] ) ) ? '<span class="spwps--label">' . esc_attr( $this->field['label'] ) . '</span>' : '';
			echo wp_kses_post( $this->field_after() );
		}

	}
}
