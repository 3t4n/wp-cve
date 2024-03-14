<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 * Field: date
 *
 * @since   1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'KIANFR_Field_date' ) ) {
	class KIANFR_Field_date extends KIANFR_Fields
	{

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' )
		{
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		public function render()
		{
			$default_settings = [
				'dateFormat' => 'mm/dd/yy',
			];

			$settings = ( ! empty( $this->field['settings'] ) ) ? $this->field['settings'] : [];
			$settings = wp_parse_args( $settings, $default_settings );

			echo $this->field_before();

			if ( ! empty( $this->field['from_to'] ) ) {
				$args = wp_parse_args( $this->field, [
					'text_from' => esc_html__( 'From', 'kianfr' ),
					'text_to'   => esc_html__( 'To', 'kianfr' ),
				] );

				$value = wp_parse_args( $this->value, [
					'from' => '',
					'to'   => '',
				] );

				echo '<label class="kianfr--from">' . esc_attr( $args['text_from'] ) . ' <input type="text" name="' . esc_attr( $this->field_name( '[from]' ) ) . '" value="' . esc_attr( $value['from'] ) . '"' . $this->field_attributes() . '/></label>';
				echo '<label class="kianfr--to">' . esc_attr( $args['text_to'] ) . ' <input type="text" name="' . esc_attr( $this->field_name( '[to]' ) ) . '" value="' . esc_attr( $value['to'] ) . '"' . $this->field_attributes() . '/></label>';
			} else {
				echo '<input type="text" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes() . '/>';
			}

			echo '<div class="kianfr-date-settings" data-settings="' . esc_attr( json_encode( $settings ) ) . '"></div>';

			echo $this->field_after();
		}

		public function enqueue()
		{
			if ( ! wp_script_is( 'jquery-ui-datepicker' ) ) {
				wp_enqueue_script( 'jquery-ui-datepicker' );
			}
		}

	}
}
