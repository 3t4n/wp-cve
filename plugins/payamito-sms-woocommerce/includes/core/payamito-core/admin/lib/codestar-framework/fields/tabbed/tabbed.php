<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Field: tabbed
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Field_tabbed' ) ) {
	class KIANFR_Field_tabbed extends KIANFR_Fields
	{

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$unallows = [ 'tabbed' ];

			echo $this->field_before();

			echo '<div class="kianfr-tabbed-nav" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';
			foreach ( $this->field['tabs'] as $key => $tab ) {
				$tabbed_icon   = ( ! empty( $tab['icon'] ) ) ? '<i class="kianfr--icon ' . esc_attr( $tab['icon'] ) . '"></i>' : '';
				$tabbed_active = ( empty( $key ) ) ? 'kianfr-tabbed-active' : '';

				echo '<a href="#" class="' . esc_attr( $tabbed_active ) . '"">' . $tabbed_icon . esc_attr( $tab['title'] ) . '</a>';
			}
			echo '</div>';

			echo '<div class="kianfr-tabbed-contents">';
			foreach ( $this->field['tabs'] as $key => $tab ) {
				$tabbed_hidden = ( ! empty( $key ) ) ? ' hidden' : '';

				echo '<div class="kianfr-tabbed-content' . esc_attr( $tabbed_hidden ) . '">';

				foreach ( $tab['fields'] as $field ) {
					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true;
					}

					$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_value   = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
					$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . ']' : $this->field['id'];

					KIANFR::field( $field, $field_value, $unique_id, 'field/tabbed' );
				}

				echo '</div>';
			}
			echo '</div>';

			echo $this->field_after();
		}

	}
}
