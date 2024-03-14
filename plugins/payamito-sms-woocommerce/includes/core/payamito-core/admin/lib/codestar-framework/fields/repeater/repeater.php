<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Field: repeater
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Field_repeater' ) ) {
	class KIANFR_Field_repeater extends KIANFR_Fields
	{

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$args = wp_parse_args( $this->field, [
				'max'          => 0,
				'min'          => 0,
				'button_title' => '<i class="fas fa-plus-circle"></i>',
			] );

			if ( preg_match( '/' . preg_quote( '[' . $this->field['id'] . ']' ) . '/', $this->unique ) ) {
				echo '<div class="kianfr-notice kianfr-notice-danger">' . esc_html__( 'Error: Field ID conflict.', 'kianfr' ) . '</div>';
			} else {
				echo $this->field_before();

				echo '<div class="kianfr-repeater-item kianfr-repeater-hidden" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';
				echo '<div class="kianfr-repeater-content">';
				foreach ( $this->field['fields'] as $field ) {
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_unique  = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . '][0]' : $this->field['id'] . '[0]';

					KIANFR::field( $field, $field_default, '___' . $field_unique, 'field/repeater' );
				}
				echo '</div>';
				echo '<div class="kianfr-repeater-helper">';
				echo '<div class="kianfr-repeater-helper-inner">';
				echo '<i class="kianfr-repeater-sort fas fa-arrows-alt"></i>';
				echo '<i class="kianfr-repeater-clone far fa-clone"></i>';
				echo '<i class="kianfr-repeater-remove kianfr-confirm fas fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'kianfr' ) . '"></i>';
				echo '</div>';
				echo '</div>';
				echo '</div>';

				echo '<div class="kianfr-repeater-wrapper kianfr-data-wrapper" data-field-id="[' . esc_attr( $this->field['id'] ) . ']" data-max="' . esc_attr( $args['max'] ) . '" data-min="' . esc_attr( $args['min'] ) . '">';

				if ( ! empty( $this->value ) && is_array( $this->value ) ) {
					$num = 0;

					foreach ( $this->value as $key => $value ) {
						echo '<div class="kianfr-repeater-item">';
						echo '<div class="kianfr-repeater-content">';
						foreach ( $this->field['fields'] as $field ) {
							$field_unique = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . '][' . $num . ']' : $this->field['id'] . '[' . $num . ']';
							$field_value  = ( isset( $field['id'] ) && isset( $this->value[ $key ][ $field['id'] ] ) ) ? $this->value[ $key ][ $field['id'] ] : '';

							KIANFR::field( $field, $field_value, $field_unique, 'field/repeater' );
						}
						echo '</div>';
						echo '<div class="kianfr-repeater-helper">';
						echo '<div class="kianfr-repeater-helper-inner">';
						echo '<i class="kianfr-repeater-sort fas fa-arrows-alt"></i>';
						echo '<i class="kianfr-repeater-clone far fa-clone"></i>';
						echo '<i class="kianfr-repeater-remove kianfr-confirm fas fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'kianfr' ) . '"></i>';
						echo '</div>';
						echo '</div>';
						echo '</div>';

						$num ++;
					}
				}

				echo '</div>';

				echo '<div class="kianfr-repeater-alert kianfr-repeater-max">' . esc_html__( 'You cannot add more.', 'kianfr' ) . '</div>';
				echo '<div class="kianfr-repeater-alert kianfr-repeater-min">' . esc_html__( 'You cannot remove more.', 'kianfr' ) . '</div>';
				echo '<a href="#" class="button button-primary kianfr-repeater-add">' . $args['button_title'] . '</a>';

				echo $this->field_after();
			}
		}

		public function enqueue()
		{
			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}
		}

	}
}
