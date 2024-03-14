<?php
/**
 * Image Accordion integration
 */
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

class WPML_Image_Accordion extends WPML_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'accordion_items';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'label',
			'title',
			'description',
			'button_label',
			'button_url' => ['url'],
			'link_url' => ['url'],
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'label':
				return __( 'Image Accordion: Label', 'skt-addons-elementor' );
			case 'title':
				return __( 'Image Accordion: Title', 'skt-addons-elementor' );
			case 'description':
				return __( 'Image Accordion: Description', 'skt-addons-elementor' );
			case 'button_label':
				return __( 'Image Accordion: Button Label', 'skt-addons-elementor' );
			case 'button_url':
				return __( 'Image Accordion: Button URL', 'skt-addons-elementor' );
			case 'link_url':
				return __( 'Image Accordion: Link URL', 'skt-addons-elementor' );
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
			case 'label':
				return 'LINE';
			case 'title':
				return 'AREA';
			case 'description':
				return 'AREA';
			case 'button_label':
				return 'LINE';
			case 'button_url':
				return 'LINK';
			case 'link_url':
				return 'LINK';
			default:
				return '';
		}
	}
}