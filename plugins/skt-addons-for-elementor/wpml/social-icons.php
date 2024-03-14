<?php
/**
 * Social Icons integration
 */
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

class WPML_Social_Icons extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'skt_addons_elementor_social_icon_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'skt_addons_elementor_social_icon_title',
			'skt_addons_elementor_social_link' => ['url']
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'skt_addons_elementor_social_icon_title':
				return __( 'Social Icons: Title', 'skt-addons-elementor' );
			case 'url':
				return __( 'Social Icons: Link', 'skt-addons-elementor' );
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
			case 'skt_addons_elementor_social_icon_title':
				return 'LINE';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}
}