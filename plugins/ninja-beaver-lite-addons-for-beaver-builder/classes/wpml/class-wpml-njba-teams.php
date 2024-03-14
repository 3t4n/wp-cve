<?php

class WPML_NJBA_Teams extends WPML_Beaver_Builder_Module_With_Items {

	public function &get_items( $settings ) {
		return $settings->teams;
	}

	public function get_fields() {
		return array( 'name', 'designation', 'url_text', 'url', 'member_description' );
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'name':
				return esc_html__( 'Teams - Name', 'bb-njba' );

			case 'designation':
				return esc_html__( 'Teams - Designation', 'bb-njba' );

			case 'url_text':
				return esc_html__( 'Teams - Link Text', 'bb-njba' );

			case 'url':
				return esc_html__( 'Teams - Link Url', 'bb-njba' );

			case 'member_description':
				return esc_html__( 'Teams - Member Description', 'bb-njba' );




			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'name':
				return 'LINE';

			case 'designation':
				return 'LINE';

			case 'url_text':
				return 'LINE';

			case 'url':
				return 'LINK';

			case 'member_description':
				return 'VISUAL';

			default:
				return '';
		}
	}

}