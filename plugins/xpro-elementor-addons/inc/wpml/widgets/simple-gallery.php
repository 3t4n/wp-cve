<?php

namespace XproElementorAddons\Inc;

defined( 'ABSPATH' ) || die();

class WPML_Simple_Gallery extends \WPML_Elementor_Module_With_Items {

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
		return array( 'filter' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'filter':
				return __( 'Simple Gallery: Filter Name', 'xpro-elementor-addons' );
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
			case 'filter':
				return 'LINE';
			default:
				return '';
		}
	}
}
