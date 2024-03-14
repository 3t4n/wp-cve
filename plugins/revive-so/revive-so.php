<?php

/**
 * Plugin Name: Revive.so
 * Description: Revive.so is the ultimate WordPress plugin for content rejuvenation. Republish and recirculate evergreen posts with a simple click. This will boost your content's visibility, engagement, and SEO rankings. Don't let your valuable content fade into obscurity.
 * Version: 1.0.5
 * Author: Revive.so
 * Author URI: https://revive.so/
 * Requires at least: 5.4
 * Tested up to: 6.4
 * Text Domain: revive-so
 *
 * License: GPL v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// If this file is called directly, abort!!!
defined( 'ABSPATH' ) || exit;

/**
 * Check if Reviveso class is already exists.
 *
 * @class Main class of the plugin.
 */
if ( ! class_exists( 'Reviveso' ) ) {

	/**
	 * Reviveso class.
	 *
	 * @class Main class of the plugin.
	 */
	final class Reviveso {


		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		public $version = '1.0.5';

		/**
		 * Minimum version of WordPress required to run Reviveso.
		 *
		 * @var string
		 */
		private $wordpress_version = '5.4';

		/**
		 * Minimum version of PHP required to run Reviveso.
		 *
		 * @var string
		 */
		private $php_version = '7.3';

		/**
		 * Hold install error messages.
		 *
		 * @var bool
		 */
		private $messages = array();

		/**
		 * The single instance of the class.
		 *
		 * @var Reviveso
		 */
		protected static $instance = null;

		/**
		 * Retrieve main Reviveso instance.
		 *
		 * Ensure only one instance is loaded or can be loaded.
		 *
		 * @see reviveso()
		 * @return Reviveso
		 */
		public static function get() {
			if ( is_null( self::$instance ) && ! ( self::$instance instanceof Reviveso ) ) {
				self::$instance = new Reviveso();
				self::$instance->setup();
			}

			return self::$instance;
		}

		/**
		 * Instantiate the plugin.
		 */
		private function setup() {
			// Define plugin constants.
			$this->define_constants();

			if ( ! $this->is_requirements_meet() ) {
				return;
			}

			// Include required files.
			$this->includes();

			// Instantiate services.
			$this->instantiate();

			// Loaded action.
			do_action( 'reviveso_plugin_loaded' );
		}

		/**
		 * Check that the WordPress and PHP setup meets the plugin requirements.
		 *
		 * @return bool
		 */
		private function is_requirements_meet() {

			// Check WordPress version.
			if ( version_compare( get_bloginfo( 'version' ), $this->wordpress_version, '<' ) ) {
				/* translators: WordPress Version */
				$this->messages[] = sprintf( esc_html__( 'You are using the outdated WordPress, please update it to version %s or higher.', 'revive-so' ), $this->wordpress_version );
			}

			// Check PHP version.
			if ( version_compare( phpversion(), $this->php_version, '<' ) ) {
				/* translators: PHP Version */
				$this->messages[] = sprintf( esc_html__( 'Reviveso requires PHP version %s or above. Please update PHP to run this plugin.', 'revive-so' ), $this->php_version );
			}

			if ( empty( $this->messages ) ) {
				return true;
			}

			// Auto-deactivate plugin.
			add_action( 'admin_init', array( $this, 'auto_deactivate' ) );
			add_action( 'admin_notices', array( $this, 'activation_error' ), 8 );

			return false;
		}

		/**
		 * Auto-deactivate plugin if requirements are not met, and display a notice.
		 */
		public function auto_deactivate() {
			deactivate_plugins( REVIVESO_BASENAME );
			if ( isset( $_GET['activate'] ) ) { // phpcs:ignore
				unset( $_GET['activate'] ); // phpcs:ignore
			}
		}

		/**
		 * Error notice on plugin activation.
		 */
		public function activation_error() {
			?>
			<div class="notice reviveso-notice notice-error">
				<p>
					<?php echo wp_kses_post( join( '<br>', $this->messages ) ); ?>
				</p>
			</div>
			<?php
		}

		/**
		 * Define the plugin constants.
		 */
		private function define_constants() {
			define( 'REVIVESO_VERSION', $this->version );
			define( 'REVIVESO_FILE', __FILE__ );
			define( 'REVIVESO_PATH', dirname( REVIVESO_FILE ) . '/' );
			define( 'REVIVESO_URL', plugins_url( '', REVIVESO_FILE ) . '/' );
			define( 'REVIVESO_BASENAME', plugin_basename( REVIVESO_FILE ) );
			defined( 'REVIVE_STORE_URL' ) || define( 'REVIVE_STORE_URL', 'https://revive.so/' );
			defined( 'REVIVE_STORE_UPGRADE_URL' ) || define( 'REVIVE_STORE_UPGRADE_URL', 'https://revive.so/pricing' );
		}

		/**
		 * Include the required files.
		 */
		private function includes() {
			include __DIR__ . '/vendor/autoload.php';
		}

		/**
		 * Instantiate services.
		 */
		private function instantiate() {
			// Activation hook.
			register_activation_hook( REVIVESO_FILE, 
				function () {
					REVIVESO_Activate::activate();
				} 
			);

			// Deactivation hook.
			register_deactivation_hook( REVIVESO_FILE, 
				function () {
					REVIVESO_Deactivate::deactivate();
				} 
			);

			// Init Reviveso Classes.
			add_action( 'init', array( 'REVIVESO_Loader', 'register_services'), 30);
		}
	}
}

/**
 * Returns the main instance of Reviveso to prevent the need to use globals.
 *
 * @return Reviveso
 */
if ( ! function_exists( 'revive_so_run_plugin' ) ) {
	function revive_so_run_plugin() {
		return Reviveso::get();
	}
}

// Start it.
revive_so_run_plugin();
