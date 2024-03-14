<?php

namespace XproElementorAddons\Inc;

defined( 'ABSPATH' ) || die();

class WPML_Info_List extends \WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'item';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'custom', 'title', 'description' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'custom':
				return __( 'Info List: Custom', 'xpro-elementor-addons' );
			case 'title':
				return __( 'Info List: Title', 'xpro-elementor-addons' );
			case 'description':
				return __( 'Info List: Description', 'xpro-elementor-addons' );
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
			case 'custom':
			case 'title':
				return 'LINE';
			case 'description':
				return 'AREA';
			default:
				return '';
		}
	}
}
