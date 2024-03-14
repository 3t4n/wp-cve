<?php

namespace XproElementorAddons\Inc;

defined( 'ABSPATH' ) || die();

class WPML_Horizontal_Timeline extends \WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'horizontal_timeline_item';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'title', 'date_custom', 'sub_title', 'description', 'custom' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return __( 'Horizontal Timeline: Title', 'xpro-elementor-addons' );
			case 'date_custom':
				return __( 'Horizontal Timeline: Date', 'xpro-elementor-addons' );
			case 'sub_title':
				return __( 'Horizontal Timeline: Sub Title', 'xpro-elementor-addons' );
			case 'description':
				return __( 'Horizontal Timeline: Description', 'xpro-elementor-addons' );
			case 'custom':
				return __( 'Horizontal Timeline: Custom', 'xpro-elementor-addons' );
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
			case 'title':
			case 'date_custom':
			case 'sub_title':
			case 'custom':
				return 'LINE';
			case 'description':
				return 'AREA';
			default:
				return '';
		}
	}
}
