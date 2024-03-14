<?php
/**
 * This file contains the class for rendering the analytics page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that renders the analytics page.
 */
class Nelio_Content_Analytics_Page extends Nelio_Content_Abstract_Page {

	public function __construct() {

		parent::__construct(
			'nelio-content',
			'nelio-content-analytics',
			_x( 'Analytics', 'text', 'nelio-content' ),
			nc_can_current_user_use_plugin()
		);

	}//end __construct()

	// @Overrides
	// phpcs:ignore
	public function init() {

		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'use_analytics' ) ) {
			return;
		}//end if

		parent::init();

	}//end init()

	// @Implements
	// phpcs:ignore
	public function enqueue_assets() {

		wp_enqueue_style(
			'nelio-content-analytics-page',
			nelio_content()->plugin_url . '/assets/dist/css/analytics-page.css',
			array( 'nelio-content-components' ),
			nc_get_script_version( 'analytics-page' )
		);
		nc_enqueue_script_with_auto_deps( 'nelio-content-analytics-page', 'analytics-page', true );

		wp_add_inline_script(
			'nelio-content-analytics-page',
			'NelioContent.initPage( "nelio-content-analytics-page" );'
		);

	}//end enqueue_assets()

}//end class
