<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Field: textarea
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Field_textarea' ) ) {
	class KIANFR_Field_textarea extends KIANFR_Fields
	{

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			echo $this->field_before();
			echo $this->shortcoder();
			echo '<textarea name="' . esc_attr( $this->field_name() ) . '"' . $this->field_attributes() . '>' . $this->value . '</textarea>';
			echo $this->field_after();
		}

		public function shortcoder()
		{
			if ( ! empty( $this->field['shortcoder'] ) ) {
				$instances = ( is_array( $this->field['shortcoder'] ) ) ? $this->field['shortcoder'] : array_filter( (array) $this->field['shortcoder'] );

				foreach ( $instances as $instance_key ) {
					if ( isset( KIANFR::$shortcode_instances[ $instance_key ] ) ) {
						$button_title = KIANFR::$shortcode_instances[ $instance_key ]['button_title'];

						echo '<a href="#" class="button button-primary kianfr-shortcode-button" data-modal-id="' . esc_attr( $instance_key ) . '">' . $button_title . '</a>';
					}
				}
			}
		}
	}
}
