<?php
/**
 * This file defines a helper class to add react-based components in our settings screen.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * Helper class to add react-based components.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */
abstract class Nelio_Content_Abstract_React_Setting extends Nelio_Content_Abstract_Setting {

	protected $value;
	protected $component;

	public function __construct( $name, $component ) {
		parent::__construct( $name );
		$this->component = $component;
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}//end __construct()

	public function set_value( $value ) {
		$this->value = $value;
	}//end set_value()

	public function enqueue_assets() {

		$screen = get_current_screen();
		if ( 'nelio-content_page_nelio-content-settings' !== $screen->id ) {
			return;
		}//end if

		wp_enqueue_style(
			'nelio-content-individual-settings',
			nelio_content()->plugin_url . '/assets/dist/css/individual-settings.css',
			array( 'nelio-content-components' ),
			nc_get_script_version( 'individual-settings' )
		);
		nc_enqueue_script_with_auto_deps( 'nelio-content-individual-settings', 'individual-settings', true );

		$settings = array(
			'component'  => $this->component,
			'id'         => $this->get_field_id(),
			'name'       => $this->option_name . '[' . $this->name . ']',
			'value'      => $this->value,
			'attributes' => $this->get_field_attributes(),
		);

		wp_add_inline_script(
			'nelio-content-individual-settings',
			sprintf(
				'NelioContent.initField( %s, %s );',
				wp_json_encode( $this->get_field_id() ),
				wp_json_encode( $settings )
			)
		);

	}//end enqueue_assets()

	// @Implements
	// phpcs:ignore
	public function display() {
		printf( '<div id="%s"></div>', esc_attr( $this->get_field_id() ) );
	}//end display()

	private function get_field_id() {
		return str_replace( '_', '-', $this->name );
	}//end get_field_id()

	protected function get_field_attributes() {
		return array();
	}//end get_field_attributes()

}//end class
