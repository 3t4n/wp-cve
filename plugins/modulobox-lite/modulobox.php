<?php
/**
 * ModuloBox Lite
 *
 * @package   ModuloBox Lite
 * @author    Themeone <themeone.master@gmail.com>
 * @link      https://www.theme-one.com/modulobox/
 * @copyright 2017 Themeone
 *
 * Plugin Name:  ModuloBox Lite
 * Plugin URI:   https://www.theme-one.com/modulobox/
 * Description:  A modular, versatile &amp; highly customizable lightbox plugin to display your media in a fully responsive popup.
 * Version:      1.6.0
 * Author:       Themeone
 * Author URI:   https://www.theme-one.com/
 *
 * License:      GPLv2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain:  modulobox
 * Domain Path:  /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ModuloBox' ) ) {

	/**
	 * Main ModuloBox class
	 *
	 * @class ModuloBox
	 * @since 1.0.0
	 *
	 */
	class ModuloBox {

		/**
		 * Cloning disabled
		 *
		 * @since 1.0.0
		 * @access private
		 */
		private function __clone() {
		}

		/**
		 * De-serialization disabled
		 *
		 * @since 1.0.0
		 * @access private
		 */
		private function __wakeup() {
		}

		/**
		 * ModuloBox Constructor
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function __construct() {

			$this->define_constants();
			$this->includes();
			$this->init_hooks();

		}

		/**
		 * Define ModuloBox Constants
		 *
		 * @since 1.0.0
		 * @access private
		 */
		private function define_constants() {

			define( 'MOBX_VERSION', '1.6.0' );
			define( 'MOBX_NAME', 'modulobox' );
			define( 'MOBX_SLUG', 'mobx' );

			define( 'MOBX_FILE', __FILE__ );
			define( 'MOBX_BASE', plugin_basename( MOBX_FILE ) );
			define( 'MOBX_PATH', plugin_dir_path( MOBX_FILE ) );
			define( 'MOBX_URL', plugin_dir_url( MOBX_FILE ) );

			define( 'MOBX_ADMIN_PATH', MOBX_PATH . 'admin/' );
			define( 'MOBX_ADMIN_URL', MOBX_URL . 'admin/' );
			define( 'MOBX_PUBLIC_PATH', MOBX_PATH . 'public/' );
			define( 'MOBX_PUBLIC_URL', MOBX_URL . 'public/' );
			define( 'MOBX_INCLUDES_PATH', MOBX_PATH . 'includes/' );
			define( 'MOBX_INCLUDES_URL', MOBX_URL . 'includes/' );

		}

		/**
		 * Include core files
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function includes() {

			require_once( MOBX_INCLUDES_PATH . 'base.class.php' );
			require_once( MOBX_INCLUDES_PATH . 'normalize-settings.class.php' );

			if ( is_admin() ) {

				require_once( MOBX_ADMIN_PATH . 'admin-init.class.php' );
				require_once( MOBX_ADMIN_PATH . 'settings-field.php' );
				require_once( MOBX_ADMIN_PATH . 'settings-field.class.php' );
				require_once( MOBX_ADMIN_PATH . 'async-request.class.php' );
				require_once( MOBX_ADMIN_PATH . 'attachment.class.php' );

			} else {

				require_once( MOBX_PUBLIC_PATH . 'gallery.class.php' );
				require_once( MOBX_PUBLIC_PATH . 'init.class.php' );

			}

		}

		/**
		 * Hook into actions and filters
		 *
		 * @since 1.0.0
		 * @access private
		 */
		private function init_hooks() {

			// Load plugin text domain
			add_action( 'plugins_loaded', array( $this, 'localize_plugin' ) );
			// Add plugin edit button in plugin list page
			add_filter( 'plugin_action_links_' . MOBX_BASE, array( $this, 'plugin_action_links' ), 10, 4 );

		}

		/**
		 * Localize_plugin
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function localize_plugin() {

			load_plugin_textdomain(
				'modulobox',
				false,
				dirname( MOBX_BASE ) . '/languages'
			);

			// Translate Plugin Description
			__( 'A modular, versatile &amp; highly customizable lightbox plugin to display your media in a fully responsive popup.', 'modulobox' );

		}

		/**
		 * Modify plugin edit link
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $links An array of plugin action links
		 * @return array
		 */
		public function plugin_action_links( $links ) {

			if ( current_user_can( 'manage_options' ) ) {

				$documentation = 'https://theme-one.com/modulobox/documentation/';

				// Add custom action links
				$action_links = array(
					'<a href="' . esc_url( admin_url( 'admin.php?page=' . MOBX_NAME ) ) . '" aria-label="' . esc_attr__( 'View ModuloBox settings', 'modulobox' ) . '">' . esc_html__( 'Settings', 'modulobox' ) . '</a>',
					'<a href="' . esc_url( $documentation ) . '" aria-label="' . esc_attr__( 'View ModuloBox documentation', 'modulobox' ) . '">' . esc_html__( 'Doc', 'modulobox' ) . '</a>',
				);

				$links = array_merge( $action_links, $links );

			}

			return $links;
		}
	}

	new ModuloBox;

}
