<?php

class WPML_NJBA_Logo_Grid extends WPML_Beaver_Builder_Module_With_Items {

	public function &get_items( $settings ) {
		return $settings->logos;
	}

	public function get_fields() {
		return array( 'logo_title' );
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'logo_title':
				return esc_html__( 'Logo Grid - Logo Title', 'bb-njba' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'logo_title':
				return 'LINE';

			default:
				return '';
		}
	}

}
