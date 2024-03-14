<?php
/**
 * Framework radio field file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS_Field_radio' ) ) {
	/**
	 *
	 * Field: radio
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS_Field_radio extends SP_WP_TABS_Fields {

		/**
		 * Radio field constructor.
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
					'inline'     => false,
					'query_args' => array(),
				)
			);

			$inline_class = ( $args['inline'] ) ? ' class="wptabspro--inline-list"' : '';

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_before();

			if ( isset( $this->field['options'] ) ) {

				$options = $this->field['options'];
				$options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options, false, $args['query_args'] ) );

				if ( is_array( $options ) && ! empty( $options ) ) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<ul' . $inline_class . '>';
					foreach ( $options as $option_key => $option_value ) {

						if ( is_array( $option_value ) && ! empty( $option_value ) ) {

							echo '<li>';
							echo '<ul>';
							echo '<li><strong>' . esc_attr( $option_key ) . '</strong></li>';
							foreach ( $option_value as $sub_key => $sub_value ) {
								$checked = ( $sub_key === $this->value ) ? ' checked' : '';
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo '<li><label><input type="radio" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $sub_key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/> ' . esc_html( $sub_value ) . '</label></li>';
							}
							echo '</ul>';
							echo '</li>';

						} else {

							$checked = ( $option_key === $this->value ) ? ' checked' : '';
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo '<li><label><input type="radio" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $option_key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/> ' . esc_attr( $option_value ) . '</label></li>';

						}
					}
					echo '</ul>';

				} else {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data provided for this option type.', 'wp-expand-tabs-free' );

				}
			} else {
				$label = ( isset( $this->field['label'] ) ) ? $this->field['label'] : '';
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<label><input type="radio" name="' . esc_attr( $this->field_name() ) . '" value="1"' . $this->field_attributes() . esc_attr( checked( $this->value, 1, false ) ) . '/> ' . esc_attr( $label ) . '</label>';
			}
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_after();

		}

	}
}
