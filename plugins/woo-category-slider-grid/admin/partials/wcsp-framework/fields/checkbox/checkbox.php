<?php

/**
 * Framework checkbox field file.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials/section/settings
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WCS_Field_checkbox' ) ) {
	/**
	 *
	 * Field: checkbox
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WCS_Field_checkbox extends SP_WCS_Fields {
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
					'inline' => false,
				)
			);

			$inline_class = ( $args['inline'] ) ? ' class="spf--inline-list"' : '';

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $this->field['options'] ) ) {

				$value   = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );
				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options ) );

				if ( ! empty( $options ) ) {

					echo '<ul' . $inline_class . '>';// phpcs:ignore
					foreach ( $options as $option_key => $option_value ) {
						$checked = ( in_array( $option_key, $value, true ) ) ? ' checked' : '';
						echo '<li><label><input type="checkbox" name="' . esc_attr( $this->field_name( '[]' ) ) . '" value="' . esc_attr( $option_key ) . '"' . wp_kses_post( $this->field_attributes() . $checked ) . '/> ' . esc_html( $option_value ) . '</label></li>';
					}
					echo '</ul>';

				} else {

					echo ! empty( $this->field['empty_message'] ) ? esc_html( $this->field['empty_message'] ) : esc_html__( 'No data provided for this option type.', 'woo-category-slider-grid' );

				}
			} else {
				echo '<label class="spf-checkbox">';
				echo '<input type="hidden" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '" class="spf--input"' . wp_kses_post( $this->field_attributes() ) . '/>';
				echo '<input type="checkbox" class="spf--checkbox"' . checked( $this->value, 1, false ) . '/>';
				echo ( ! empty( $this->field['label'] ) ) ? ' ' . wp_kses_post( $this->field['label'] ) : '';
				echo '</label>';
			}

			echo wp_kses_post( $this->field_after() );

		}

	}
}
