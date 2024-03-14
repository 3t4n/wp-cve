<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: accordion
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Field_accordion' ) ) {
	class ADMINIFY_Field_accordion extends ADMINIFY_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			$unallows = [ 'accordion' ];

			echo wp_kses_post( $this->field_before() );

			echo '<div class="adminify-accordion-items" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			foreach ( $this->field['accordions'] as $key => $accordion ) {
				echo '<div class="adminify-accordion-item">';

				$icon = ( ! empty( $accordion['icon'] ) ) ? 'adminify--icon ' . $accordion['icon'] : 'adminify-accordion-icon fas fa-angle-right';

				echo '<h4 class="adminify-accordion-title">';
				echo '<i class="' . esc_attr( $icon ) . '"></i>';
				echo esc_html( $accordion['title'] );
				echo '</h4>';

				echo '<div class="adminify-accordion-content">';

				foreach ( $accordion['fields'] as $field ) {
					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true; }

					$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_value   = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
					$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

					ADMINIFY::field( $field, $field_value, $unique_id, 'field/accordion' );
				}

				echo '</div>';

				echo '</div>';
			}

			echo '</div>';

			echo wp_kses_post( $this->field_after() );
		}

	}
}
