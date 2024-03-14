<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: textarea
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Field_textarea' ) ) {
	class ADMINIFY_Field_textarea extends ADMINIFY_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			echo wp_kses_post( $this->field_before() );
			echo wp_kses_post( $this->shortcoder() );
			echo '<textarea name="' . esc_attr( $this->field_name() ) . '"' . wp_kses_post( $this->field_attributes() ) . '>' . esc_html( $this->value ) . '</textarea>';
			echo wp_kses_post( $this->field_after() );
		}

		public function shortcoder() {
			if ( ! empty( $this->field['shortcoder'] ) ) {
				$instances = ( is_array( $this->field['shortcoder'] ) ) ? $this->field['shortcoder'] : array_filter( (array) $this->field['shortcoder'] );

				foreach ( $instances as $instance_key ) {
					if ( isset( ADMINIFY::$shortcode_instances[ $instance_key ] ) ) {
						$button_title = ADMINIFY::$shortcode_instances[ $instance_key ]['button_title'];

						echo '<a href="#" class="button button-primary adminify-shortcode-button" data-modal-id="' . esc_attr( $instance_key ) . '">' . wp_kses_post( $button_title ) . '</a>';
					}
				}
			}
		}
	}
}
