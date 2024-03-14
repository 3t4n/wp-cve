<?php

namespace XproElementorAddons\Inc;

defined( 'ABSPATH' ) || die();

class WPML_Pricing extends \WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'feature_items';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'title_text', 'tooltip_text' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title_text':
				return __( 'Pricing: Feature Title', 'xpro-elementor-addons' );
			case 'tooltip_text':
				return __( 'Pricing: Tooltip Text', 'xpro-elementor-addons' );
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
				return 'LINE';
			case 'tooltip_text':
				return 'AREA';
			default:
				return '';
		}
	}
}
