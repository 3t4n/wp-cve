<?php

class WPML_NJBA_InfoList extends WPML_Beaver_Builder_Module_With_Items {

	public function &get_items( $settings ) {
		return $settings->info_list_content;
	}

	public function get_fields() {
		return array( 'title', 'text' );
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return esc_html__( 'Infolist - Title', 'bb-njba' );

			case 'text':
				return esc_html__( 'Infolist - Content', 'bb-njba' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title':
				return 'LINE';

			case 'text':
				return 'VISUAL';

			default:
				return '';
		}
	}

}
