<?php
/**
 * Framework radio fields.
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

if ( ! class_exists( 'SPF_WPSP_Field_radio' ) ) {
	/**
	 *
	 * Field: radio
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_radio extends SPF_WPSP_Fields {
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
					'inline'     => false,
					'query_args' => array(),
				)
			);

			$inline_class = ( $args['inline'] ) ? 'spwps--inline-list' : '';

			echo wp_kses_post( $this->field_before() );

			if ( isset( $this->field['options'] ) ) {

				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options, false, $args['query_args'] ) );

				if ( is_array( $options ) && ! empty( $options ) ) {

					echo '<ul "' . esc_attr( $inline_class ) . '">';

					foreach ( $options as $option_key => $option_value ) {

						if ( is_array( $option_value ) && ! empty( $option_value ) ) {

							echo '<li>';
							echo '<ul>';
							echo '<li><strong>' . esc_attr( $option_key ) . '</strong></li>';
							foreach ( $option_value as $sub_key => $sub_value ) {
								$checked = ( $sub_key == $this->value ) ? ' checked' : '';
								echo '<li>';
								echo '<label>';
								// Ignore phpcs errors because already $this->field_attributes escaped.
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo '<input type="radio" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $sub_key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>';
								echo '<span class="spwps--text">' . esc_attr( $sub_value ) . '</span>';
								echo '</label>';
								echo '</li>';
							}
							echo '</ul>';
							echo '</li>';
						} else {
							$checked = ( $option_key == $this->value ) ? ' checked' : '';
							echo '<li>';
							echo '<label>';
							// Ignore phpcs errors because already $this->field_attributes escaped.
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo '<input type="radio" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $option_key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>';
							echo '<span class="spwps--text">' . esc_attr( $option_value ) . '</span>';
							echo '</label>';
							echo '</li>';

						}
					}
					echo '</ul>';
				} else {

					echo( ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'woo-product-slider' ) );

				}
			} else {
				$label = ( isset( $this->field['label'] ) ) ? $this->field['label'] : '';
				// Ignore phpcs errors because already $this->field_attributes escaped.
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<label><input type="radio" name="' . esc_attr( $this->field_name() ) . '" value="1"' . $this->field_attributes() . esc_attr( checked( $this->value, 1, false ) ) . '/>';
				echo ( ! empty( $this->field['label'] ) ) ? '<span class="spwps--text">' . esc_attr( $this->field['label'] ) . '</span>' : '';
				echo '</label>';
			}
			echo wp_kses_post( $this->field_after() );
		}

	}
}
