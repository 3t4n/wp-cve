<?php
/**
 * Framework column fields.
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

if ( ! class_exists( 'SPF_WPSP_Field_column' ) ) {
	/**
	 *
	 * Field: column
	 *
	 * @since 2.2.0
	 * @version 2.2.0
	 */
	class SPF_WPSP_Field_column extends SPF_WPSP_Fields {
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
					'number1_icon'        => '<i class="fa fa-desktop"></i>',
					'number2_icon'        => '<i class="fa fa-laptop"></i>',
					'number3_icon'        => '<i class="fa fa-tablet"></i>',
					'number4_icon'        => '<i class="fa fa-mobile"></i>',
					'number5_icon'        => '<i class="fa fa-mobile"></i>',
					'number1_placeholder' => esc_html__( 'Large Desktop', 'woo-product-slider' ),
					'number2_placeholder' => esc_html__( 'Desktop', 'woo-product-slider' ),
					'number3_placeholder' => esc_html__( 'Laptop', 'woo-product-slider' ),
					'number4_placeholder' => esc_html__( 'Tablet', 'woo-product-slider' ),
					'number5_placeholder' => esc_html__( 'Mobile', 'woo-product-slider' ),
					'number1'             => true,
					'number2'             => true,
					'number3'             => true,
					'number4'             => true,
					'number5'             => false,
				)
			);

			$default_values = array(
				'number1' => '',
				'number2' => '',
				'number3' => '',
				'number4' => '',
				'number5' => '',
			);

			$value = wp_parse_args( $this->value, $default_values );

			echo wp_kses_post( $this->field_before() );

			$properties = array();

			foreach ( array( 'number1', 'number2', 'number3', 'number4', 'number5' ) as $prop ) {
				if ( ! empty( $args[ $prop ] ) ) {
					$properties[] = $prop;
				}
			}
			echo '<div class="spwps--inputs">';
			$properties = ( array( 'number2', 'number4' ) === $properties ) ? array_reverse( $properties ) : $properties;

			foreach ( $properties as $property ) {

				$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? $args[ $property . '_placeholder' ] : '';

				echo '<div class="spwps--input">';
				echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="spwps--label spwps--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"  placeholder="' . esc_attr( $placeholder ) . '" class="spwps-number" step="any" min="1" required />';
				echo '</div>';

			}
			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}

	}
}
