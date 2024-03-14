<?php
/**
 * Skills integration
 */
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

class WPML_Skills extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'skills';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return ['name'];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'name':
				return __( 'Skills: Skill Name', 'skt-addons-elementor' );
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
			case 'name':
				return 'LINE';
			default:
				return '';
		}
	}
}