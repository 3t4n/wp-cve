<?php
/**
 * Framework slider field.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider_pro.
 * @subpackage Woo_Product_Slider_pro/models.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Field_slider' ) ) {
	/**
	 *
	 * Field: slider
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_slider extends SPF_WPSP_Fields {

		/**
		 * Field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
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

			$args = wp_parse_args(
				$this->field,
				array(
					'max'  => 100,
					'min'  => 0,
					'step' => 1,
					'unit' => '',
				)
			);

			$is_unit = ( ! empty( $args['unit'] ) ) ? ' spwps--is-unit' : '';

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spwps--wrap">';
			echo '<div class="spwps-slider-ui"></div>';
			echo '<div class="spwps--input">';
			echo '<input type="number" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes( array( 'class' => 'spwps-input-number' . esc_attr( $is_unit ) ) ) . ' data-min="' . esc_attr( $args['min'] ) . '" data-max="' . esc_attr( $args['max'] ) . '" data-step="' . esc_attr( $args['step'] ) . '" step="any" />';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo ( ! empty( $args['unit'] ) ) ? '<span class="spwps--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
			echo '</div>';
			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}

		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'jquery-ui-slider' ) ) {
				wp_enqueue_script( 'jquery-ui-slider' );
			}

		}

	}
}
