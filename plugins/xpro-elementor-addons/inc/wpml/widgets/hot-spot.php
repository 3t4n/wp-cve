<?php

namespace XproElementorAddons\Inc;

defined( 'ABSPATH' ) || die();

class WPML_Hot_Spot extends \WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'hotspot_items';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'tooltip_text' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'tooltip_text':
				return __( 'Hot Spot: Tooltip Text', 'xpro-elementor-addons' );
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
			case 'tooltip_text':
				return 'VISUAL';
			default:
				return '';
		}
	}
}
