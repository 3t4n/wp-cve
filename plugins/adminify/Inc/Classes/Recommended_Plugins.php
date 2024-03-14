<?php
namespace WPAdminify\Inc\Classes;

use WPAdminify\Libs\Recommended;

if ( ! class_exists( 'Recommended_Plugins' ) ) {
	/**
	 * Recommended Plugins class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 */
	class Recommended_Plugins extends Recommended {

		/**
		 * Constructor method
		 */
		public function __construct() {
			$this->menu_order = 62; // for submenu order value should be more than 10 .
			parent::__construct( $this->menu_order );
		}

		/**
		 * Menu list
		 */
		public function menu_items() {
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
		public function plugins_list() {
			return array(
				'ultimate-blocks-for-gutenberg' => array(
					'slug'              => 'ultimate-blocks-for-gutenberg',
					'name'              => 'Master Blocks – Gutenberg Blocks Page Builder',
					'short_description' => 'Gutenberg is now a reality. Easy Blocks is a growing Gutenberg Block Plugin. It’s easy to create multiple columns variations within a single row. Drag and Drop columns size variations gives you more controls over your website. We’re calling it as a Gutenberg Page Builder. If you are using Gutenberg Editor, then you can try this plugin to get maximum customization possibilities.',
					'icon'              => 'https://ps.w.org/ultimate-blocks-for-gutenberg/assets/icon-256x256.png',
					'download_link'     => 'https://downloads.wordpress.org/plugin/ultimate-blocks-for-gutenberg.zip',
					'type'              => array( 'all', 'featured', 'favorites' ),
				),
				'master-addons'                 => array(
					'slug'              => 'master-addons',
					'name'              => 'Master Addons for Elementor',
					'short_description' => 'Master Addons for Elementor provides the most comprehensive Elements & Extensions with a user-friendly interface. It is packed with 50+ Elementor Elements & 20+ Extension.',
					'icon'              => 'https://ps.w.org/master-addons/assets/icon.svg',
					'download_link'     => 'https://downloads.wordpress.org/plugin/master-addons.zip',
					'type'              => array( 'all', 'featured', 'popular', 'favorites' ),
				),
				'prettyphoto'                   => array(
					'slug'              => 'prettyphoto',
					'name'              => 'WordPress prettyPhoto',
					'short_description' => 'Master Addons is Collection of Exclusive & Unique Addons for Elementor Page Builder. This Plugin that gives you full control over Images to show in your website.',
					'icon'              => 'https://ps.w.org/prettyphoto/assets/icon-256x256.png',
					'download_link'     => 'https://downloads.wordpress.org/plugin/prettyphoto.zip',
					'type'              => array( 'all', 'featured', 'popular', 'favorites' ),
				),
				'admin-bar'                     => array(
					'slug'              => 'admin-bar',
					'name'              => 'Admin Bar Remover',
					'short_description' => 'This plugin turns on or off Admin Bar in front end, for all users',
					'icon'              => 'https://s.w.org/plugins/geopattern-icon/admin-bar.svg',
					'download_link'     => 'https://downloads.wordpress.org/plugin/admin-bar.zip',
					'type'              => array( 'featured', 'popular' ),
				),
				'copy-to-clipboard'             => array(
					'slug'              => 'copy-to-clipboard',
					'name'              => 'Copy to Clipboard',
					'short_description' => 'Copy To Clipboard is a WordPress plugin that makes it simple to copy & paste text, paragraphs, blockquotes, coupons codes and source codes from your WP website with a single click. It’s an efficient way to save effort and time while navigating through your WordPress site content',
					'icon'              => 'https://ps.w.org/copy-to-clipboard/assets/icon.svg?rev=2840276',
					'download_link'     => 'https://downloads.wordpress.org/plugin/copy-to-clipboard.zip',
					'type'              => array( 'featured', 'popular' ),
				),

			);
		}

		/**
		 * Admin submenu
		 */
		public function admin_menu() {
			// For submenu .
			$this->sub_menu = add_submenu_page(
				'wp-adminify-settings',       // Ex. wp-adminify-settings /  edit.php?post_type=page .
				__( 'Recommended Plugins', 'adminify' ),
				__( 'Recommended Plugins', 'adminify' ),
				'manage_options',
				'wp-adminify-recommended-plugins',
				array( $this, 'render_recommended_plugins' )
			);
		}
	}
}
