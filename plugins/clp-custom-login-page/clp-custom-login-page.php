<?php
/*
 Plugin Name: 		CLP - Custom Login Page
 Plugin URI: 		https://wordpress.org/plugins/clp-custom-login-page/
 Description:       Best plugin to customize login and Registration page
 Version:           1.5.5
 Author:            NiteoThemes
 Author URI:        https://www.niteothemes.com
 Text Domain:       clp-custom-login-page
 Domain Path:		/languages
 License:           GPL-2.0+
 License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CLP_Custom_Login_Page' ) ) :

	/**
	 * Main CMP Coming Soon and Maintenance class.
	 *
	 * @since 1.0.0
	**/ 
	class CLP_Custom_Login_Page {
	
		private static $instance;
		public $settings;

		/**
		 * Main CLP_Custom_Login_Page Instance.
		 * @since 1.0.0
		 * @return object
		**/
		public static function instance() {
			if ( ! isset( self::$instance ) && !( self::$instance instanceof CLP_Custom_Login_Page ) ) {
				self::$instance = new CLP_Custom_Login_Page();

			}

			return self::$instance;
		}

		/**
         * Class Constructor
		 * @since 1.0.0
		**/
		public function __construct() {
			$this->settings = $this->get_settings();
			$this->autoloader();
			$this->constants();
			$this->hooks();
			$this->change_default_login_type();

			new CLP_Authorization_Expiration( $this->settings );
		}



		/**
         * Define plugin constants
		 * @since 1.0.0
		**/
		public function constants() {
			$this->define( 'CLP_VERSION', '1.5.5' );
			$this->define( 'CLP_DEV', FALSE );
			$this->define( 'CLP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			$this->define( 'CLP_PLUGIN_PATH', plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Includes required classes files
		 * @since 1.0.0
		**/
		private function autoloader() {
			require_once 'includes/class-clp-autoloader.php';
		}

		/**
         * Get plugin settings
		 * @since 1.4.0
		 * @return array
		**/
		private function get_settings() {
			$settings = unserialize(get_option('clp_settings', ''));

			// if no settings defined, get defaults
			if ( !$settings ) {
				$settings = $this->get_default_settings();
			}

			return $settings;
		}

		/**
         * Defines default plugin settings
		 * @since 1.4.0
		 * @return array
		**/
		private function get_default_settings() {
			$default_settings = array(
				'basic' => array(
					'login-type' => 'default',
					'auth-cookie' => 2,
					'auth-cookie-unit' => 'day',
					'auth-cookie-remember' => 14,
					'auth-cookie-remember-unit' => 'day',
				)
			);

			return $default_settings;
		}

		/**
		 * Define constant if not already set.
		 * @since 1.0.0
		 * @param  string|string $name Name of the definition.
		 * @param  string|bool   $value Default value.
		**/
		public function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
        }
    

		/**
		 * Inits and hooks
		 * @since 1.0.0
		**/
		public function hooks() {
			do_action( 'clp_plugin_loaded');
			add_action( 'init', array( $this, 'customize_login_template' ), 1 );
			add_action( 'init', array( $this, 'load_textdomain' ) );
			add_action( 'init', array( 'CLP_Compatibility', 'aio_wp_security_customizer_fix' ) );
			add_action( 'admin_init', array( $this, 'redirect_customizer' ) );
			add_action( 'admin_menu', array( $this, 'add_menu_items' ) );
			add_action( 'admin_notices', array( $this, 'add_admin_notice' ) );
			add_action( 'customize_register', array( $this, 'load_customizer' ) );
			
			add_action( 'wp_ajax_clp_wp_get_attachment_url_ajax', array( 'CLP_Helper_Functions', 'clp_wp_get_attachment_url_ajax' ) );
			add_action( 'wp_ajax_clp_get_unsplash', array( 'CLP_Unsplash_Api', 'clp_get_unsplash' ) );
			add_action( 'wp_ajax_clp_ajax_export_settings', array( 'CLP_Import_Export', 'clp_export_settings' ) );
			add_action( 'wp_ajax_clp_ajax_reset_settings', array( 'CLP_Import_Export', 'clp_reset_settings' ) );
			add_action( 'wp_ajax_clp_ajax_import_settings', array( 'CLP_Import_Export', 'clp_import_settings' ) );
			add_action( 'admin_enqueue_scripts', array( $this,'print_admin_style' ) ); 
			
			add_filter( 'template_include', array( $this, 'change_customizer_template' ), 99 );
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this,'add_action_links' ) );

			add_action( 'plugins_loaded', array( $this, 'migration' ), 1 );
		}

		/**
		 * Load text domain
         * @since 1.0.0
		**/
		public function load_textdomain() {
			load_plugin_textdomain( 'clp-custom-login-page', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * load Customizer
		 * @since 1.0.0
		**/
		public function load_customizer( $wp_customize ) {
			new CLP_Customizer( $wp_customize );
		}

		/**
		 * Add plugin menu
		 * @since 1.0.0
		**/
		public function add_menu_items() {
			$page = add_menu_page(__('CLP Settings', 'clp-custom-login-page'), __('CLP Settings', 'clp-custom-login-page'), 'manage_options', 'clp-settings', array($this, 'settings_page'), 'dashicons-buddicons-topics' );
			add_submenu_page('clp-settings', __('CLP Customize', 'clp-custom-login'), __('CLP Customize', 'clp-custom-login'), 'manage_options', 'clp-customize', 'false');
		}

	    public function add_admin_notice() {
			global $pagenow;

			// display save messages
			if ( isset( $_REQUEST['page']) && $_REQUEST['page'] == 'clp-settings' ) {
				if (isset($_REQUEST['status']) && $_REQUEST['status'] == 'settings-saved') {
					$status 	= 'success';
					$message 	= __('CLP Settings Saved', 'clp-custom-login');
					echo '<div class="notice notice-'.$status.' is-dismissible"><p>'.$message.'.</p></div>';
				}
			}
	    }

		/**
		 * Add settings page
		 * @since 1.0.0
		**/
		public function settings_page() {
			require_once (CLP_PLUGIN_DIR . 'includes/admin/settings-page.php');
		}

		/**
		 * Print CLP admin settings styles and scripts
		 * @since 1.4.0
		**/
		public function print_admin_style( $hook ) {
			// return of user is not logged in
			if ( !is_user_logged_in() ) {
				return;
			}

			// check for CLP settings page
			if ( $hook === 'toplevel_page_clp-settings' ) {
				wp_enqueue_style( 'clp-admin-settings',  CLP_PLUGIN_PATH .'assets/css/admin-settings.css', array(), CLP_Helper_Functions::assets_version('assets/css/admin-settings.css') );
			}
		}

		/**
		 * Add Plugin action links
		 * @since 1.0.0
		**/
		public function add_action_links( $links ) {
			$settings = array(
				'<a href="' . admin_url( 'customize.php?autofocus[panel]=clp_panel' ) . '">'.__('Customize Login Page', 'clp-custom-login-page').'</a>',
				'<a href="' . admin_url( 'admin.php?page=clp-settings' ) . '">'.__('Settings', 'clp-custom-login-page').'</a>',
			);
			return array_merge( $settings, $links );
		}



		/**
		 * Redirect to Customizer from admin menu 
		 * @since 1.0.0
		**/
		public function redirect_customizer() {
			if ( ! empty( $_GET['page'] ) ) { 
				if ( 'clp-customize' === $_GET['page'] ) { 
					$url = add_query_arg( array( 'autofocus[panel]' => 'clp_panel' ), admin_url( 'customize.php' ) );
					wp_safe_redirect( $url );
				}
			}
		}

		/**
		 * Change to login template if customizer panel is expanded and change the preview template per it's settings
		 * @since 1.0.0
		 * @return string
		**/
		public function change_customizer_template( $template ) {
			if ( is_customize_preview() && isset( $_REQUEST['clp-customize'] ) && is_user_logged_in() ) {
				$new_template = CLP_PLUGIN_DIR . 'includes/customizer-login-template.php';
				return $new_template;
			}

			return $template;
		}

		/**
		 * Include login template customizations to login template 
		 * @since 1.0.0
		 * @return void
		**/
		public function customize_login_template( ) {
			if ( $GLOBALS['pagenow'] === 'wp-login.php' || is_customize_preview() ) {
				include_once( CLP_PLUGIN_DIR . 'includes/template-customization.php' );
			}
		}

		/**
		 * INI Class to change default login type from email and username to email or username only if required
		 * @since 1.4.0
		 * @return void
		**/
		public function change_default_login_type() {
			switch ( $this->settings['basic']['login-type'] ) {
				case 'default':
					break;
				case 'username':
					remove_filter( 'authenticate', 'wp_authenticate_email_password', 20, 3 );
					break;
				case 'email':
					remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
					break;
				
				default:
					break;
			}
		}

		/**
		 * Add CLP updater proceess
		 *
		 * @since 1.4.0
		 */
		public function migration() {
			require_once('includes/update.php');
		}

	}

endif;

/*
 * @since 1.0.0
 * @return object|CLP_Custom_Login_Page instance.
 */
function clp_custom_login() {
	return CLP_Custom_Login_Page::instance();
}

// Get the things running
clp_custom_login();

// And here goes the uninstallation function
register_uninstall_hook( __FILE__, 'clp_plugin_delete' );
if ( !function_exists('clp_plugin_delete') ) {
	function clp_plugin_delete() {
		delete_option('clp_settings');
	}
}
