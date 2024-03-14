<?php
namespace SG_Email_Marketing\Pages;

use SG_Email_Marketing\Pages\Page;

/**
 * Handle all hooks for our Forms custom page.
 */
class Forms extends Page {

	/**
	 * Page id.
	 *
	 * @var string
	 */
	public $page_id = 'sg_email_marketing_forms';

	/**
	 * Add a sub-menu item in the WordPress Admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_submenu_item() {
		add_submenu_page(
			$this->plugin_slug,   // Parent slug.
			__( 'Forms', 'siteground-email-marketing' ), // phpcs:ignore
			__( 'Forms', 'siteground-email-marketing' ), // phpcs:ignore
			'manage_options',
			$this->page_id,
			array( $this, 'render' )
		);
	}
}
