<?php
/**
 * Framework image select fields.
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

if ( ! class_exists( 'SPF_WPSP_Field_image_select' ) ) {

	/**
	 *
	 * Field: image_select
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_image_select extends SPF_WPSP_Fields {
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
					'multiple' => false,
					'inline'   => false,
					'options'  => array(),
				)
			);

			$inline = ( $args['inline'] ) ? ' spwps--inline-list' : '';

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $args['options'] ) ) {

				echo '<div class="spwps-siblings spwps--image-group' . esc_attr( $inline ) . '" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

				$num = 1;

				foreach ( $args['options'] as $key => $option ) {

					$type     = ( $args['multiple'] ) ? 'checkbox' : 'radio';
					$extra    = ( $args['multiple'] ) ? '[]' : '';
					$active   = ( in_array( $key, $value ) ) ? ' spwps--active' : '';
					$checked  = ( in_array( $key, $value ) ) ? ' checked' : '';
					$pro_only = isset( $option['pro_only'] ) ? ' disabled' : '';

					echo '<div class="spwps--sibling spwps--image' . esc_attr( $active ) . '" ' . esc_attr( $pro_only ) . ' >';
					echo '<figure>';

					echo '<img src="' . esc_url( $option['img'] ) . '" alt="img-' . esc_attr( $num++ ) . '" />';
					// Ignore phpcs errors because already $this->field_attributes escaped.
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>';
					echo '</figure>';
					if ( $args['image_name'] ) {
						echo '<p class="spwps-image-name text-center">' . esc_attr( $key ) . '</p>';
					}
					echo '</div>';

				}

				echo '</div>';

			}

			echo wp_kses_post( $this->field_after() );

		}
	}
}
