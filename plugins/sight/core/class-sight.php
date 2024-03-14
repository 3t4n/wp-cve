<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package Sight
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Sight_Portfolio' ) ) {
	/**
	 * Main Core Class
	 */
	class Sight_Portfolio {
		/**
		 * The plugin version number.
		 *
		 * @var string $data The plugin version number.
		 */
		public $version;

		/**
		 * The plugin data array.
		 *
		 * @var array $data The plugin data array.
		 */
		public $data = array();

		/**
		 * The plugin settings array.
		 *
		 * @var array $settings The plugin data array.
		 */
		public $settings = array();

		/**
		 * INIT (global theme queue)
		 */
		public function init() {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			// Get plugin data.
			$plugin_data = get_plugin_data( SIGHT_PATH . 'sight.php' );

			// Vars.
			$this->version = $plugin_data['Version'];

			// Settings.
			$this->settings = array(
				'name'    => esc_html__( 'Sight', 'sight' ),
				'version' => $plugin_data['Version'],
			);

			// Include core.
			require_once SIGHT_PATH . 'core/core-api.php';
			require_once SIGHT_PATH . 'core/core-plugin-functions.php';
			require_once SIGHT_PATH . 'core/core-register-post-types.php';
			require_once SIGHT_PATH . 'core/core-register-category-fields.php';
			require_once SIGHT_PATH . 'core/core-video-settings.php';

			// Include core classes.
			require_once SIGHT_PATH . 'core/block-renderer-controller.php';
			require_once SIGHT_PATH . 'core/block-utils-is-field-visible.php';

			// Render files.
			require_once SIGHT_PATH . 'render/sight-entry.php';
			require_once SIGHT_PATH . 'render/sight-load-more.php';

			// Elementor integration.
			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				require_once SIGHT_PATH . 'elementor/integration.php';
			}

			// Gutenberg integration.
			if ( function_exists( 'register_block_type' ) ) {
				require_once SIGHT_PATH . 'gutenberg/block-portfolio.php';
			}

			// Render.
			require_once SIGHT_PATH . 'render/sight-render.php';

			// Actions.
			add_action( 'sight_plugin_activation', array( $this, 'activation' ) );
			add_action( 'plugins_loaded', array( $this, 'check_version' ) );
			add_action( 'init', array( $this, 'flush_rewrite_rules' ), 999 );
		}

		/**
		 * This function will safely define a constant
		 *
		 * @param string $name  The name.
		 * @param mixed  $value The value.
		 */
		public function define( $name, $value = true ) {

			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Returns true if has setting.
		 *
		 * @param string $name The name.
		 */
		public function has_setting( $name ) {
			return isset( $this->settings[ $name ] );
		}

		/**
		 * Returns a setting.
		 *
		 * @param string $name The name.
		 */
		public function get_setting( $name ) {
			return isset( $this->settings[ $name ] ) ? $this->settings[ $name ] : null;
		}

		/**
		 * Updates a setting.
		 *
		 * @param string $name  The name.
		 * @param mixed  $value The value.
		 */
		public function update_setting( $name, $value ) {
			$this->settings[ $name ] = $value;
			return true;
		}

		/**
		 * Returns data.
		 *
		 * @param string $name The name.
		 */
		public function get_data( $name ) {
			return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
		}

		/**
		 * Sets data.
		 *
		 * @param string $name  The name.
		 * @param mixed  $value The value.
		 */
		public function set_data( $name, $value ) {
			$this->data[ $name ] = $value;
		}

		/**
		 * Hook activation
		 */
		public function activation() {
			update_option( 'sight_db_activation', 1, true );

			if ( get_option( 'sight_db_version' ) ) {
				return;
			}

			update_option( 'sight_db_version', sight_raw_setting( 'version' ), true );
		}

		/**
		 * Removes rewrite rules and then recreate rewrite rules.
		 */
		public function flush_rewrite_rules() {
			if ( get_option( 'sight_db_activation' ) ) {
				flush_rewrite_rules();

				delete_option( 'sight_db_activation' );
			}
		}

		/**
		 * Check current version
		 */
		public function check_version() {

			// Version Data.
			$new = sight_raw_setting( 'version' );

			// Get db version.
			$current = get_option( 'sight_db_version', $new );

			// If versions don't match.
			if ( $current && $current !== $new ) {
				/**
				 * If different versions call a special hook.
				 *
				 * @param string $current Current version.
				 * @param string $new     New version.
				 */
				do_action( 'sight_plugin_upgrade', $current, $new );

				update_option( 'sight_db_version', $new, true );
			}

			if ( $current ) {
				update_option( 'sight_db_version', $new, true );
			}
		}
	}

	/**
	 * The main function responsible for returning the one true sight Instance to functions everywhere.
	 * Use this function like you would a global variable, except without needing to declare the global.
	 *
	 * Example: <?php $sight = sight_portfolio(); ?>
	 */
	function sight_portfolio() {

		// Globals.
		global $sight_instance;

		// Init.
		if ( ! isset( $sight_instance ) ) {
			$sight_instance = new Sight_Portfolio();
			$sight_instance->init();
		}

		return $sight_instance;
	}

	// Initialize.
	sight_portfolio();
}
