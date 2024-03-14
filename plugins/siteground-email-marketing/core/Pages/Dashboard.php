<?php
namespace SG_Email_Marketing\Pages;

use SG_Email_Marketing\Pages\Page;

/**
 * Handle all hooks for our Dashboard custom page.
 */
class Dashboard extends Page {

	/**
	 * ID of the page.
	 *
	 * @var string
	 */
	public $page_id = 'sg_email_marketing_forms';

	/**
	 * Register the top level page into the WordPress admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_plugin_main_menu_item() {
		// Add the main menu item in the side menu.
		\add_menu_page(
			__( 'SiteGround Email Marketing', 'siteground-email-marketing' ), // Page title.
			__( 'Email Marketing', 'siteground-email-marketing' ), // Menu item title.
			'manage_options',
			$this->plugin_slug,   // Page slug.
			array( $this, 'render' ),
			\SG_Email_Marketing\URL . '/assets/images/icon.svg'
		);
	}

	/**
	 * Reorder the sub-menu pages.
	 *
	 * @since  1.0.0
	 *
	 * @param   array $menu_order The WP menu order.
	 */
	public function reorder_submenu_pages( $menu_order ) {
		// Load the global sub-menu.
		global $submenu;

		if ( empty( $submenu[ $this->plugin_slug ] ) ) {
			return $menu_order;
		}

		// Hide the dashboard page (default name page).

		unset( $submenu[ $this->plugin_slug ][0] );
		return $menu_order;
	}

	/**
	 * Add styles to WordPress admin head.
	 *
	 * @since  1.0.0.
	 */
	public function admin_print_styles() {
		echo '<style>.toplevel_page_sg-email-marketing.menu-top .wp-menu-image img { width:20px; display:inline;} </style>';
	}
}
