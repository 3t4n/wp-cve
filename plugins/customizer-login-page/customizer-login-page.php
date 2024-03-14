<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Plugin Name: Customizer Login Page
 * Description: customizer Login Page For WordPress.
 * Version: 2.0.4
 * Author: A WP Life
 * Author URI: https://awplife.com/
 * License: GPLv2 or later
 * Text Domain: customizer-login-page
 * Domain Path: /languages
 */

$clp_build_package              = get_option( 'clp_build_package' );
$customizer_login_page_settings = get_option( 'customizer_login_page_settings' );

if ( $clp_build_package === false ) {
	if ( $customizer_login_page_settings !== false ) {
		// If 'clp_build_package' doesn't exist but 'customizer_login_page_settings' does exist.
		add_option( 'clp_build_package', 'oldclp' );
	} else {
		// If neither 'clp_build_package' nor 'customizer_login_page_settings' exist.
		add_option( 'clp_build_package', 'newlpc' );
	}
}

/** Function to load different code based on the option value */
function clp_load_based_on_option() {
	// Retrieve the option value.
	$clp_build = get_option( 'clp_build_package' );

	// Check the option value and load corresponding code.
	if ( $clp_build === 'newlpc' ) {
		require_once 'login-page-customizer/login-page-customizer.php';
	} elseif ( $clp_build === 'oldclp' ) {
		oldclp_fire_function();
	}
}
clp_load_based_on_option();

function oldclp_fire_function() {
	// Default settings
	register_activation_hook( __FILE__, 'customizer_login_page_defaultsettings' );
	function customizer_login_page_defaultsettings() {

		$customizer_login_page_settings = get_option( 'customizer_login_page_settings' );
		add_option( 'customizer_login_page_settings', $_POST );

	}

	// class
	if ( ! class_exists( 'AWP_Customizer_Login_Settings' ) ) {

		class AWP_Customizer_Login_Settings {

			public function __construct() {
				$this->_constants();
				$this->includes();
				$this->_hooks();
			}

			protected function _constants() {
				// Plugin Version
				define( 'AWP_CLP_VER', '2.0.4' );

				// Plugin Text Domain
				define( 'AWP_CPL_TXTDM', 'customizer-login-page' );

				// Plugin Name
				define( 'AWP_CLP_PLUGIN_NAME', __( 'Customizer Login', AWP_CPL_TXTDM ) );

				// Plugin Slug
				define( 'AWP_CLP_PLUGIN_SLUG', 'awp_customizer_login' );

				// Plugin Directory Path
				define( 'AWP_CLP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

				// Plugin Directory URL
				define( 'AWP_CLP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

				// Root File
				define( 'AWP_CLP_PLUGIN_ROOT_FILE', ( __FILE__ ) );

			} // end of constructor function

			/**
			 * Include required core files used in admin and on the frontend.
			 */
			public function includes() {

			}

			protected function _hooks() {

				// Load text domain
				add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

				// add menu item, change menu for multisite
				add_action( 'admin_menu', array( $this, 'awp_customizer_login_menu' ), 101 );

						 add_action( 'customize_preview_init', array( $this, 'awp_customizer_previewer_js' ) );

			} // end of hook function

			function awp_customizer_previewer_js() {

			}

			public function load_textdomain() {
				load_plugin_textdomain( AWP_CPL_TXTDM, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
			}

			public function awp_customizer_login_menu() {
				$customizer_settings_menu = add_menu_page( __( 'Customizer Login', AWP_CPL_TXTDM ), __( 'Customizer Login', AWP_CPL_TXTDM ), 'administrator', 'customizer-login-settings-page', array( $this, 'awp_customizer_login_page' ) );
			}

			public function awp_customizer_login_page() {
				require_once 'customizer-setting-page.php';
			}

		}

		// Plugin Recommend
		add_action( 'tgmpa_register', 'AWP_CPL_TXTDM_plugin_recommend' );
		function AWP_CPL_TXTDM_plugin_recommend() {
			$plugins = array(
				array(
					'name'     => 'Event Manager',
					'slug'     => 'event-monster',
					'required' => false,
				),
				array(
					'name'     => 'Modal Popup Box ',
					'slug'     => 'modal-popup-box',
					'required' => false,
				),
				array(
					'name'     => 'Pricing Table',
					'slug'     => 'abc-pricing-table',
					'required' => false,
				),
			);
			tgmpa( $plugins );
		}

		$awp_customizer_login_object = new AWP_Customizer_Login_Settings();
		// Shortcode page
		require_once 'customizer-option-panel-settings.php';
		require_once 'class-tgm-plugin-activation.php';

		new Customizer_Login_Entities();
	}
}
