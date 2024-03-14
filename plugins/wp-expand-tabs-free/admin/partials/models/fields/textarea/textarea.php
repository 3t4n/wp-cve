<?php
/**
 * Framework textarea field file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS_Field_textarea' ) ) {
	/**
	 *
	 * Field: textarea
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS_Field_textarea extends SP_WP_TABS_Fields {

		/**
		 * Textarea field constructor.
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
      // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_before();
      // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->shortcoder();
      // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<textarea name="' . esc_attr( $this->field_name() ) . '"' . $this->field_attributes() . '>' . $this->value . '</textarea>';
      // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->field_after();

		}
		/**
		 * Shortcoder field
		 */
		public function shortcoder() {

			if ( ! empty( $this->field['shortcoder'] ) ) {

				$shortcoders = ( is_array( $this->field['shortcoder'] ) ) ? $this->field['shortcoder'] : array_filter( (array) $this->field['shortcoder'] );

				foreach ( $shortcoders as $shortcode_id ) {

					if ( isset( SP_WP_TABS::$args['shortcoders'][ $shortcode_id ] ) ) {

						$setup_args   = SP_WP_TABS::$args['shortcoders'][ $shortcode_id ];
						$button_title = ( ! empty( $setup_args['button_title'] ) ) ? $setup_args['button_title'] : esc_html__( 'Add Shortcode', 'wp-expand-tabs-free' );

						echo '<a href="#" class="button button-primary wptabspro-shortcode-button" data-modal-id="' . esc_attr( $shortcode_id ) . '">' . wp_kses_post( $button_title ) . '</a>';

					}
				}
			}

		}
	}
}

