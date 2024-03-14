<?php

namespace XproElementorAddons\Inc;

defined( 'ABSPATH' ) || die();

class WPML_Simple_Portfolio extends \WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'gallery';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'filter', 'title_text', 'desc_text' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'filter':
				return __( 'Simple Portfolio: Filter Name', 'xpro-elementor-addons' );
			case 'title_text':
				return __( 'Simple Portfolio: Title', 'xpro-elementor-addons' );
			case 'desc_text':
				return __( 'Simple Portfolio: Description', 'xpro-elementor-addons' );
			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title_text':
			case 'filter':
				return 'LINE';
			case 'desc_text':
				return 'AREA';
			default:
				return '';
		}
	}
}
