<?php

class WPML_NJBA_Opening_Hours extends WPML_Beaver_Builder_Module_With_Items {

	public function &get_items( $settings ) {
		return $settings->day_panels;
	}

	public function get_fields() {
		return array( 'day', 'time', 'time_2' );
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'day':
				return esc_html__( 'Opening Hours Day', 'bb-njba' );

			case 'time':
				return esc_html__( 'Opening Hours Start Time', 'bb-njba' );

			case 'time_2':
				return esc_html__( 'Opening Hours End Time', 'bb-njba' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'day':
				return 'LINE';

			case 'time':
				return 'LINE';

			case 'time_2':
				return 'LINE';

			default:
				return '';
		}
	}

}
