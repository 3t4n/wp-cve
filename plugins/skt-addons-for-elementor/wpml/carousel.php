<?php
/**
 * Carousel integration
 */
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

class WPML_Carousel extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'slides';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'title',
			'subtitle',
			'link' => ['url']
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
				return __( 'Carousel: Title', 'skt-addons-elementor' );
			case 'subtitle':
				return __( 'Carousel: Subtitle', 'skt-addons-elementor' );
			case 'url':
				return __( 'Carousel: Link', 'skt-addons-elementor' );
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
			case 'subtitle':
				return 'AREA';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}
}