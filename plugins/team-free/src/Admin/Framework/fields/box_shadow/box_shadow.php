<?php
/**
 * Framework box shadow field file.
 *
 * @link https://shapedplugin.com
 * @since 3.0.0
 *
 * @package team-free
 * @subpackage team-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'TEAMFW_Field_box_shadow' ) ) {
	/**
	 *
	 * Field: border
	 *
	 * @since 2.0
	 * @version 2.0
	 */
	class TEAMFW_Field_box_shadow extends TEAMFW_Fields {
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
		 * Render field
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'vertical_icon'          => __( 'Y offset', 'team-free' ),
					'horizontal_icon'        => __( 'X offset', 'team-free' ),
					'blur_icon'              => __( 'Blur', 'team-free' ),
					'spread_icon'            => __( 'Spread', 'team-free' ),
					'vertical_placeholder'   => 'v-offset',
					'horizontal_placeholder' => 'h-offset',
					'blur_placeholder'       => 'blur',
					'spread_placeholder'     => 'spread',
					'vertical'               => true,
					'horizontal'             => true,
					'blur'                   => true,
					'spread'                 => true,
					'color'                  => true,
					'hover_color'            => false,
					'style'                  => true,
					'unit'                   => 'px',
				)
			);

			$default_value = array(
				'vertical'   => '',
				'horizontal' => '',
				'blur'       => '',
				'spread'     => '',
				'color'      => '',
				'style'      => 'outset',
			);

			$border_props = array(
				'inset'  => esc_html__( 'Inset', 'spf' ),
				'outset' => esc_html__( 'Outset', 'spf' ),
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spf--inputs">';
				$properties = array();
			foreach ( array( 'vertical', 'horizontal', 'blur', 'spread' ) as $prop ) {
				if ( ! empty( $args[ $prop ] ) ) {
					$properties[] = $prop;
				}
			}

			foreach ( $properties as $property ) {

				$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . esc_attr( $args[ $property . '_placeholder' ] ) . '"' : '';

				echo '<div class="spf--title">';
				echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span>' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
				echo '<div class="spf--input">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . wp_kses_post( $placeholder ) . ' class="spf-input-number spf--is-unit" step="any" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';
				echo '</div>';

			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="spf--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( $border_props as $border_prop_key => $border_prop_value ) {
					$selected = ( $value['style'] === $border_prop_key ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $border_prop_key ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $border_prop_value ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
			}
			echo '</div>';

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="spf--color">';
				echo '<div class="spf-field-color">';
				echo '<div class="spf--title">Color</div>';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="spf-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['hover_color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover_color'] ) . '"' : '';
				echo '<div class="spf--color">';
				echo '<div class="spf-field-color">';
				echo '<div class="spf--title">Hover Color</div>';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" value="' . esc_attr( $value['hover_color'] ) . '" class="spf-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}

			echo wp_kses_post( $this->field_after() );

		}
	}
}
