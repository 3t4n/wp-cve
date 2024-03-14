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


if ( ! class_exists( 'SPF_WPSP_Field_border' ) ) {
	/**
	 *
	 * Field: border
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_border extends SPF_WPSP_Fields {

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
		 * Border render function.
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'top_icon'           => '<i class="fa fa-long-arrow-alt-up"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-alt-left"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-alt-down"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-alt-right"></i>',
					'all_icon'           => '<i class="fa fa-arrows-alt"></i>',
					'top_placeholder'    => esc_html__( 'top', 'woo-product-slider' ),
					'right_placeholder'  => esc_html__( 'right', 'woo-product-slider' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'woo-product-slider' ),
					'left_placeholder'   => esc_html__( 'left', 'woo-product-slider' ),
					'all_placeholder'    => esc_html__( 'all', 'woo-product-slider' ),
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'all'                => false,
					'color'              => true,
					'hover_color'        => false,
					'style'              => true,
					'unit'               => 'px',
				)
			);

			$default_value = array(
				'top'         => '',
				'right'       => '',
				'bottom'      => '',
				'left'        => '',
				'color'       => '',
				'style'       => 'solid',
				'hover_color' => '',
				'all'         => '',
			);

			$border_props = array(
				'solid'  => esc_html__( 'Solid', 'woo-product-slider' ),
				'dashed' => esc_html__( 'Dashed', 'woo-product-slider' ),
				'dotted' => esc_html__( 'Dotted', 'woo-product-slider' ),
				'double' => esc_html__( 'Double', 'woo-product-slider' ),
				'inset'  => esc_html__( 'Inset', 'woo-product-slider' ),
				'outset' => esc_html__( 'Outset', 'woo-product-slider' ),
				'groove' => esc_html__( 'Groove', 'woo-product-slider' ),
				'ridge'  => esc_html__( 'ridge', 'woo-product-slider' ),
				'none'   => esc_html__( 'None', 'woo-product-slider' ),
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spwps--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? $args['all_placeholder'] : '';
				echo '<div class="spwps--border">';
				echo '<div class="spwps--title">' . esc_html__( 'Width', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="spwps--label spwps--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="spwps-input-number spwps--is-unit spwps-number" step="any" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="spwps--label spwps--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';
				echo '</div>';

			} else {
				$properties = array();
				foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( array( 'right', 'left' ) === $properties ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? $args[ $property . '_placeholder' ] : '';
					echo '<div class="spwps--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="spwps--label spwps--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="spwps-input-number spwps--is-unit" step="any" />';
					echo ( ! empty( $args['unit'] ) ) ? '<span class="spwps--label spwps--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
					echo '</div>';
				}
			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="spwps--style">';
				echo '<div class="spwps--title">' . esc_html__( 'Style', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( $border_props as $border_prop_key => $border_prop_value ) {
					$selected = ( $value['style'] === $border_prop_key ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $border_prop_key ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $border_prop_value ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
				echo '</div>';
			}

			echo '</div>';

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? $default_value['color'] : '';
				echo '<div class="spwps--color">';
				echo '<div class="spwps-field-color">';
				echo '<div class="spwps--title">' . esc_html__( 'Color', 'woo-product-slider' ) . '</div>';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="spwps-color" data-default-color="' . esc_attr( $default_color_attr ) . '" />';
				echo '</div>';
				echo '</div>';
			}
			if ( ! empty( $args['hover_color'] ) ) {
				$default_hover_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? $default_value['hover_color'] : '';
				echo '<div class="spwps--color">';
				echo '<div class="spwps-field-color">';
				echo '<div class="spwps--title">' . esc_html__( 'Hover Color', 'woo-product-slider' ) . '</div>';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" value="' . esc_attr( $value['hover_color'] ) . '" class="spwps-color" data-default-color="' . esc_attr( $default_hover_color_attr ) . '" />';
				echo '</div>';
				echo '</div>';
			}
			echo wp_kses_post( $this->field_after() );
		}
	}
}
