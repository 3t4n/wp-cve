<?php
/**
 * Framework spacing fields.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider.
 * @subpackage Woo_Product_Slider/models.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SPF_WPSP_Field_spacing' ) ) {
	/**
	 *
	 * Field: spacing
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPF_WPSP_Field_spacing extends SPF_WPSP_Fields {
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
					'top_icon'           => '<i class="fa fa-long-arrow-up"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-right"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-down"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-left"></i>',
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
					'unit'               => true,
					'show_units'         => true,
					'all'                => false,
					'vertical'           => false,
					'show_title'         => false,
					'units'              => array( 'px', '%', 'em' ),
				)
			);

			$default_values = array(
				'top'      => '',
				'right'    => '',
				'bottom'   => '',
				'left'     => '',
				'all'      => '',
				'vertical' => '',
				'unit'     => 'px',
			);

			$value   = wp_parse_args( $this->value, $default_values );
			$unit    = ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? $args['units'][0] : '';
			$is_unit = ( ! empty( $unit ) ) ? ' spwps--is-unit' : '';
			$min     = ( isset( $this->field['attributes']['min'] ) ) ? 'min=' . $this->field['attributes']['min'] : '';
			$max     = ( isset( $this->field['attributes']['max'] ) ) ? ' max=' . $this->field['attributes']['max'] : '';

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spwps--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? $args['all_placeholder'] : '';
				$show_title  = ( $args['show_title'] ) ? '<div class="spwps--title">' . __( 'Gap', 'woo-product-slider' ) . '</div>' : '';

				echo '<div class="spwps--space">';
				echo wp_kses_post( $show_title );
				echo '<div class="spwps--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="spwps--label spwps--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="spwps-input-number' . esc_attr( $is_unit ) . '" ' . esc_attr( $min . $max ) . ' step="any" />';
				echo ( $unit ) ? '<span class="spwps--label spwps--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
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
					echo '<div class="spwps--spacing-input">';
					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? $args[ $property . '_placeholder' ] : '';
					/* translators: %s: property modify title. */
					echo '<div class="spwps--title">' . esc_html( sprintf( '%s', $placeholder ) ) . '</div>';
					echo '<div class="spwps--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="spwps--label spwps--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="spwps-input-number' . esc_attr( $is_unit ) . ' spwps-number" step="any" />';
					echo ( $unit ) ? '<span class="spwps--label spwps--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
					echo '</div>';
					echo '</div>';

				}
			}

			if ( ! empty( $args['vertical'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';

				echo '<div class="spwps--space">';
				echo '<div class="spwps--title">' . esc_html__( 'Vertical Gap', 'woo-product-slider' ) . '</div>';
				echo '<div class="spwps--input">';
				echo ( ! empty( $args['vertical_icon'] ) ) ? '<span class="spwps--label spwps--icon">' . $args['vertical_icon'] . '</span>' : '';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[vertical]' ) ) . '" value="' . esc_attr( $value['vertical'] ) . '"' . $placeholder . ' class="spwps-input-number' . esc_attr( $is_unit ) . '" step="any" />';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo ( $unit ) ? '<span class="spwps--label spwps--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['unit'] ) && ! empty( $args['show_units'] ) && count( $args['units'] ) > 1 ) {
				echo '<div class="spwps--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
				foreach ( $args['units'] as $unit ) {
					$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $unit ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
			}

			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}
	}
}
