<?php
/**
 * This file contains the class for rendering the feeds page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that renders the feeds page.
 */
class Nelio_Content_Feeds_Page extends Nelio_Content_Abstract_Page {

	public function __construct() {

		parent::__construct(
			'nelio-content',
			'nelio-content-feeds',
			_x( 'Feeds', 'text', 'nelio-content' ),
			nc_can_current_user_use_plugin()
		);

	}//end __construct()

	// @Overrides
	// phpcs:ignore
	public function init() {

		$feeds = get_option( 'nc_feeds', array() );
		if ( empty( $feeds ) ) {
			return;
		}//end if

		parent::init();

	}//end init()

	// @Implements
	// phpcs:ignore
	public function enqueue_assets() {

		wp_enqueue_style(
			'nelio-content-feeds-page',
			nelio_content()->plugin_url . '/assets/dist/css/feeds-page.css',
			array( 'nelio-content-components' ),
			nc_get_script_version( 'feeds-page' )
		);
		nc_enqueue_script_with_auto_deps( 'nelio-content-feeds-page', 'feeds-page', true );

		wp_add_inline_script(
			'nelio-content-feeds-page',
			'NelioContent.initPage( "nelio-content-feeds-page" );'
		);

	}//end enqueue_assets()

}//end class
