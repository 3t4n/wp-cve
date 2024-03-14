<?php
	/**
	 * Main WPSyncSheetsElementor namespace.
	 *
	 * @since 1.0.0
	 * @package wpsyncsheets-elementor
	 */

namespace WPSyncSheetsElementor {
	/**
	 * Main WPSyncSheetsElementor class.
	 *
	 * @since 1.0.0
	 * @package wpsyncsheets-elementor
	 */
	final class WPSyncSheetsElementor {
		/**
		 * Instance of class variable.
		 *
		 * @since 1.0.0
		 *
		 * @var \WPSyncSheetsElementor\WPSyncSheetsElementor
		 */
		private static $instance;

		/**
		 * Plugin version for enqueueing, etc.
		 * The value is got from WPSSLE_VERSION constant.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $version = '';

		/**
		 * Main WPSyncSheetsElementor Instance.
		 *
		 * Only one instance of WPSyncSheetsElementor exists in memory at any one time.
		 * Also prevent the need to define globals all over the place.
		 *
		 * @since 1.0.0
		 *
		 * @return WPSyncSheetsElementor
		 */
		public static function instance() {

			if ( null === self::$instance || ! self::$instance instanceof self ) {
				self::$instance = new self();
				self::$instance->constants();
				self::$instance->includes();
				add_action( 'init', array( self::$instance, 'load_textdomain' ), 10 );
			}

			return self::$instance;
		}

		/**
		 * Load the plugin language files.
		 *
		 * @since 1.0.0
		 */
		public function load_files() {
			self::$instance = new self();
			self::$instance->wpssle_include();
		}

		/**
		 * Setup plugin constants.
		 * All the path/URL related constants are defined in main plugin file.
		 *
		 * @since 1.0.0
		 */
		private function constants() {
			$this->version = WPSSLE_VERSION;
		}

		/**
		 * Load the plugin language files.
		 *
		 * @since 1.0.0
		 */
		public function load_textdomain() {

			// If the user is logged in, unset the current text-domains before loading our text domain.
			// This feels hacky, but this way a user's set language in their profile will be used,
			// rather than the site-specific language.
			if ( is_user_logged_in() ) {
				unload_textdomain( 'wpsse' );
			}
			load_plugin_textdomain( 'wpsse', false, WPSSLE_DIRECTORY . '/assets/languages/' );
		}

		/**
		 * Include files.
		 *
		 * @since 1.0.0
		 */
		private function includes() {

			// Global Includes.

			require_once WPSSLE_PATH . '/includes/class-wpssle-google-api.php';
			require_once WPSSLE_PATH . '/includes/class-wpssle-google-api-functions.php';
			require_once WPSSLE_PATH . '/includes/class-wpssle-plugin-setting.php';
		}
	}
}

namespace {

	/**
	 * The function which returns the one WPSSLE instance.
	 *
	 * @since 1.0.0
	 *
	 * @return WPSSLE\wpssle
	 */
	function wpssle() {
		return WPSyncSheetsElementor\WPSyncSheetsElementor::instance();
	}
	class_alias( 'WPSyncSheetsElementor\WPSyncSheetsElementor', 'WPSyncSheetsElementor' );
}
