<?php

namespace WPAdminify\Inc\Admin;

use WPAdminify\Inc\Utils;
use \WPAdminify\Inc\Classes\Tweaks;
use \WPAdminify\Inc\Classes\MenuStyle;
use \WPAdminify\Inc\Classes\CustomAdminColumns;
use \WPAdminify\Inc\Classes\AdminBar;
use \WPAdminify\Inc\Classes\DashboardWidgets;
use \WPAdminify\Inc\Classes\Sidebar_Widgets;
use \WPAdminify\Inc\Classes\OutputCSS;
use \WPAdminify\Inc\Classes\ThirdPartyCompatibility;
use \WPAdminify\Inc\Classes\AdminFooterText;
use \WPAdminify\Inc\Admin\Modules;
use WPAdminify\Inc\Classes\Adminify_Rollback;
use WPAdminify\Inc\Admin\AdminSettings;

// no direct access allowed
if (!defined('ABSPATH')) {
	exit;
}
/**
 * WP Adminify
 * Admin Class
 *
 * @author Jewel Theme <support@jeweltheme.com>
 */

if (!class_exists('Admin')) {
	class Admin
	{
		public $options;

		public function __construct()
		{
			$this->jltwp_adminify_modules_manager();

			// Remove Page Header like - Dashboard, Plugins, Users etc
			add_action('admin_head', [$this, 'remove_page_headline'], 99);
			add_action('admin_head', [$this, 'change_gutenberg_editor_logo'], 99);

			jltwp_adminify()->add_filter('support_forum_url', [$this, 'jltwp_adminify_support_forum_url']);

			// Disable deactivation feedback form
			jltwp_adminify()->add_filter('show_deactivation_feedback_form', '__return_false');

			// Disable after deactivation subscription cancellation window
			jltwp_adminify()->add_filter('show_deactivation_subscription_cancellation', '__return_false');
		}


		// Gutenberg Editor WordPress Logo Change
		public function change_gutenberg_editor_logo()
		{

			// it is not a necessary thing but it prevents this CSS to be added on every WordPress admin page
			$screen = get_current_screen();
			if (!$screen->is_block_editor) {
				return;
			}

			$gutenberg_editor_logo = (array) AdminSettings::get_instance()->get('gutenberg_editor_logo');

			echo '<style>
				/* hides the logo */
				body.is-fullscreen-mode .edit-post-header a.components-button svg{
					display: none;
				}
				/* adds a custom image */
				body.is-fullscreen-mode .edit-post-header a.components-button:before{
					background-image: url( ' . esc_url($gutenberg_editor_logo['url']) . ' );
					background-size: cover;
					/* you can the image paddings with the parameters below*/
					top: 10px;
					right: 10px;
					bottom: 10px;
					left: 10px;
				}
			</style>';
		}


		/**
		 * WP Adminify: Modules
		 */
		public function jltwp_adminify_modules_manager()
		{
			$this->options = (array) AdminSettings::get_instance()->get();
			new Modules();
			new CustomAdminColumns();
			new MenuStyle($this->options);
			if (!empty($this->options['admin_ui'])) {
				new AdminBar();
			}
			new Tweaks();
			new Sidebar_Widgets();
			new OutputCSS();
			new ThirdPartyCompatibility();
			new AdminFooterText();

			// Register Default Dashboard Widgets
			new DashboardWidgets();

			// Version Rollback
			Adminify_Rollback::get_instance();
		}


		/**
		 * Remove Page Headlines: Dashboard, Plugins, Users
		 *
		 * @return void
		 */
		public function remove_page_headline()
		{
			$screen = get_current_screen();
			if (empty($screen->id)) {
				return;
			}

			if (in_array(
				$screen->id,
				[
					'dashboard',
					'nav-menus',
					'edit-tags',
					'themes',
					'widgets',
					'plugins',
					'plugin-install',
					'users',
					'user',
					'profile',
					'tools',
					'import',
					'export',
					'export-personal-data',
					'erase-personal-data',
					'options-general',
					'options-writing',
					'options-reading',
					'options-discussion',
					'options-media',
					'options-permalink',
				]
			)) {
				echo '<style>#wpbody-content .wrap > h1,#wpbody-content .wrap > h1.wp-heading-inline{display:none;}</style>';
			}
		}


		/**
		 * Support Forum URL
		 *
		 * @param [type] $support_url and Pro Support
		 *
		 * @return void
		 */
		public function jltwp_adminify_support_forum_url($support_url)
		{
			if (jltwp_adminify()->is_premium()) {
				$support_url = 'https://wpadminify.com/support';
			} else {
				$support_url = 'https://wordpress.org/support/plugin/adminify/';
			}
			return $support_url;
		}
	}
}
