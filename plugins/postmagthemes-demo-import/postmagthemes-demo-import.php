<?php

/*
Plugin Name: PostmagThemes demo import
Plugin URI: https://wordpress.org/plugins/postmagthemes-demo-import/
Description: Import your content, widgets and theme settings with one click. Theme authors! Enable simple demo import for your theme demo data.
Version: 1.1.0
Author: postmagthemes
Author URI: http://postmagthemes.com
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
Text Domain: pt-pmdi
*/

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Main plugin class with initialization tasks.
 */
class PMDI_Plugin {
	/**
	 * Constructor for this class.
	 */
	public function __construct() {
		/**
		 * Display admin error message if PHP version is older than 5.3.2.
		 * Otherwise execute the main plugin class.
		 */
		if ( version_compare( phpversion(), '5.3.2', '<' ) ) {
			add_action( 'admin_notices', array( $this, 'old_php_admin_error_notice' ) );
		} else {
			// Set plugin constants.
			$this->set_plugin_constants();

			// Composer autoloader.
			require_once PT_PMDI_PATH . 'vendor/autoload.php';

			// Instantiate the main plugin class *Singleton*.
			$pt_one_click_demo_import = PMDI\OneClickDemoImport::get_instance();

			// Register WP CLI commands
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				WP_CLI::add_command( 'pmdi list', array( 'PMDI\WPCLICommands', 'list_predefined' ) );
				WP_CLI::add_command( 'pmdi import', array( 'PMDI\WPCLICommands', 'import' ) );
			}
		}
	}


	/**
	 * Display an admin error notice when PHP is older the version 5.3.2.
	 * Hook it to the 'admin_notices' action.
	 */
	public function old_php_admin_error_notice() {
		$message = sprintf( esc_html__( 'The %2$sPostmagThemes Demo Import%3$s plugin requires %2$sPHP 5.3.2+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.3.2.%4$s Your current version of PHP: %2$s%1$s%3$s', 'pt-pmdi' ), phpversion(), '<strong>', '</strong>', '<br>' );

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}


	/**
	 * Set plugin constants.
	 *
	 * Path/URL to root of this plugin, with trailing slash and plugin version.
	 */
	private function set_plugin_constants() {
		// Path/URL to root of this plugin, with trailing slash.
		if ( ! defined( 'PT_PMDI_PATH' ) ) {
			define( 'PT_PMDI_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'PT_PMDI_URL' ) ) {
			define( 'PT_PMDI_URL', plugin_dir_url( __FILE__ ) );
		}

		// Action hook to set the plugin version constant.
		add_action( 'admin_init', array( $this, 'set_plugin_version_constant' ) );
	}


	/**
	 * Set plugin version constant -> PT_PMDI_VERSION.
	 */
	public function set_plugin_version_constant() {
		if ( ! defined( 'PT_PMDI_VERSION' ) ) {
			$plugin_data = get_plugin_data( __FILE__ );
			define( 'PT_PMDI_VERSION', $plugin_data['Version'] );
		}
	}

	public function createSlug( $str, $delimiter = '-' ) {
		$slug = strtolower( trim( preg_replace( '/[\s-]+/', $delimiter, preg_replace( '/[^A-Za-z0-9-]+/', $delimiter, preg_replace( '/[&]/', 'and', preg_replace( '/[\']/', '', iconv( 'UTF-8', 'ASCII//TRANSLIT', $str ) ) ) ) ), $delimiter ) );
		return $slug;
	}
}

// Instantiate the plugin class.
$pmdi_plugin = new PMDI_Plugin();



if ( is_admin() ) {
	// Load demo.
	require_once PT_PMDI_PATH . 'demo/class-demo.php';
	require_once PT_PMDI_PATH . 'demo/demo.php';
}
