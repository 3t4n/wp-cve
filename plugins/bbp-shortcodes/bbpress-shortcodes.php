<?php
/**
 * Plugin Name: bbPress Shortcodes
 * Plugin URI:  https://wordpress.org/plugins/bbp-shortcodes/
 * Description: The bbPress Shortcodes plugin provides a TinyMCE dropdown button in the visual editor that users can access to use any bbPress shortcodes.
 * Version:     2.0
 * Author:      Vinod Dalvi
 * Author URI:  https://profiles.wordpress.org/vinod-dalvi/
 * License:     GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 * Text Domain: bbpress-shortcodes
 *
 *
 * bbPress Shortcodes is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * bbPress Shortcodes is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with bbPress Shortcodes. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */


/**
 * Includes necessary dependencies and starts the plugin.
 *
 * @package BBPS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exits if accessed directly.
}

if ( ! class_exists( 'BBPress_Shortcodes' ) ) {

	/**
	 * Main bbPress Shortcodes Class.
	 *
	 * @class BBPress_Shortcodes
	 */
	final class BBPress_Shortcodes {

		/**
		 * Stores plugin options.
		 */
		public $opt;

		/**
		 * Core singleton class
		 * @var self
		 */
		private static $_instance;

		/**
		 * bbPress Shortcodes Constructor.
		 */
		public function __construct() {
			$this->opt = get_option( 'bbpress_shortcodes' );
			$this->define_constants();
			$this->includes();
			$this->init_hooks();
		}

		/**
		 * Gets the instance of this class.
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( ! ( self::$_instance instanceof self ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Defines bbPress Shortcodes Constants.
		 */
		private function define_constants() {
			define( 'BBPS_VERSION', '2.0' );
			define( 'BBPS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			define( 'BBPS_PLUGIN_FILE', __FILE__ );
		}

		/**
		 * Includes required core files used in admin and on the frontend.
		 */
		public function includes() {
			require_once BBPS_PLUGIN_DIR . 'includes/class-bbps-activator.php';
			require_once BBPS_PLUGIN_DIR . 'includes/class-bbps-deactivator.php';
			require_once BBPS_PLUGIN_DIR . 'includes/class-bbps-i18n.php';
			if ( is_admin() ) {
				require_once BBPS_PLUGIN_DIR . 'admin/class-bbps-admin.php';
			} else {
				require_once BBPS_PLUGIN_DIR . 'public/class-bbps-public.php';
			}
			require_once BBPS_PLUGIN_DIR . 'includes/class-bbps-loader.php';
		}

		/**
		 * Hooks into actions and filters.
		 */
		private function init_hooks() {
			// Executes necessary actions on plugin activation and deactivation.
			register_activation_hook( BBPS_PLUGIN_FILE, array( 'BBPS_Activator', 'activate' ) );
			register_deactivation_hook( BBPS_PLUGIN_FILE, array( 'BBPS_Deactivator', 'deactivate' ) );
		}
	}
}

/**
 * Starts plugin execution.
 */
$bbps = BBPress_Shortcodes::getInstance();
new BBPS_Loader( $bbps );
