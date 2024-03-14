<?php
/**
 * Plugin Name: Ninja Beaver Lite Addons for Beaver Builder
 * Plugin URI: https://www.ninjabeaveraddon.com
 * Description: A set of custom, improvement, impressive lite modules for Beaver Builder.
 * Version: 2.4.5
 * Author: Ninja Team
 * Author URI: https://www.ninjabeaveraddon.com
 * Copyright: (c) 2019-2020 Ninja Beaver Lite Addons
 * Text Domain: bb-njba
 */

if ( ! defined( 'ABSPATH' ) ) { // Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'BB_NJBA' ) && class_exists( 'FLBuilder' ) ) {
	class BB_NJBA {
		/**
		 * Primary class constructor.
		 */
		public function __construct() {
			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$basename = 'ninja-beaver-lite-addons-for-beaver-builder/bb-njba-lite.php';
			$this->njbaDefineConstants();
			add_action( 'init', [ $this, 'njbaLoadModules' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'njbaLoadScriptsGloballyBasedOnSettings' ] );
			add_filter( 'body_class', [ $this, 'njbaBodyClasses' ] );
			register_deactivation_hook( __FILE__, [ $this, 'njbaLiteVersionDeactivation' ] );
			register_activation_hook( __FILE__, [ $this, 'njbaLiteVersionActivation' ] );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'njbaRenderPluginActionLinks' ] );
		}

		/**
		 *Plugin Action Links
		 *
		 * @param $links_array
		 *
		 * @return mixed
		 */
		function njbaRenderPluginActionLinks( $links ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=njba-admin-setting' ) . '">' . __( 'Settings' ) . '</a>';

			return $links;
		}

		/**
		 *njba plugin Deactivation hook
		 * @since 1.0.0
		 */
		public function njbaLiteVersionDeactivation() { //if(!function_exists("njbaLiteVersionDeactivation")) {
			update_option( 'njba_lite_version_versions', '' );
		} //}

		/**
		 *njba plugin activation hook
		 * @since 1.0.0
		 */
		public function njbaLiteVersionActivation() {
			add_option( 'njba_lite_version_versions', '2.4.4' );
			add_option( 'njba_extensions_lists', '' );
			update_option( 'njba_lite_version_versions', '2.4.4' );
		}

		/**
		 * Define constants.
		 *
		 * @return void
		 * @since 1.1.0
		 */
		private function njbaDefineConstants() {
			$njba_cat = esc_html__( 'NJBA Module', 'bb-njba' );
			$versions = '2.4.4';
			if ( ! defined( 'NJBA_MODULE_DIR' ) ) {
				define( 'NJBA_MODULE_DIR', plugin_dir_path( __FILE__ ) );
			}
			if ( ! defined( 'NJBA_MODULE_URL' ) ) {
				define( 'NJBA_MODULE_URL', plugins_url( '/', __FILE__ ) );
			}
			if ( ! defined( 'NJBA_MODULE_CAT' ) ) {
				define( 'NJBA_MODULE_CAT', $njba_cat );
			}
			if ( ! defined( 'NJBA_MODULE_VERSION' ) ) {
				define( 'NJBA_MODULE_VERSION', $versions );
			}
			if ( ! defined( 'NJBA__MODULE_PLUGIN_FILE' ) ) {
				define( 'NJBA__MODULE_PLUGIN_FILE', __FILE__ );
			}
			if ( ! defined( 'NINJA_BEAVER_LITE' ) ) {
				define( 'NINJA_BEAVER_LITE', 'Ninja Beaver Lite Addons' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
			}
		}

		/**
		 * njba load modules
		 * @since 1.1.0
		 */
		public function njbaLoadModules() {
			if ( class_exists( 'FLBuilder' ) ) {
				$njba_options = get_option( 'njba_options' );
				add_option( 'njba_usage_enabled', '1' );
				if ( $njba_options == '' ) {
					$njba_admin_option_data = array(
						'google_static_map_api_key' => '',
						'facebook_app_id'           => '',
					);
					$njba_admin_options     = add_option( 'njba_options', $njba_admin_option_data );
				}
				require_once 'includes/helper-functions.php'; // modules categories define
				require_once 'classes/class-admin-settings.php'; // admin settings
				//require_once 'classes/class-njba-usage.php'; //Usage
				require_once 'classes/class-module-fields.php'; //class fields
				require_once 'includes/modules.php'; //include modules
				require_once 'classes/class-wpml-compatibility.php'; //WPML 
			}
		}

		/**
		 * Load language files.
		 *
		 * @since 1.1.4
		 * @return void
		 */

		public function load_textdomain() {
			load_plugin_textdomain( 'bb-njba', false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * njba plugin text domain define
		 * @return bool
		 */
		/*public function load_plugin_textdomain() {
			if ( function_exists( 'get_user_locale' ) ) {
				$locale = apply_filters( 'plugin_locale', get_user_locale(), 'bb-njba' );
			} else {
				$locale = apply_filters( 'plugin_locale', get_locale(), 'bb-njba' );
			}
			$mobile_global = trailingslashit( WP_LANG_DIR ) . 'plugins/bb-plugin/' . $locale . '.mo';
			$mobile_local  = trailingslashit( NJBA_MODULE_DIR ) . 'languages/' . $locale . '.mo';
			if ( file_exists( $mobile_global ) ) {
				return load_textdomain( 'bb-njba', $mobile_global ); //Look in global /wp-content/languages/plugins/bb-plugin/ folder
			}

			if ( file_exists( $mobile_local ) ) {
				return load_textdomain( 'bb-njba', $mobile_local ); //Look in local /wp-content/plugins/bb-plugin/languages/ folder
			}

			return false; //Nothing found
		}*/

		/**
		 * Ninja modules Scripts
		 * @since 1.0.0
		 */
		public function njbaLoadScriptsGloballyBasedOnSettings() {
			if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
				wp_enqueue_style( 'njba-fields-style', NJBA_MODULE_URL . 'assets/css/njba-fields.css', array(), rand() );
				wp_enqueue_script( 'njba-fields-script', NJBA_MODULE_URL . 'assets/js/fields.js', array( 'jquery' ), rand(), true );
			}
			wp_register_script( 'njba-twitter-widgets', NJBA_MODULE_URL . 'assets/js/twitter-widgets.js', array( 'jquery' ), rand(), true );
		}

		/**
		 * njba main Body Class
		 *
		 * @param $classes
		 *
		 * @return array
		 * @since 1.0.0
		 */
		public function njbaBodyClasses( $classes ) {
			$classes[] = 'bb-njba';

			return $classes;
		}
	}

	new BB_NJBA();

} elseif ( ! class_exists( 'FLBuilder' ) ) { // Display admin notice for activating beaver builder
	add_action( 'admin_notices', 'njba_admin_notices' );
	add_action( 'network_admin_notices', 'njba_admin_notices' );
	function njba_admin_notices() {
		$url = admin_url( 'plugins.php' );
		echo '<div class="notice notice-error"><p>';
		echo sprintf( __( 'Please install and activate Beaver Builder Lite or Beaver Builder Pro / Agency to use Ninja Beaver Lite add-on and after continuing.',
			'bb-njba' ), $url );
		echo '</p></div>';
		$lite_dirname   = 'ninja-beaver-lite-addons-for-beaver-builder';
		$lite_active    = is_plugin_active( $lite_dirname . '/bb-njba-lite.php' );
		$plugin_dirname = basename( dirname( __DIR__ ) );
		if ( $lite_active && $plugin_dirname !== $lite_dirname ) {
			deactivate_plugins( array( $lite_dirname . '/bb-njba-lite.php' ), false, is_network_admin() );

			return;
		}
	}
} else { // Display admin notice for activating beaver builder
	add_action( 'admin_notices', 'njba_admin_notices' );
	add_action( 'network_admin_notices', 'njba_admin_notices' );
	function njba_admin_notices() {
		$url = admin_url( 'plugins.php' );
		echo '<div class="notice notice-error"><p>';
		echo sprintf( __( "You currently have two versions of <strong> Ninja Beaver Lite Addon for Beaver Builder</strong> active on this site. Please <a href='%s'>deactivate one</a> before continuing.",
			'bb-njba' ), $url );
		echo '</p></div>';
	}
}
