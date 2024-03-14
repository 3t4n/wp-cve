<?php

namespace XproElementorAddons\Inc;

defined( 'ABSPATH' ) || die();

class WPML_Hero_Slider extends \WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'slide_items';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'subtitle', 'title', 'description', 'primary_button_title', 'secondary_button_title' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'subtitle':
				return __( 'Hero Slider: Subtitle', 'xpro-elementor-addons' );
			case 'title':
				return __( 'Hero Slider: Title', 'xpro-elementor-addons' );
			case 'description':
				return __( 'Hero Slider: Description', 'xpro-elementor-addons' );
			case 'primary_button_title':
				return __( 'Hero Slider: Primary Button', 'xpro-elementor-addons' );
			case 'secondary_button_title':
				return __( 'Hero Slider: Secondary Button', 'xpro-elementor-addons' );
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
			case 'Subtitle':
			case 'title':
				return 'AREA';
			case 'description':
				return 'VISUAL';
			case 'primary_button_title':
			case 'secondary_button_title':
				return 'LINE';
			default:
				return '';
		}
	}
}
