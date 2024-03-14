<?php
/**
 * Bar Chart integration
 */
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

class WPML_Bar_Chart extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'chart_data';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return ['label'];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'label':
				return __( 'Bar Chart: Label Text', 'skt-addons-elementor' );
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
			default:
				return '';
		}
	}
}