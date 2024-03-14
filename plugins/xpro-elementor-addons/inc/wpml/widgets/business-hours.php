<?php

namespace XproElementorAddons\Inc;

defined( 'ABSPATH' ) || die();

class WPML_Business_Hours extends \WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'business_item';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'business_day', 'business_time' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'business_day':
				return __( 'Business Hours: Day', 'xpro-elementor-addons' );
			case 'business_time':
				return __( 'Business Hours: Time', 'xpro-elementor-addons' );
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
			case 'business_day':
			case 'business_time':
				return 'LINE';
			default:
				return '';
		}
	}
}
