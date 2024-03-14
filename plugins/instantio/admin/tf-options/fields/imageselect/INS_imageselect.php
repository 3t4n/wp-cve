<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

/**
 * Field: imageselect
 * Author MHemel Hasan
 */
if ( ! class_exists( 'INS_imageselect' ) ) {
	class INS_imageselect extends INS_Fields {

		public function __construct( $field, $value = '', $settings_id = '', $parent_field = '' ) {
			parent::__construct( $field, $value, $settings_id, $parent_field );
		}

		public function render() {

			$args = wp_parse_args( $this->field, array(
				'multiple' => false,
				'inline' => false,
				'options' => array(),
			) );

			$inline = ( $args['inline'] ) ? ' tf-inline-list' : '';
			$inlinewrap = ( $args['inline'] ) ? ' tf-inline-list-wrap' : '';

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );


			if ( ! empty( $args['options'] ) ) {

				echo '<div class="tf-image-seletor-wrap' . esc_attr( $inlinewrap ) . '" data-multiple="' . esc_attr( $args['multiple'] ) . '" ' . $this->field_attributes() . ' >';

				$num = 1;

				foreach ( $args['options'] as $key => $option ) {

					$type = ( $args['multiple'] ) ? 'checkbox' : 'radio';
					$extra = ( $args['multiple'] ) ? '[]' : '';
					$active = ( in_array( $key, $value ) ) ? ' tf-active' : '';
					$checked = ( in_array( $key, $value ) ) ? ' checked' : '';

					echo '<div class="tf-image-seletor-items' . esc_attr( $inline ) . esc_attr( $active ) . '">';
					echo '<figure class="tf-image-seletor-card">';
					echo '<img src="' . esc_url( $option['url'] ) . '" alt="img-' . esc_attr( $num++ ) . '" />';
					echo '<input data-depend-id="' . esc_attr( $this->field['id'] ) . '' . $this->parent_field . '" type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>';
					echo '<span class="tf-image-seletor-card-info">' . esc_html( $option['title'] ) . '</span>';
					echo '</figure>';
					echo '</div>';

				}

				echo '</div>';

			}

		}

		public function output() {

			$output = '';
			$bg_image = array();
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$elements = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

			if ( ! empty( $elements ) && isset( $this->value ) && $this->value !== '' ) {
				$output = $elements . '{background-image:url(' . $this->value . ')' . $important . ';}';
			}

			$this->parent_field->output_css .= $output;

			return $output;

		}

	}
}
