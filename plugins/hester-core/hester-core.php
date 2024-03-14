<?php
/**
 * Plugin Name: Hester Core
 * Description: Additional features for Hester WordPress Theme.
 * Author:      Peregrine Themes
 * Author URI:  https://peregrine-themes.com
 * Version:     1.0.7
 * Text Domain: hester-core
 * Domain Path: languages
 *
 * Hester Core is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Hester Core is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Social Snap. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Hester Core
 * @author     Peregrine Themes <peregrinethemes@gmail.com>
 * @since      1.0.0
 * @license    GPL-3.0+
 * @copyright  Copyright (c) 2022, Hester
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Don't allow multiple versions to be active.
if ( ! class_exists( 'Hester_Core' ) ) {

	/**
	 * Main Hester Core class.
	 *
	 * @since 1.0.0
	 * @package Hester Core
	 */
	final class Hester_Core {

		/**
		 * Singleton instance of the class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance;

		/**
		 * Plugin version for enqueueing, etc.
		 *
		 * @since 1.0.0
		 * @var sting
		 */
		public $version = '1.0.7';

		public $theme_name = 'hester';

		/**
		 * Main Hester Core Instance.
		 *
		 * Insures that only one instance of Hester Core exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 * @return Hester_Core
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Core ) ) {

				self::$instance = new Hester_Core();
				self::$instance->constants();
				self::$instance->load_textdomain();
				self::$instance->includes();
				self::$instance->objects();


				$theme = wp_get_theme();
				preg_match("/^([\w]+)/", $theme->template, $match);
				self::$instance->theme_name = strtolower( $match[0] );

				add_action( 'plugins_loaded', array( self::$instance, 'objects' ), 10 );
			}

			return self::$instance;
		}

		/**
		 * Setup plugin constants.
		 *
		 * @since 1.0.0
		 */
		private function constants() {

			// Plugin version.
			if ( ! defined( 'HESTER_CORE_VERSION' ) ) {
				define( 'HESTER_CORE_VERSION', $this->version );
			}

			// Plugin Folder Path.
			if ( ! defined( 'HESTER_CORE_PLUGIN_DIR' ) ) {
				define( 'HESTER_CORE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'HESTER_CORE_PLUGIN_URL' ) ) {
				define( 'HESTER_CORE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File.
			if ( ! defined( 'HESTER_CORE_PLUGIN_FILE' ) ) {
				define( 'HESTER_CORE_PLUGIN_FILE', __FILE__ );
			}
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @since 1.0.0
		 */
		public function load_textdomain() {

			load_plugin_textdomain( 'hester-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Include files.
		 *
		 * @since 1.0.0
		 */
		private function includes() {

			// Global includes.
			require_once HESTER_CORE_PLUGIN_DIR . 'core/widgets/widgets.php';

			require_once HESTER_CORE_PLUGIN_DIR . 'core/admin/class-hester-core-admin.php';

			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				require_once HESTER_CORE_PLUGIN_DIR . 'core/cli/class-hester-core-cli.php';
			}

			// theme specific inlcude
			$theme = wp_get_theme(); // gets the current theme
			if ( 'Hester' == $theme->name || 'hester' === $theme->template ) {
				require_once 'themes/hester/hester.php';
			}
		}

		/**
		 * Setup objects to be used throughout the plugin.
		 *
		 * @since 1.0.0
		 */
		public function objects() {

			// Hook now that all of the Hester Core stuff is loaded.
			do_action( 'hester_core_loaded' );
		}
	}

	/**
	 * The function which returns the one Hester_Core instance.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $hester_core = hester_core(); ?>
	 *
	 * @since 1.0.0
	 * @return object
	 */
	function hester_core() {
		return Hester_Core::instance();
	}

	$theme = wp_get_theme();
	if ( 'hester' === $theme->template || 'hester-pro' === $theme->template || 'blogun' === $theme->template || 'blogun-pro' === $theme->template || 'bloglo' === $theme->template || 'bloglo-pro' === $theme->template ) {
		hester_core();
	} else {
		add_action( 'admin_notices', 'hester_core_theme_notice' );
	}

	/**
	 * Display notice.
	 *
	 * @since 1.0.0
	 */
	function hester_core_theme_notice() {
		echo '<div class="notice notice-warning"><p>' . __( 'Please activate one of Peregrine themes before activating Hester Core.', 'hester-core' ) . '</p></div>';
	}
}
