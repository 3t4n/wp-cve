<?php
/**
 * Framework color group fields.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider.
 * @subpackage Woo_Product_Slider/Admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Field_color_group' ) ) {
	/**
	 *
	 * Field: color_group
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_color_group extends SPF_WPSP_Fields {
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

			$options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $options ) ) {
				foreach ( $options as $key => $option ) {

					$color_value  = ( ! empty( $this->value[ $key ] ) ) ? $this->value[ $key ] : '';
					$default_attr = ( ! empty( $this->field['default'][ $key ] ) ) ? ' data-default-color="' . esc_attr( $this->field['default'][ $key ] ) . '"' : '';

					echo '<div class="spwps--left spwps-field-color">';
					echo '<div class="spwps--title">' . wp_kses_post( $option ) . '</div>';
					// Ignore phpcs errors because already $this->field_attributes and $default_attr escaped.
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<input type="text" name="' . esc_attr( $this->field_name( '[' . $key . ']' ) ) . '" value="' . esc_attr( $color_value ) . '" class="spwps-color"' . $default_attr . $this->field_attributes() . '/>';
					echo '</div>';

				}
			}

			echo wp_kses_post( $this->field_after() );

		}

	}
}
