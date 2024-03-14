<?php
/**
 * Framework column field file.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials/section/settings
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WCS_Field_column' ) ) {
	/**
	 *
	 * Field: column
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WCS_Field_column extends SP_WCS_Fields {

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
					'large_desktop_icon'        => '<i class="fa fa-television"></i>',
					'desktop_icon'              => '<i class="fa fa-desktop"></i>',
					'laptop_icon'               => '<i class="fa fa-laptop"></i>',
					'tablet_icon'               => '<i class="fa fa-tablet"></i>',
					'mobile_icon'               => '<i class="fa fa-mobile"></i>',
					'all_text'                  => '<i class="fa fa-arrows"></i>',
					'large_desktop_placeholder' => esc_html__( 'Large Desktop', 'woo-category-slider-grid' ),
					'desktop_placeholder'       => esc_html__( 'Desktop', 'woo-category-slider-grid' ),
					'laptop_placeholder'        => esc_html__( 'Small Desktop', 'woo-category-slider-grid' ),
					'tablet_placeholder'        => esc_html__( 'Tablet', 'woo-category-slider-grid' ),
					'mobile_placeholder'        => esc_html__( 'Mobile', 'woo-category-slider-grid' ),
					'all_placeholder'           => esc_html__( 'all', 'woo-category-slider-grid' ),
					'large_desktop'             => true,
					'desktop'                   => true,
					'laptop'                    => true,
					'tablet'                    => true,
					'mobile'                    => true,
					'unit'                      => false,
					'min'                       => '0',
					'all'                       => false,
					'units'                     => array( 'px', '%', 'em' ),
				)
			);

			$default_values = array(
				'large_desktop' => '4',
				'desktop'       => '4',
				'laptop'        => '3',
				'tablet'        => '2',
				'mobile'        => '1',
				'min'           => '',
				'all'           => '',
				'unit'          => 'px',
			);

			$value = wp_parse_args( $this->value, $default_values );

			echo wp_kses_post( $this->field_before() );

			$min = ( isset( $args['min'] ) ) ? ' min="' . esc_attr( $args['min'] ) . '"' : '';
			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';

				echo '<div class="spf--input">';
				echo ( ! empty( $args['all_text'] ) ) ? '<span class="spf--label spf--label-icon">' . wp_kses_post( $args['all_text'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . $placeholder . $min . ' class="spf-number" />'; // phpcs:ignore
				echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--label-unit">' . esc_html( $args['units'][0] ) . '</span>' : '';
				echo '</div>';

			} else {

				$properties = array();

				foreach ( array( 'large_desktop', 'desktop', 'laptop', 'tablet', 'mobile' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( array( 'laptop', 'mobile' ) === $properties ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . esc_attr( $args[ $property . '_placeholder' ] ) . '"' : '';

					echo '<div class="spf--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="spf--label spf--label-icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . $placeholder . $min . ' class="spf-number" />'; // phpcs:ignore
					echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="spf--label spf--label-unit">' . esc_html( $args['units'][0] ) . '</span>' : '';
					echo '</div>';

				}
			}

			if ( ! empty( $args['unit'] ) && count( $args['units'] ) > 1 ) {
				echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
				foreach ( $args['units'] as $unit ) {
					$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_html( $unit ) . '</option>';
				}
				echo '</select>';
			}

			echo wp_kses_post( $this->field_after() );

		}

		/**
		 * Output
		 *
		 * @return statement
		 */
		public function output() {

			$output    = '';
			$element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$unit      = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';

			$mode = ( ! empty( $this->field['output_mode'] ) ) ? $this->field['output_mode'] : 'padding';
			$mode = ( 'relative' === $mode || 'absolute' === $mode || 'none' === $mode ) ? '' : $mode;
			$mode = ( ! empty( $mode ) ) ? $mode . '-' : '';

			if ( ! empty( $this->field['all'] ) && isset( $this->value['all'] ) && '' !== $this->value['all'] ) {

				$output  = $element . '{';
				$output .= $mode . 'large_desktop:' . $this->value['all'] . $unit . $important . ';';
				$output .= $mode . 'desktop:' . $this->value['all'] . $unit . $important . ';';
				$output .= $mode . 'laptop:' . $this->value['all'] . $unit . $important . ';';
				$output .= $mode . 'tablet:' . $this->value['all'] . $unit . $important . ';';
				$output .= $mode . 'mobile:' . $this->value['all'] . $unit . $important . ';';
				$output .= '}';

			} else {

				$large_desktop = ( isset( $this->value['large_desktop'] ) && '' !== $this->value['large_desktop'] ) ? $mode . 'large_desktop:' . $this->value['large_desktop'] . $unit . $important . ';' : '';
				$desktop       = ( isset( $this->value['desktop'] ) && '' !== $this->value['desktop'] ) ? $mode . 'desktop:' . $this->value['desktop'] . $unit . $important . ';' : '';
				$laptop        = ( isset( $this->value['laptop'] ) && '' !== $this->value['laptop'] ) ? $mode . 'laptop:' . $this->value['laptop'] . $unit . $important . ';' : '';
				$tablet        = ( isset( $this->value['tablet'] ) && '' !== $this->value['tablet'] ) ? $mode . 'tablet:' . $this->value['tablet'] . $unit . $important . ';' : '';
				$mobile        = ( isset( $this->value['mobile'] ) && '' !== $this->value['mobile'] ) ? $mode . 'mobile:' . $this->value['mobile'] . $unit . $important . ';' : '';

				if ( '' !== $large_desktop || '' !== $desktop || '' !== $laptop || '' !== $tablet || '' !== $mobile ) {
					$output = $element . '{' . $large_desktop . $desktop . $laptop . $tablet . $mobile . '}';
				}
			}

			$this->parent->output_css .= $output;

			return $output;

		}

	}
}
