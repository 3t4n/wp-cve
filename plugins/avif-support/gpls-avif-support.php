<?php
namespace GPLSCore\GPLS_PLUGIN_AVFSTW;

/**
 * Plugin Name:     AVIF Support [GrandPlugins]
 * Description:     AVIF support plugin aims to support avif images in WordPress by overcome wp issues and limits regarding uploading, displaying and generating avif images.
 * Author:          GrandPlugins
 * Author URI:      https://grandplugins.com
 * Text Domain:     gpls-avif-support
 * Std Name:        gpls-avfstw-avif-support
 * Version:         1.0.6
 * Requires PHP:    7.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use GPLSCore\GPLS_PLUGIN_AVFSTW\Core\Core;
use GPLSCore\GPLS_PLUGIN_AVFSTW\Base;
use GPLSCore\GPLS_PLUGIN_AVFSTW\Plugin;

if ( ! class_exists( __NAMESPACE__ . '\GPLS_AVFSTW_Class' ) ) :

	/**
	 * Main Class.
	 */
	class GPLS_AVFSTW_Class {

		/**
		 * Single Instance
		 *
		 * @var self
		 */
		private static $instance = null;

		/**
		 * Plugin Info
		 *
		 * @var array
		 */
		private static $plugin_info;

		/**
		 * Core Object
		 *
		 * @return Core
		 */
		private static $core;

		/**
		 * Singular init Function.
		 *
		 * @return Object
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Check for Required Plugins before Activate.
		 *
		 * @return void
		 */
		private static function required_plugins_check_activate() {
			if ( empty( self::$plugin_info['required_plugins'] ) ) {
				return;
			}
			foreach ( self::$plugin_info['required_plugins'] as $plugin_basename => $plugin_details ) {
				if ( ! is_plugin_active( $plugin_basename ) ) {
					deactivate_plugins( self::$plugin_info['basename'] );
					wp_die( sprintf( esc_html__( '%1$s ( %2$s ) plugin is required in order to activate the plugin' ), $plugin_details['title'], $plugin_basename ) );
				}
			}
		}

		/**
		 * Check for Required Plugins before Load.
		 *
		 * @return void
		 */
		private static function required_plugins_check_load() {
			if ( empty( self::$plugin_info['required_plugins'] ) ) {
				return;
			}
			foreach ( self::$plugin_info['required_plugins'] as $plugin_basename => $plugin_details ) {
				if ( ! class_exists( $plugin_details['class_check'] ) ) {
					require_once \ABSPATH . 'wp-admin/includes/plugin.php';
					deactivate_plugins( self::$plugin_info['basename'] );
					return;
				}
			}
		}

		/**
		 * Disable Duplicate Free/Pro.
		 *
		 * @return void
		 */
		private static function disable_duplicate() {
			if ( ! empty( self::$plugin_info['duplicate_base'] ) && is_plugin_active( self::$plugin_info['duplicate_base'] ) ) {
				deactivate_plugins( self::$plugin_info['duplicate_base'] );
			}
		}

		/**
		 * Plugin Activated Hook.
		 *
		 * @return void
		 */
		public static function plugin_activated() {
			self::setup_plugin_info();
			self::required_plugins_check_activate();
			self::disable_duplicate();
			self::includes();
			self::start();
			register_uninstall_hook( __FILE__, array( __NAMESPACE__ . '\GPLS_AVFSTW_Class', 'plugin_uninstalled' ) );
			Plugin::activated();
		}

		/**
		 * Base Start.
		 *
		 * @return void
		 */
		private static function start() {
			self::$core = Core::start( self::$plugin_info );
			Base::start( self::$core, self::$plugin_info );
		}

		/**
		 * Plugin Deactivated Hook.
		 *
		 * @return void
		 */
		public static function plugin_deactivated() {
			self::setup_plugin_info();
			self::$core->core_actions( 'deactivated' );
			Plugin::deactivated();
		}

		/**
		 * Plugin Installed hook.
		 *
		 * @return void
		 */
		public static function plugin_uninstalled() {
			self::setup_plugin_info();
			self::includes();
			self::start();
			self::$core->core_actions( 'deactivated' );
			Plugin::uninstalled();
		}
		/**
		 * Constructor
		 */
		private function __construct() {
			self::setup_plugin_info();
			$this->load_languages();
			self::includes();
			$this->main_load();
		}

		/**
		 * Includes Files
		 *
		 * @return void
		 */
		public static function includes() {
			require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . 'vendor/autoload.php';
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
		 * Main Load.
		 *
		 * @return void
		 */
		public function main_load() {
			self::required_plugins_check_load();
			self::start();
			$this->load();
		}

		/**
		 * Load CLasses.
		 *
		 * @return void
		 */
		public function load() {
			Plugin::load();
		}

		/**
		 * Set Plugin Info
		 *
		 * @return void
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
				'id'              => 2037,
				'basename'        => plugin_basename( __FILE__ ),
				'version'         => $plugin_data['Version'],
				'name'            => $plugin_data['SName'],
				'text_domain'     => $plugin_data['text_domain'],
				'file'            => __FILE__,
				'plugin_url'      => $plugin_data['URI'],
				'public_name'     => $plugin_data['Name'],
				'path'            => trailingslashit( plugin_dir_path( __FILE__ ) ),
				'url'             => trailingslashit( plugin_dir_url( __FILE__ ) ),
				'options_page'    => $plugin_data['SName'],
				'templates_path'  => trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/Templates/',
				'localize_var'    => str_replace( '-', '_', $plugin_data['SName'] ) . '_localize_data',
				'type'            => 'free',
				'prefix'          => 'gpls-avfstw',
				'classes_prefix'  => 'gpls-avfstw',
				'classes_general' => 'gpls-general',
				'review_link'     => 'https://grandplugins.com/product/avif-support/?review=true#reviews',
			);
		}

	}

	add_action( 'plugins_loaded', array( __NAMESPACE__ . '\GPLS_AVFSTW_Class', 'init' ), 10 );
	register_activation_hook( __FILE__, array( __NAMESPACE__ . '\GPLS_AVFSTW_Class', 'plugin_activated' ) );
	register_deactivation_hook( __FILE__, array( __NAMESPACE__ . '\GPLS_AVFSTW_Class', 'plugin_deactivated' ) );
endif;
