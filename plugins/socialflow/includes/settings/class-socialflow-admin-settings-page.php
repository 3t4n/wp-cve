<?php
/**
 * Abstract class for admin settings pages
 *
 * @package SocialFlow
 * @since 2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
/**
 *  SocialFlow_Admin_Settings_Page.
 */
class SocialFlow_Admin_Settings_Page {
	/**
	 * Holds current page slug
	 *
	 * @since 2.1
	 * @access public
	 * @var string
	 */
	public $slug;

	/**
	 * Add actions to manipulate messages
	 * add menu page on creation
	 */
	public function __construct() {}

	/**
	 * Render current page content here
	 */
	public function page() {}

	/**
	 * Save page settings
	 *
	 * @param array $settings settings to filter.
	 * @return array filtered settings
	 */
	public function save( $settings ) {
		return $settings;
	}

	/**
	 * Output success or failure admin notice when updating options page
	 */
	public function admin_notices() {
		$socialflow_params = filter_input_array( INPUT_GET );
		global $socialflow;
		if ( isset( $socialflow_params['page'] ) && $this->slug === $socialflow_params['page'] && isset( $socialflow_params['settings-updated'] ) && $socialflow_params['settings-updated'] ) {
			$socialflow->render_view( 'notice/options-updated' );
		}
	}
}
