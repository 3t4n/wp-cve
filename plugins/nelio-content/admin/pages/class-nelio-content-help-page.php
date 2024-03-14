<?php
/**
 * This file contains the class that registers the help menu item in Nelio Content.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that registers the help menu item in Nelio Content.
 */
class Nelio_Content_Help_Page extends Nelio_Content_Abstract_Page {

	public function __construct() {

		parent::__construct(
			'nelio-content',
			'nelio-content-help',
			_x( 'Help', 'text', 'nelio-content' ),
			nc_can_current_user_use_plugin()
		);

	}//end __construct()

	// @Overrides
	// phpcs:ignore
	public function add_page() {

		parent::add_page();

		global $submenu;
		if ( isset( $submenu['nelio-content'] ) ) {
			$count = count( $submenu['nelio-content'] );
			for ( $i = 0; $i < $count; ++$i ) {
				if ( 'nelio-content-help' === $submenu['nelio-content'][ $i ][2] ) {
					$submenu['nelio-content'][ $i ][2] = add_query_arg( // phpcs:ignore
						array(
							'utm_source'   => 'nelio-content',
							'utm_medium'   => 'plugin',
							'utm_campaign' => 'support',
							'utm_content'  => 'overview-help',
						),
						__( 'https://neliosoftware.com/content/help/', 'nelio-content' )
					);
					break;
				}//end if
			}//end for
		}//end if

	}//end add_page()

	// @Implements
	// phpcs:ignore
	public function enqueue_assets() {
		// Nothing to be done.
	}//end enqueue_assets()

	// @Overwrites
	// phpcs:ignore
	public function display() {
		// Nothing to be done.
	}//end display()

}//end class
