<?php
/**
 * This file adds the page to welcome nwe users and starts the render process.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that adds the welcome page.
 */
class Nelio_Content_Welcome_Page extends Nelio_Content_Abstract_Page {

	public function __construct() {

		parent::__construct(
			'nelio-content',
			'nelio-content',
			_x( 'Welcome', 'text', 'nelio-content' ),
			nc_can_current_user_manage_account()
		);

	}//end __construct()

	// @Implements
	// phpcs:ignore
	public function enqueue_assets() {

		wp_enqueue_style(
			'nelio-content-welcome-page',
			nelio_content()->plugin_url . '/assets/dist/css/welcome-page.css',
			array( 'nelio-content-components' ),
			nc_get_script_version( 'welcome-page' )
		);
		nc_enqueue_script_with_auto_deps( 'nelio-content-welcome-page', 'welcome-page', true );

	}//end enqueue_assets()

}//end class
