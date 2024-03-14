<?php

namespace MasterAddons\Inc\Classes;

use MasterAddons\Lib\Recommended;

if (!class_exists('Recommended_Plugins')) {
	/**
	 * Recommended Plugins class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 */
	class Recommended_Plugins extends Recommended
	{

		/**
		 * Constructor method
		 */
		public function __construct()
		{
			$this->menu_order = 51; // for submenu order value should be more than 10 .
			parent::__construct($this->menu_order);
		}

		/**
		 * Menu list
		 */
		public function menu_items()
		{
			return array(
				array(
					'key'   => 'all',
					'label' => 'All',
				),
				array(
					'key'   => 'featured', // key should be used as category to the plugin list.
					'label' => 'Featured Item',
				),
				array(
					'key'   => 'popular',
					'label' => 'Popular',
				),
				array(
					'key'   => 'favorites',
					'label' => 'Favorites',
				),
			);
		}

		/**
		 * Plugins List
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function plugins_list()
		{
			return array(
				'adminify'                    => array(
					'slug'              => 'adminify',
					'name'              => 'WP Adminify – Custom Login, Admin Dashboard, Admin Columns | White Label | Media Library Folders',
					'short_description' => 'WP Adminify enhances the WordPress Dashboard Customization journey. It comes with 20+ modules, such as Media Folder, Login Customizer, Menu Editor, Admin Columns, Activity Logs, Disable Admin Notice, WordPress White Label, Admin Page, Google Pagespeed Insights, Custom CSS/JS, and many more. It can be your best choice because of its Multiple Dashboard UI Templates and lightweight size (4MB).',
					'icon'              => 'https://ps.w.org/adminify/assets/icon.svg',
					'download_link'     => 'https://downloads.wordpress.org/plugin/adminify.zip',
					'type'              => array('all', 'featured', 'popular', 'favorites'),
				),
				'ultimate-blocks-for-gutenberg'                    => array(
					'slug'              => 'ultimate-blocks-for-gutenberg',
					'name'              => 'Master Blocks – Gutenberg Blocks Page Builder',
					'short_description' => 'Gutenberg is now a reality. Easy Blocks is a growing Gutenberg Block Plugin. It’s easy to create multiple columns variations within a single row. Drag and Drop columns size variations gives you more controls over your website. We’re calling it as a Gutenberg Page Builder. If you are using Gutenberg Editor, then you can try this plugin to get maximum customization possibilities.',
					'icon'              => 'https://ps.w.org/ultimate-blocks-for-gutenberg/assets/icon-256x256.png',
					'download_link'     => 'https://downloads.wordpress.org/plugin/ultimate-blocks-for-gutenberg.zip',
					'type'              => array('all', 'featured', 'favorites'),
				),
				'darken'                    => array(
					'slug'              => 'darken',
					'name'              => 'Darken - WordPress Dark Mode',
					'short_description' => 'Darken WordPress plugin allows users to easily configure and switch between dark and light modes in their WordPress dashboard.	',
					'icon'              => 'https://ps.w.org/darken/assets/icon-256x256.png',
					'download_link'     => 'https://downloads.wordpress.org/plugin/darken.zip',
					'type'              => array('all', 'featured', 'popular', 'favorites'),
				),
				'image-comparison-elementor-addon'                    => array(
					'slug'              => 'image-comparison-elementor-addon',
					'name'              => 'Image Comparison Elementor Addon',
					'short_description' => 'Image Comparison Elementor Addon is a Plugin that gives you the control to add before and after image. You will get the full control to customize everything you need for image or photo comparison.',
					'icon'              => 'https://ps.w.org/image-comparison-elementor-addon/assets/icon-256x256.png',
					'download_link'     => 'https://downloads.wordpress.org/plugin/image-comparison-elementor-addon.zip',
					'type'              => array('all'),
				),
				'wp-awesome-faq'                    => array(
					'slug'              => 'wp-awesome-faq',
					'name'              => 'Master Accordion ( Former WP Awesome FAQ Plugin )',
					'short_description' => 'No need extra configurations for WP Awesome FAQ Plugin. WP Awesome FAQ Plugin allows to create unlimited FAQ Items with Title, Description. With the plugin installation’s a Custom Post Type named “FAQ” will be created automatically. To show all FAQ’s items a shortcode [faq] needed.',
					'icon'              => 'https://ps.w.org/wp-awesome-faq/assets/icon-128x128.png',
					'download_link'     => 'https://downloads.wordpress.org/plugin/wp-awesome-faq.zip',
					'type'              => array('all', 'featured', 'popular'),
				),
				'prettyphoto'                    => array(
					'slug'              => 'prettyphoto',
					'name'              => 'WordPress prettyPhoto',
					'short_description' => 'Master Addons is Collection of Exclusive & Unique Addons for Elementor Page Builder. This Plugin that gives you full control over Images to show in your website.',
					'icon'              => 'https://ps.w.org/prettyphoto/assets/icon-256x256.png',
					'download_link'     => 'https://downloads.wordpress.org/plugin/prettyphoto.zip',
					'type'              => array('all', 'featured', 'popular', 'favorites'),
				),
				'copy-to-clipboard'		=> array(
					'slug'              => 'copy-to-clipboard',
					'name'              => 'WordPress prettyPhoto',
					'short_description' => 'Copy To Clipboard is a WordPress plugin that makes it simple to copy & paste text, paragraphs, blockquotes, coupons codes and source codes from your WP website with a single click. An efficient way to save effort and time while navigating through your WordPress site content',
					'icon'              => 'https://ps.w.org/copy-to-clipboard/assets/icon.svg',
					'download_link'     => 'https://downloads.wordpress.org/plugin/copy-to-clipboard.zip',
					'type'              => array('all', 'featured', 'popular', 'favorites'),
				),
				'admin-bar'		=> array(
					'slug'              => 'admin-bar',
					'name'              => 'Admin Bar Editor',
					'short_description' => 'WordPress Admin Bar editor for backend and frontend.',
					'icon'              => 'https://s.w.org/plugins/geopattern-icon/admin-bar.svg',
					'download_link'     => 'https://downloads.wordpress.org/plugin/admin-bar.zip',
					'type'              => array('all', 'featured', 'popular', 'favorites'),
				),

			);
		}

		/**
		 * Admin submenu
		 */
		public function admin_menu()
		{
			// For submenu .
			$this->sub_menu = add_submenu_page(
				'master-addons-settings',       // Ex. master-addons-settings /  edit.php?post_type=page .
				__('Recommended', 'master-addons'),
				__('Recommended', 'master-addons'),
				'manage_options',
				'master-addons-recommended-plugins',
				array($this, 'render_recommended_plugins')
			);
		}
	}
	new Recommended_Plugins();
}
