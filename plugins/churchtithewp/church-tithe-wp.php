<?php
/*
Plugin Name: ChurchTitheWP
Plugin URI: https://churchtithewp.com
Description: Accept single or recurring tithes on your WordPress site in seconds through Apple Pay, Google Pay, Credit Card, and saved-in-browser credit cards.
Version: 1.0.0.17
Author: Church Tithe WP
Text Domain: church-tithe-wp
Domain Path: languages
License: GPL2
*/

/*
Copyright 2019  Church Tithe WP  (email : support@churchtithewp.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Setup plugin constants.
 *
 * @access private
 * @since 1.0
 * @return void
 */
function church_tithe_wp_setup_constants() {

	// Plugin version.
	if ( ! defined( 'CHURCH_TITHE_WP_VERSION' ) ) {

		$church_tithe_wp_version = '1.0.0.17';

		// If SCRIPT_DEBUG is enabled, break the browser cache.
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			define( 'CHURCH_TITHE_WP_VERSION', $church_tithe_wp_version . time() );
		} else {
			define( 'CHURCH_TITHE_WP_VERSION', $church_tithe_wp_version );
		}
	}

	// Plugin Folder Path.
	if ( ! defined( 'CHURCH_TITHE_WP_PLUGIN_DIR' ) ) {
		define( 'CHURCH_TITHE_WP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}

	// Plugin Folder URL.
	if ( ! defined( 'CHURCH_TITHE_WP_PLUGIN_URL' ) ) {
		define( 'CHURCH_TITHE_WP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}

	// Plugin Root File.
	if ( ! defined( 'CHURCH_TITHE_WP_PLUGIN_FILE' ) ) {
		define( 'CHURCH_TITHE_WP_PLUGIN_FILE', __FILE__ );
	}

	// The default mode for the onboarding wizard.
	if ( ! defined( 'CHURCH_TITHE_WP_WIZARD_TEST_MODE' ) ) {
		define( 'CHURCH_TITHE_WP_WIZARD_TEST_MODE', false );
	}

}
church_tithe_wp_setup_constants();

/**
 * Installation functions
 */
require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/install.php';

if ( ! class_exists( 'Church_Tithe_WP' ) ) {

	/**
	 * Main Church_Tithe_WP Class.
	 *
	 * @since 1.0
	 */
	final class Church_Tithe_WP {

		/**
		 * The instance of Church_Tithe_WP
		 *
		 * @var Church_Tithe_WP
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * Church Tithe WPs Transactions DB Object
		 *
		 * @var object|Church_Tithe_WP_Transactions_DB
		 * @since 1.0
		 */
		public $transactions_db;

		/**
		 * Church Tithe WPs Arrangements DB Object
		 *
		 * @var object|Church_Tithe_WP_Arrangements_DB
		 * @since 1.0
		 */
		public $arrangements_db;

		/**
		 * Main Church_Tithe_WP Instance.
		 *
		 * @since 1.0
		 * @static
		 * @static var array $instance
		 * @uses Church_Tithe_WP::includes() Include the required files.
		 * @uses Church_Tithe_WP::load_textdomain() load the language files.
		 * @see Church_Tithe_WP()
		 * @return object|Church_Tithe_WP The Church_Tithe_WP singleton
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Church_Tithe_WP ) ) {
				self::$instance = new Church_Tithe_WP();
				self::$instance->load_textdomain();
				self::$instance->includes();
				self::$instance->transactions_db = new Church_Tithe_WP_Transactions_DB();
				self::$instance->arrangements_db = new Church_Tithe_WP_Arrangements_DB();
				// Create the databases.
				church_tithe_wp()->transactions_db->create_table();
				church_tithe_wp()->arrangements_db->create_table();
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone.
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.6
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			wp_die( esc_textarea( __( 'Cheatin huh?', 'church-tithe-wp' ) ) );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @since 1.6
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			wp_die( esc_textarea( __( 'Cheatin&#8217; huh?', 'church-tithe-wp' ) ) );
		}

		/**
		 * Include required files.
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function includes() {

			/**
			 * Base Database
			 */
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/database-functions/class-church-tithe-wp-db.php';

			/**
			 * Transactions Database and Object
			 */
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/database-functions/class-church-tithe-wp-general-query.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/database-functions/class-church-tithe-wp-transactions-db.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/objects/class-church-tithe-wp-transaction.php';

			/**
			 * Arrangements Database and Object
			 */
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/database-functions/class-church-tithe-wp-arrangements-db.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/objects/class-church-tithe-wp-arrangement.php';

			/**
			 * Misc Functions
			 */
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/misc-functions.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/session-handler.php';

			/**
			 * Email Functions
			 */
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/emails/emails.php';

			/**
			 * Admin Stuff
			 */
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/admin-setup.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/admin-queries.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/endpoints.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/admin/php/health-checks-and-wizard/setup.php';

			/**
			 * Stripe Stuff
			 */
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-connect.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-functions.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-webhooks/stripe-webhooks.php';

			// Stripe API Classes.
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-classes/class-church-tithe-wp-stripe.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-classes/class-church-tithe-wp-stripe-get.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/stripe/stripe-classes/class-church-tithe-wp-stripe-delete.php';

			/**
			 * Frontend Stuff
			 */
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/enqueue-scripts.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/misc-functions.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/frontend-queries.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/frontend/php/endpoints/endpoints.php';

			/**
			 * Shortcodes
			 */
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/shortcodes.php';
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/output-form-functions.php';

			/**
			 * Validation Functions
			 */
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/validation-functions.php';

			/**
			 * Image resizer
			 */
			require CHURCH_TITHE_WP_PLUGIN_DIR . 'includes/misc-functions/resizer.php';

		}

		/**
		 * Loads the plugin language files.
		 *
		 * @since 1.0
		 * @return void
		 */
		public function load_textdomain() {

			// Load the included language files.
			load_plugin_textdomain( 'church-tithe-wp', false, CHURCH_TITHE_WP_PLUGIN_FILE . '/languages/' );

			// Load any custom language files from /wp-content/languages/church-tithe-wp for the current locale.
			$locale = apply_filters( 'plugin_locale', get_locale(), 'church-tithe-wp' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'church-tithe-wp', $locale );
			load_textdomain( 'church-tithe-wp', WP_LANG_DIR . '/church-tithe-wp/' . $mofile );

		}

	}

}

/**
 * Function which returns the Church Tithe WP Singleton
 *
 * @since 1.0
 * @return Church_Tithe_WP
 */
function church_tithe_wp() {
	return Church_Tithe_WP::instance();
}

/**
 * Start Church_Tithe_WP.
 *
 * @since 1.0
 * @return Church_Tithe_WP
 */
function church_tithe_wp_initialize() {
	return church_tithe_wp();
}
add_action( 'plugins_loaded', 'church_tithe_wp_initialize' );
