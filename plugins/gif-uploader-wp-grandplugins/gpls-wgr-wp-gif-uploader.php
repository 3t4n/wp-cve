<?php

namespace GPLSCore\GPLS_PLUGIN_WGR;

/**
 * Plugin Name:  WP GIF Uploader [[GrandPlugins]]
 * Description:  The plugin offers uploading GIF and create sub-sizes without losing animation.
 * Author:       GrandPlugins
 * Author URI:   https://profiles.wordpress.org/grandplugins/
 * Plugin URI:   https://grandplugins.com/product/wp-gif-editor/
 * Domain Path:  /languages
 * Requires PHP: 5.6
 * Text Domain:  wp-gif-editor
 * Std Name:     gpls-wgr-wp-gif-editor
 * Version:      1.0.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use GPLSCore\GPLS_PLUGIN_WGR\Core;
use GPLSCore\GPLS_PLUGIN_WGR\Settings;
use GPLSCore\GPLS_PLUGIN_WGR\GIF_Base;
use GPLSCore\GPLS_PLUGIN_WGR\GIF_Creator;
use GPLSCore\GPLS_PLUGIN_WGR\GIF_Editor;
use GPLSCore\GPLS_PLUGIN_WGR\GIF_Post;

if ( ! class_exists( __NAMESPACE__ . '\GPLS_WGR_WP_GIF_Editor' ) ) :


	/**
	 * Exporter Main Class.
	 */
	class GPLS_WGR_WP_GIF_Editor {

		/**
		 * Single Instance
		 *
		 * @var object
		 */
		private static $instance;

		/**
		 * Plugin Info
		 *
		 * @var array
		 */
		private static $plugin_info;

		/**
		 * Debug Mode Status
		 *
		 * @var bool
		 */
		protected $debug = false;

		/**
		 * Core Object
		 *
		 * @var object
		 */
		private static $core;

		/**
		 * Settings Class Object.
		 *
		 * @var object
		 */
		public $settings;

		/**
		 * Singular init Function.
		 *
		 * @return Object
		 */
		public static function init() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}


		/**
		 * Core Actions Hook.
		 *
		 * @return void
		 */
		public static function core_actions( $action_type ) {
			require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . 'core/bootstrap.php';
			self::$core = new Core( self::$plugin_info );
			if ( 'activated' === $action_type ) {
				self::$core->plugin_activated();
			} elseif ( 'deactivated' === $action_type ) {
				self::$core->plugin_deactivated();
			} elseif ( 'uninstall' === $action_type ) {
				self::$core->plugin_uninstalled();
			}
		}

		/**
		 * Plugin Activated Hook.
		 *
		 * @return void
		 */
		public static function plugin_activated() {
			self::setup_plugin_info();
			if ( is_plugin_active( 'wp-gif-editor/gpls-wgr-wp-gif-editor.php' ) ) {
				deactivate_plugins( 'wp-gif-editor/gpls-wgr-wp-gif-editor.php' );
			}

			self::core_actions( 'activated' );
			register_uninstall_hook( __FILE__, array( __NAMESPACE__ . '\GPLS_WGR_WP_GIF_Editor', 'plugin_uninstalled' ) );
		}

		/**
		 * Plugin Deactivated Hook.
		 *
		 * @return void
		 */
		public static function plugin_deactivated() {
			self::setup_plugin_info();
			self::core_actions( 'deactivated' );
			GIF_Base::deactivated();

		}

		/**
		 * Plugin Installed hook.
		 *
		 * @return void
		 */
		public static function plugin_uninstalled() {
			self::setup_plugin_info();
			self::core_actions( 'uninstall' );
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			self::setup_plugin_info();
			$this->load_languages();
			$this->includes();

			self::disable_duplicate();

			self::$core     = new Core( self::$plugin_info );
			$this->settings = new Settings( self::$core, self::$plugin_info );

			GIF_Base::init( self::$plugin_info );
			GIF_Creator::init( self::$plugin_info );
			GIF_Editor::init( self::$plugin_info, self::$core );
			GIF_Post::init( self::$plugin_info, self::$core );
		}

		/**
		 * Disable Duplicate Free/Pro.
		 *
		 * @return void
		 */
		private static function disable_duplicate() {
			if ( ! empty( self::$plugin_info['duplicate_base'] ) && self::is_plugin_active( self::$plugin_info['duplicate_base'] ) ) {
				deactivate_plugins( 'gif-uploader-wp-grandplugins/gpls-wgr-wp-gif-uploader.php' );
			}
		}

		/**
		 * Is Plugin Active.
		 *
		 * @param string $plugin_basename
		 * @return boolean
		 */
		private static function is_plugin_active( $plugin_basename ) {
			require_once \ABSPATH . 'wp-admin/includes/plugin.php';
			return is_plugin_active( $plugin_basename );
		}

		/**
		 * Includes Files
		 *
		 * @return void
		 */
		public function includes() {
			require_once ABSPATH . WPINC . '/class-wp-image-editor.php';
			require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . 'core/bootstrap.php';
		}

		/**
		 * Load languages Folder.
		 *
		 * @return void
		 */
		public function load_languages() {
			load_plugin_textdomain( self::$plugin_info['text_domain'], false, self::$plugin_info['path'] . 'languages/' );
		}

		/**
		 * Set Plugin Info
		 *
		 * @return array
		 */
		public static function setup_plugin_info() {
			$plugin_data = get_file_data(
				__FILE__,
				array(
					'Version'     => 'Version',
					'Name'        => 'Plugin Name',
					'URI'         => 'Plugin URI',
					'SName'       => 'Std Name',
					'text_domain' => 'Text Domain',
				),
				false
			);

			self::$plugin_info = array(
				'id'             => 802,
				'basename'       => plugin_basename( __FILE__ ),
				'version'        => $plugin_data['Version'],
				'name'           => $plugin_data['SName'],
				'text_domain'    => $plugin_data['text_domain'],
				'file'           => __FILE__,
				'plugin_url'     => $plugin_data['URI'],
				'public_name'    => $plugin_data['Name'],
				'path'           => trailingslashit( plugin_dir_path( __FILE__ ) ),
				'url'            => trailingslashit( plugin_dir_url( __FILE__ ) ),
				'options_page'   => $plugin_data['SName'] . '-settings-tab',
				'localize_var'   => str_replace( '-', '_', $plugin_data['SName'] ) . '_localize_data',
				'type'           => 'pro',
				'general_prefix' => 'gpls-plugins-general-prefix',
				'classes_prefix' => 'gpls-wgr',
				'review_link'    => 'https://wordpress.org/plugins/gif-uploader-wp-grandplugins/#reviews',
				'pro_link'       => 'https://grandplugins.com/product/wp-gif-editor/?utm_source=free',
				'duplicate_base' => 'wp-gif-editor/gpls-wgr-wp-gif-editor.php',
			);
		}

		/**
		 * Define Constants
		 *
		 * @param string $key
		 * @param string $value
		 * @return void
		 */
		public function define( $key, $value ) {
			if ( ! defined( $key ) ) {
				define( $key, $value );
			}
		}

	}

	add_action( 'plugins_loaded', array( __NAMESPACE__ . '\GPLS_WGR_WP_GIF_Editor', 'init' ), 10 );
	register_activation_hook( __FILE__, array( __NAMESPACE__ . '\GPLS_WGR_WP_GIF_Editor', 'plugin_activated' ) );
	register_deactivation_hook( __FILE__, array( __NAMESPACE__ . '\GPLS_WGR_WP_GIF_Editor', 'plugin_deactivated' ) );

endif;
