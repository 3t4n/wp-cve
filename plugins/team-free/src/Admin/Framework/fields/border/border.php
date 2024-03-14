<?php
/**
 * Framework border field file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package team-free
 * @subpackage team-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'TEAMFW_Field_border' ) ) {
	/**
	 *
	 * Field: border
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class TEAMFW_Field_border extends TEAMFW_Fields {

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
					'top_icon'           => '<i class="fa fa-long-arrow-alt-up"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-alt-left"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-alt-down"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-alt-right"></i>',
					'all_icon'           => '<i class="fa fa-arrows"></i>',
					'top_placeholder'    => esc_html__( 'top', 'team-free' ),
					'right_placeholder'  => esc_html__( 'right', 'team-free' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'team-free' ),
					'left_placeholder'   => esc_html__( 'left', 'team-free' ),
					'all_placeholder'    => esc_html__( 'all', 'team-free' ),
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'all'                => false,
					'color'              => true,
					'hover_color'        => true,
					'radius'             => false,
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
				'hover_color' => '',
				'style'       => 'solid',
				'all'         => '',
				'radius'      => '0',
			);

			$border_props = array(
				'solid'  => esc_html__( 'Solid', 'team-free' ),
				'dashed' => esc_html__( 'Dashed', 'team-free' ),
				'dotted' => esc_html__( 'Dotted', 'team-free' ),
				'double' => esc_html__( 'Double', 'team-free' ),
				'inset'  => esc_html__( 'Inset', 'team-free' ),
				'outset' => esc_html__( 'Outset', 'team-free' ),
				'groove' => esc_html__( 'Groove', 'team-free' ),
				'ridge'  => esc_html__( 'ridge', 'team-free' ),
				'none'   => esc_html__( 'None', 'team-free' ),
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spf--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';

				echo '<div class="spf--border">';
				echo '<div class="spf--title">' . esc_html__( 'Width', 'team-free' ) . '</div>';
				echo '<div class="spf--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '" class="spf-input-number spf--is-unit" step="any" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
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

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . esc_attr( $args[ $property . '_placeholder' ] ) . '"' : '';

					echo '<div class="spf--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '" class="spf-input-number spf--is-unit" step="any" />';
					echo ( ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
					echo '</div>';

				}
			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="spf--border">';
				echo '<div class="spf--title">' . esc_html__( 'Style', 'team-free' ) . '</div>';
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
			echo '</div>';

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="spf--color">';
				echo '<div class="spf--title">' . esc_html__( 'Color', 'team-free' ) . '</div>';
				echo '<div class="spf-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="spf-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}
			if ( ! empty( $args['hover_color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover_color'] ) . '"' : '';
				echo '<div class="spf--color">';
				echo '<div class="spf--title">' . esc_html__( 'Hover', 'team-free' ) . '</div>';
				echo '<div class="spf-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" value="' . esc_attr( $value['hover_color'] ) . '" class="spf-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}
			if ( ! empty( $args['radius'] ) ) {

				echo '<div class="spf--border">';
				echo '<div class="spf--title">' . esc_html__( 'Radius', 'team-free' ) . '</div>';
				echo '<div class="spf--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="spf--label spf--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[radius]' ) ) . '" value="' . esc_attr( $value['radius'] ) . '" placeholder="Radius" class="spf-input-number spf--is-unit" step="any" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';
				echo '</div>';

			}

			echo wp_kses_post( $this->field_after() );

		}

	}
}
