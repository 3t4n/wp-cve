<?php
/**
 * Horizontal Timeline
 */
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

class WPML_Horizontal_Timeline extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'timeline';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'event_date',
			'event_title',
			'event_subtitle',
			'event_description',
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		if ( $field === 'event_date' ) {
			return __( 'Horizontal Timeline: Date', 'skt-addons-elementor' );
		}

		if ( $field === 'event_title' ) {
			return __( 'Horizontal Timeline: Title', 'skt-addons-elementor' );
		}

		if ( $field === 'event_subtitle' ) {
			return __( 'Horizontal Timeline: Subtitle', 'skt-addons-elementor' );
		}

		if ( $field === 'event_description' ) {
			return __( 'Horizontal Timeline: Description', 'skt-addons-elementor' );
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		if ( $field === 'event_date' ) {
			return 'LINE';
		}

		if ( $field === 'event_title' ) {
			return 'LINE';
		}

		if ( $field === 'event_subtitle' ) {
			return 'LINE';
		}

		if ( $field === 'event_description' ) {
			return 'AREA';
		}
	}
}