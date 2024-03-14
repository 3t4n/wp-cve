<?php
/**
 * Framework border field file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS_Field_border' ) ) {
	/**
	 *
	 * Field: border
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS_Field_border extends SP_WP_TABS_Fields {

		/**
		 * Border field constructor.
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
					'top_icon'           => '<i class="fa fa-long-arrow-up"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-left"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-down"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-right"></i>',
					'all_icon'           => '<i class="fa fa-arrows"></i>',
					'top_placeholder'    => esc_html__( 'top', 'wp-expand-tabs-free' ),
					'right_placeholder'  => esc_html__( 'right', 'wp-expand-tabs-free' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'wp-expand-tabs-free' ),
					'left_placeholder'   => esc_html__( 'left', 'wp-expand-tabs-free' ),
					'all_placeholder'    => esc_html__( 'all', 'wp-expand-tabs-free' ),
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'all'                => false,
					'color'              => true,
					'style'              => true,
					'border_radius'      => false,
					'show_units'         => false,
					'unit'               => 'px',
				)
			);

			$default_value = array(
				'top'           => '',
				'right'         => '',
				'bottom'        => '',
				'left'          => '',
				'color'         => '',
				'style'         => 'solid',
				'border_radius' => '',
				'all'           => '',
			);

			$border_props = array(
				'solid'  => esc_html__( 'Solid', 'wp-expand-tabs-free' ),
				'dashed' => esc_html__( 'Dashed', 'wp-expand-tabs-free' ),
				'dotted' => esc_html__( 'Dotted', 'wp-expand-tabs-free' ),
				'double' => esc_html__( 'Double', 'wp-expand-tabs-free' ),
				'inset'  => esc_html__( 'Inset', 'wp-expand-tabs-free' ),
				'outset' => esc_html__( 'Outset', 'wp-expand-tabs-free' ),
				'groove' => esc_html__( 'Groove', 'wp-expand-tabs-free' ),
				'ridge'  => esc_html__( 'ridge', 'wp-expand-tabs-free' ),
				'none'   => esc_html__( 'None', 'wp-expand-tabs-free' ),
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_before();

			echo '<div class="wptabspro--inputs">';

			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';
				echo '<div class="wptabspro--border">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Width', 'wp-expand-tabs-free' ) . '</div>';
				echo '<div class="wptabspro--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="wptabspro--label wptabspro--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . $placeholder . ' class="wptabspro-input-number wptabspro--is-unit" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="wptabspro--label wptabspro--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
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

					echo '<div class="wptabspro--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="wptabspro--label wptabspro--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . $placeholder . ' class="wptabspro-input-number wptabspro--is-unit" />';
					echo ( ! empty( $args['unit'] ) ) ? '<span class="wptabspro--label wptabspro--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
					echo '</div>';

				}
			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="wptabspro--border">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Style', 'wp-expand-tabs-free' ) . '</div>';
				echo '<div class="wptabspro--input">';
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
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="wptabspro--color">';
				echo '<div class="wptabspro-field-color">';
				echo '<div class="wptabspro--title">' . esc_html__( 'Color', 'wp-expand-tabs-free' ) . '</div>';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="wptabspro-color"' . $default_color_attr . ' />';
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['border_radius'] ) ) {
				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? $args['all_placeholder'] : '';
				echo '<div class="wptabspro--title">' . esc_html__( 'Border Radius', 'wp-expand-tabs-free' ) . '</div>';
				echo '<div class="wptabspro--inputs border-radius">';
				echo '<div class="wptabspro--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="wptabspro--label wptabspro--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[border_radius]' ) ) . '" value="' . esc_attr( $value['border_radius'] ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="wptabspro-input-number wptabspro--is-unit" step="any" />';
				if ( $args['show_units'] && ( $args['units'] ) > 1 ) {
					echo '<div class="wptabspro--input wptabspro--border-select">';
					echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
					foreach ( $args['units'] as $unit ) {
						$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
						echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $unit ) . '</option>';
					}
					echo '</select>';
					echo '</div>';
				} else {
					echo ( ! empty( $args['unit'] ) ) ? '<span class="wptabspro--label wptabspro--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';

				}
				echo '</div></div>';
			}

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_after();

		}

		/**
		 * Output
		 *
		 * @return statement
		 */
		public function output() {

			$output    = '';
			$unit      = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

			// properties.
			$top    = ( isset( $this->value['top'] ) && '' !== $this->value['top'] ) ? $this->value['top'] : '';
			$right  = ( isset( $this->value['right'] ) && '' !== $this->value['right'] ) ? $this->value['right'] : '';
			$bottom = ( isset( $this->value['bottom'] ) && '' !== $this->value['bottom'] ) ? $this->value['bottom'] : '';
			$left   = ( isset( $this->value['left'] ) && '' !== $this->value['left'] ) ? $this->value['left'] : '';
			$style  = ( isset( $this->value['style'] ) && '' !== $this->value['style'] ) ? $this->value['style'] : '';
			$color  = ( isset( $this->value['color'] ) && '' !== $this->value['color'] ) ? $this->value['color'] : '';
			$all    = ( isset( $this->value['all'] ) && '' !== $this->value['all'] ) ? $this->value['all'] : '';

			if ( ! empty( $this->field['all'] ) && ( '' !== $all || '' !== $color ) ) {

				$output  = $element . '{';
				$output .= ( '' !== $all ) ? 'border-width:' . $all . $unit . $important . ';' : '';
				$output .= ( '' !== $color ) ? 'border-color:' . $color . $important . ';' : '';
				$output .= ( '' !== $style ) ? 'border-style:' . $style . $important . ';' : '';
				$output .= '}';

			} elseif ( '' !== $top || '' !== $right || '' !== $bottom || '' !== $left || '' !== $color ) {

				$output  = $element . '{';
				$output .= ( '' !== $top ) ? 'border-top-width:' . $top . $unit . $important . ';' : '';
				$output .= ( '' !== $right ) ? 'border-right-width:' . $right . $unit . $important . ';' : '';
				$output .= ( '' !== $bottom ) ? 'border-bottom-width:' . $bottom . $unit . $important . ';' : '';
				$output .= ( '' !== $left ) ? 'border-left-width:' . $left . $unit . $important . ';' : '';
				$output .= ( '' !== $color ) ? 'border-color:' . $color . $important . ';' : '';
				$output .= ( '' !== $style ) ? 'border-style:' . $style . $important . ';' : '';
				$output .= '}';

			}

			$this->parent->output_css .= $output;

			return $output;

		}

	}
}
