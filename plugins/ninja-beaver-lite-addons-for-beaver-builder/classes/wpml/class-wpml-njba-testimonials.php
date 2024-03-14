<?php

class WPML_NJBA_Testimonials extends WPML_Beaver_Builder_Module_With_Items {

	public function &get_items( $settings ) {
		return $settings->testimonials;
	}

	public function get_fields() {
		return array( 'title', 'subtitle', 'testimonial' );
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return esc_html__( 'Accordion Item Label', 'bb-njba' );

			case 'subtitle':
				return esc_html__( 'Accordion Item Content', 'bb-njba' );

			case 'testimonial':
				return esc_html__( 'Accordion Item Content', 'bb-njba' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title':
				return 'LINE';

			case 'subtitle':
				return 'LINE';

			case 'testimonial':
				return 'VISUAL';

			default:
				return '';
		}
	}

}
