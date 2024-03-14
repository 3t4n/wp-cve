<?php
/**
 * Framework button_set field file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package team-free
 * @subpackage team-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'TEAMFW_Field_button_set' ) ) {
	/**
	 *
	 * Field: button_set
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class TEAMFW_Field_button_set extends TEAMFW_Fields {

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
					'multiple'   => false,
					'options'    => array(),
					'query_args' => array(),
				)
			);

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( isset( $this->field['options'] ) ) {

				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options, false, $args['query_args'] ) );

				if ( is_array( $options ) && ! empty( $options ) ) {

					echo '<div class="spf-siblings spf--button-group" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

					foreach ( $options as $key => $option ) {

						$type     = ( $args['multiple'] ) ? 'checkbox' : 'radio';
						$extra    = ( $args['multiple'] ) ? '[]' : '';
						$active   = ( in_array( $key, $value, true ) || ( empty( $value ) && empty( $key ) ) ) ? ' spf--active' : '';
						$checked  = ( in_array( $key, $value, true ) || ( empty( $value ) && empty( $key ) ) ) ? ' checked' : '';
						$pro_only = ( isset( $option['pro_only'] ) && $option['pro_only'] ) ? ' spf-pro-only' : '';

						echo '<div class="spf--sibling spf--button' . esc_attr( $active . $pro_only ) . '">';
						echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>'; // phpcs:ignore
						if ( isset( $option['option_name'] ) && ! empty( $option['option_name'] ) ) {
							echo wp_kses_post( $option['option_name'] );
						} else {
							echo wp_kses_post( $option );
						}
						echo '</div>';

					}

					echo '</div>';

				} else {

					echo ! empty( $this->field['empty_message'] ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'team-free' );

				}
			}

			echo wp_kses_post( $this->field_after() );

		}

	}
}
