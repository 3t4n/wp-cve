<?php
/**
 * Plugin Name:       HootKit
 * Description:       HootKit is a great companion plugin for WordPress themes by wpHoot.
 * Version:           2.0.13
 * Requires at least: 5.0
 * Requires PHP:      5.6
 * Author:            wphoot
 * Author URI:        https://wphoot.com/
 * License:           GPLv3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain:       hootkit
 * Domain Path:       /languages
 * @package           Hootkit
 */

use \HootKit\Inc\Helper_Config;
use \HootKit\Inc\Helper_Strings;
use \HootKit\Inc\Helper_Mods;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Run in Debug mode to load unminified CSS and JS, and add other developer data to code.
 */
if ( !defined( 'HKIT_DEBUG' ) && defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG )
	define( 'HKIT_DEBUG', true );

/**
 * The core plugin class.
 *
 * @since   1.0.0
 * @package Hootkit
 */
if ( ! class_exists( 'HootKit' ) ) :

	class HootKit {

		/**
		 * Plugin Info
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public $version;
		public $name;
		public $slug;
		public $file;
		public $dir;
		public $uri;
		public $plugin_basename;

		/**
		 * Constructor method.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Plugin Info
			$this->version         = '2.0.13';
			$this->name            = 'HootKit';
			$this->slug            = 'hootkit';
			$this->file            = __FILE__;
			$this->dir             = trailingslashit( plugin_dir_path( __FILE__ ) );
			$this->uri             = trailingslashit( plugin_dir_url( __FILE__ ) );
			$this->plugin_basename = plugin_basename(__FILE__);

			// Load Plugin Files and Helpers
			$this->loader();

			// Plugin Loader - Load config, and modules based on config
			// -> Register HootKit configuration after theme has loaded (so that theme can hook in to alter Hootkit config)
			// -> init hook may be a bit late for us to load since 'widgets_init' is used to intialize widgets (unless we hook into init at 0, which is a bit messy)
			add_action( 'after_setup_theme', array( $this, 'loadhootkit' ), 96 );

		}

		/**
		 * Load Plugin Files and Helpers
		 *
		 * @since  2.0.0
		 * @access public
		 * @return void
		 */
		public function loader() {

			require_once( $this->dir . 'include/class-activation.php' );
			require_once( $this->dir . 'include/class-config.php' );
			require_once( $this->dir . 'include/class-helper-strings.php' );
			require_once( $this->dir . 'include/class-helper-mods.php' );
			require_once( $this->dir . 'include/class-helper-assets.php' );

		}

		/**
		 * Plugin Loader
		 * Load plugin and modules
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function loadhootkit() {

			// Load plugin parts
			$loadhootkit = array();
			$thememods = hootkit()->get_config( 'modules' );
			foreach ( array( 'widget', 'block', 'misc' ) as $check )
				if ( !empty( $thememods[ $check ] ) )
					$loadhootkit[ $check ] = true;

			if ( !empty( $loadhootkit ) ) {

				// Load Limited Core/Helper Functions
				// Template Functions - may be required in admin for creating live preview eg. so page builder
				require_once( $this->dir . 'include/template-functions.php' );

				// Load Limited Library for Non Hoot themes :: some deprecated theme versions 'may' have nohoot set to true
				if ( $this->get_config( 'nohoot' ) ) {
					require_once( $this->dir . 'include/hoot-library.php' );
					require_once( $this->dir . 'include/hoot-library-icons.php' );
				}

				// Admin Functions
				if ( is_admin() ) {
					require_once( $this->dir . 'admin/functions.php' );
					require_once( $this->dir . 'admin/class-settings.php' );
				}

				// Modules
				if ( !empty( $loadhootkit['widget'] ) )
					require_once( $this->dir . 'widgets/class-widgets.php' );
				if ( !empty( $loadhootkit['misc'] ) )
					require_once( $this->dir . 'misc/class-miscmods.php' );

			}

		}

		/**
		 * Get String values.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string $key
		 * @param  string $default
		 * @return string
		 */
		public function get_string( $key, $default = '' ) {
			$return = '';
			if ( !is_array( Helper_Strings::$strings ) ) {
				$return = '';
			} else {
				$return = ( !empty( Helper_Strings::$strings[ $key ] ) ? Helper_Strings::$strings[ $key ] : '' );
			}
			if ( !empty( $return ) && is_string( $return ) )
				return esc_html( $return );
			elseif ( !empty( $default ) && is_string( $default ) )
				return esc_html( $default );
			else return esc_html( ucwords( str_replace( array( '-', '_' ), ' ' , $key ) ) );
		}

		/**
		 * Get Config values.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string $key    Config value to return / else return entire array
		 * @param  string $subkey Check for $subkey if config value is an array
		 * @return mixed
		 */
		public function get_config( $key = '', $subkey = '', $default = array() ) {

			// Early Check in case config has changed
			// Now redundant since config is loaded within this->loader
			if ( empty( Helper_Config::$config ) )
				return $default;

			// Return the value
			if ( $key && is_string( $key ) ) {
				if ( isset( Helper_Config::$config[ $key ] ) ) {
					if ( $subkey && ( is_string( $subkey ) || is_integer( $subkey ) ) ) {
						return ( isset( Helper_Config::$config[ $key ][ $subkey] ) ) ? Helper_Config::$config[ $key ][ $subkey ] : $default;
					} else {
						return Helper_Config::$config[ $key ];
					}
				} else {
					return $default;
				}
			} else {
				return Helper_Config::$config;
			}

		}

		/**
		 * Get Active Modules from config
		 *
		 * @since  2.0.0
		 */
		public function get_activemods( $type = '' ) {
			if ( $type && is_string( $type ) )
				retrun( ( isset( Helper_Config::$config['activemods'][ $type ] ) ) ? Helper_Config::$config['activemods'][ $type ] : array() );
			else
				return Helper_Config::$config['activemods'];
		}

		/**
		 * Get HootKit modules
		 *
		 * @since  1.2.0
		 * @access public
		 * @param  string $key 'modules' 'supports'
		 * @param  string $subkey Check for $subkey if $key value is an array
		 * @return mixed
		 */
		public function get_mods( $key = '', $subkey = '', $default = array() ) {
			if ( $key && is_string( $key ) ) {
				if ( isset( Helper_Mods::$mods[ $key ] ) ) {
					if ( $subkey && ( is_string( $subkey ) || is_integer( $subkey ) ) ) {
						return ( isset( Helper_Mods::$mods[ $key ][ $subkey] ) ) ? Helper_Mods::$mods[ $key ][ $subkey ] : $default;
					} else {
						return Helper_Mods::$mods[ $key ];
					}
				} else {
					return array();
				}
			} else {
				return Helper_Mods::$mods;
			}
		}

		/**
		 * Fitler and Get HootKit modules of specific type
		 *
		 * @since  2.0.0
		 * @param $type 'widget' 'block' 'misc'
		 * @param $keys boolean
		 */
		public function get_modtype( $type, $keys = false ) {
			static $modtypes = array();
			if ( !isset( $modtypes[ $type ] ) ) {
				$modtypes[ $type ] = array();
				foreach ( Helper_Mods::$mods['modules'] as $slug => $atts )
					if ( isset( $atts['types'] ) && \in_array( $type, $atts['types'] ) )
						$modtypes[ $type ][ $slug ] = $atts;
			}
			return ( ( $keys === false ) ? $modtypes[ $type ] : array_keys( $modtypes[ $type ] ) );
		}

		/**
		 * Returns the instance
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {

			static $instance = null;

			if ( is_null( $instance ) ) {
				$instance = new self;
			}

			return $instance;
		}

	}

	/**
	 * Gets the instance of the `HootKit` class. This function is useful for quickly grabbing data
	 * used throughout the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	function hootkit() {
		return HootKit::get_instance();
	}

	// Lets roll!
	hootkit();

endif;