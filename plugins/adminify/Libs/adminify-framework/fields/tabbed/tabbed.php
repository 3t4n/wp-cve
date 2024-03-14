<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: tabbed
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Field_tabbed' ) ) {
	class ADMINIFY_Field_tabbed extends ADMINIFY_Fields {

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render() {
			$unallows = [ 'tabbed' ];

			echo wp_kses_post( $this->field_before() );

			echo '<div class="adminify-tabbed-nav" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';
			foreach ( $this->field['tabs'] as $key => $tab ) {
				$tabbed_icon   = ( ! empty( $tab['icon'] ) ) ? '<i class="adminify--icon ' . wp_kses_post( $tab['icon'] ) . '"></i>' : '';
				$tabbed_active = ( empty( $key ) ) ? 'adminify-tabbed-active' : '';

				echo '<a href="#" class="' . esc_attr( $tabbed_active ) . '"">' . wp_kses_post( $tabbed_icon . $tab['title'] ) . '</a>';
			}
			echo '</div>';

			echo '<div class="adminify-tabbed-contents">';
			foreach ( $this->field['tabs'] as $key => $tab ) {
				$tabbed_hidden = ( ! empty( $key ) ) ? ' hidden' : '';

				echo '<div class="adminify-tabbed-content' . esc_attr( $tabbed_hidden ) . '">';

				foreach ( $tab['fields'] as $field ) {
					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true; }

					$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_value   = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
					$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

					ADMINIFY::field( $field, $field_value, $unique_id, 'field/tabbed' );
				}

				echo '</div>';
			}
			echo '</div>';

			echo wp_kses_post( $this->field_after() );
		}

	}
}
