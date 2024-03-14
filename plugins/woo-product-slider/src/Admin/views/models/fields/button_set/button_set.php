<?php
/**
 * Framework border fields.
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

if ( ! class_exists( 'SPF_WPSP_Field_button_set' ) ) {
	/**
	 *
	 * Field: button_set
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_button_set extends SPF_WPSP_Fields {
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

			$args = wp_parse_args(
				$this->field,
				array(
					'multiple'   => false,
					'options'    => array(),
					'query_args' => array(),
				)
			);

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( isset( $this->field['options'] ) ) {

				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options, false, $args['query_args'] ) );

				if ( is_array( $options ) && ! empty( $options ) ) {

					echo '<div class="spwps-siblings spwps--button-group" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

					foreach ( $options as $key => $option ) {

						$type     = ( $args['multiple'] ) ? 'checkbox' : 'radio';
						$extra    = ( $args['multiple'] ) ? '[]' : '';
						$active   = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) ) ) ? ' spwps--active' : '';
						$checked  = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) ) ) ? ' checked' : '';
						$pro_only = isset( $option['pro_only'] ) ? ' disabled' : '';

						echo '<div class="spwps--sibling spwps--button' . esc_attr( $active ) . '" ' . esc_attr( $pro_only ) . '>';
						echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '<div class="spwps--name">' . wp_kses_post( $option['name'] ) . '</div>';
						echo '</div>';

					}

					echo '</div>';

				} else {

					echo( ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'woo-product-slider' ) );

				}
			}

			echo wp_kses_post( $this->field_after() );

		}

	}
}
