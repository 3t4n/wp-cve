<?php

class WPML_NJBA_PriceBox extends WPML_Beaver_Builder_Module_With_Items {

	public function &get_items( $settings ) {
		return $settings->price_box_content;
	}

	public function get_fields() {
		return array( 'title', 'duration', 'features', 'price', 'button_text', 'link','select_feau_text' );
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return esc_html__( 'Pricebox - Title', 'bb-njba' );
			case 'price':
				return esc_html__( 'Pricebox -  Price', 'bb-njba' );				
			case 'duration':
				return esc_html__( 'Pricebox - Duration', 'bb-njba' );
			case 'features':
				return esc_html__( 'Pricebox - Features', 'bb-njba' );		
			case 'button_text':
				return esc_html__( 'Pricebox - Button Text', 'bb-njba' );
			case 'link':
				return esc_html__( 'Pricebox - Button Url', 'bb-njba' );
			case 'select_feau_text':
				return esc_html__( 'Pricebox - Features Text', 'bb-njba' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title':
				return 'LINE';
			case 'price':
				return 'LINE';
			case 'duration':
				return 'LINE';
			case 'features':
				return 'LINE';
			case 'button_text':
				return 'LINE';
			case 'link':
				return 'LINK';
			case 'select_feau_text':
				return 'LINE';
				
			default:
				return '';
		}
	}

}
