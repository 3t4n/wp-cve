<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Field: palette
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Field_palette' ) ) {
	class KIANFR_Field_palette extends KIANFR_Fields
	{

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$palette = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : [];

			echo $this->field_before();

			if ( ! empty( $palette ) ) {
				echo '<div class="kianfr-siblings kianfr--palettes">';

				foreach ( $palette as $key => $colors ) {
					$active  = ( $key === $this->value ) ? ' kianfr--active' : '';
					$checked = ( $key === $this->value ) ? ' checked' : '';

					echo '<div class="kianfr--sibling kianfr--palette' . esc_attr( $active ) . '">';

					if ( ! empty( $colors ) ) {
						foreach ( $colors as $color ) {
							echo '<span style="background-color: ' . esc_attr( $color ) . ';"></span>';
						}
					}

					echo '<input type="radio" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>';
					echo '</div>';
				}

				echo '</div>';
			}

			echo $this->field_after();
		}

	}
}
