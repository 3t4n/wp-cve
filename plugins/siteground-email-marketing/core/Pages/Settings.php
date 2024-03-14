<?php
namespace SG_Email_Marketing\Pages;

use SG_Email_Marketing\Pages\Page;

/**
 * Handle all hooks for our Settings custom page.
 */
class Settings extends Page {

	/**
	 * Page id.
	 *
	 * @var string
	 */
	public $page_id = 'sg_email_marketing_settings';

	/**
	 * Add a sub-menu item in the WordPress Admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_submenu_item() {
		add_submenu_page(
			$this->plugin_slug,   // Parent slug.
			__( 'Settings', 'siteground-email-marketing' ), // phpcs:ignore
			__( 'Settings', 'siteground-email-marketing' ), // phpcs:ignore
			'manage_options',
			$this->page_id,
			array( $this, 'render' )
		);
	}
}
