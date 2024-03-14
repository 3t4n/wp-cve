<?php
/**
 * Content Switcher integration
 */
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

class WPML_Content_Switcher extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'content_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'title',
			'plain_content'
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return __( 'Content Switcher: Title', 'skt-addons-elementor' );
			case 'plain_content':
				return __( 'Content Switcher: Plain/ HTML Text', 'skt-addons-elementor' );
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
				return 'LINE';
			case 'plain_content':
				return 'AREA';
			default:
				return '';
		}
	}
}