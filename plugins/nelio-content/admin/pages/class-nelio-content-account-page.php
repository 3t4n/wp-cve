<?php
/**
 * This file adds the account page and starts the render process.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that adds the account page.
 */
class Nelio_Content_Account_Page extends Nelio_Content_Abstract_Page {

	public function __construct() {

		if ( nc_is_subscribed() ) {
			$title = _x( 'Account', 'text', 'nelio-content' );
		} else {
			$title = _x( 'Upgrade', 'user', 'nelio-content' );
		}//end if

		parent::__construct(
			'nelio-content',
			'nelio-content-account',
			$title,
			nc_can_current_user_manage_account()
		);

	}//end __construct()

	// @Implements
	// phpcs:ignore
	public function enqueue_assets() {

		$script   = 'NelioContent.initPage( "nelio-content-account-page", %s );';
		$settings = array(
			'isSubscribed' => nc_is_subscribed(),
			'siteId'       => nc_get_site_id(),
		);

		wp_enqueue_style(
			'nelio-content-account-page',
			nelio_content()->plugin_url . '/assets/dist/css/account-page.css',
			array( 'nelio-content-components' ),
			nc_get_script_version( 'account-page' )
		);
		nc_enqueue_script_with_auto_deps( 'nelio-content-account-page', 'account-page', true );

		wp_add_inline_script(
			'nelio-content-account-page',
			sprintf(
				$script,
				wp_json_encode( $settings ) // phpcs:ignore
			)
		);

	}//end enqueue_assets()

}//end class
