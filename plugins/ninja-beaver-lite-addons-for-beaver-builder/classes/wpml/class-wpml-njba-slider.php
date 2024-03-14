<?php

class WPML_NJBA_Slider extends WPML_Beaver_Builder_Module_With_Items {

	public function &get_items( $settings ) {
		return $settings->photos;
	}

	public function get_fields() {
		return array( 'main_title', 'sub_title', 'button_text', 'link', 'separator_text_select' );
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'main_title':
				return esc_html__( 'Slider - Main Title', 'bb-njba' );

			case 'sub_title':
				return esc_html__( 'Slider - Sub Title', 'bb-njba' );

			case 'button_text':
				return esc_html__( 'Slider - Button Text', 'bb-njba' );

			case 'link':
				return esc_html__( 'Slider - Button Url', 'bb-njba' );

			case 'separator_text_select':
				return esc_html__( 'Slider - Separator Text', 'bb-njba' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'main_title':
				return 'LINE';

			case 'sub_title':
				return 'VISUAL';

			case 'button_text':
				return 'LINE';

			case 'link':
				return 'LINK';

			case 'separator_text_select':
				return 'LINE'; 
				
			default:
				return '';
		}
	}

}
