<?php
/**
 * Plugin Name: Maintenance Page
 * Plugin URI: http://themegrill.com/maintenance-page/
 * Description: Allows you to quickly create a maintenance/coming-soon page. You can use this plugin whenever your site is down for maintenance, currently going any upgrade or is in development. Built in options panel to add logo, background image, social icons, color, subscribe field and many more. Get free support at http://themegrill.com/support-forum/ and check the demo at http://demo.themegrill.com/maintenance-page/
 * Author: ThemeGrill
 * Author URI: http://themegrill.com
 * Version: 1.0.9
 *
 * Maintenance Page is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Maintenance Page is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Maintenance Page. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Maintenance_Page
 * @author ThemeGrill <themegrill@gmail.com>
 * @version 1.0
 */

/**
 * If this file is attempted to be accessed directly, we'll exit.
 *
 * The following check provides a level of security from other files
 * that request data directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Maintenance_Page' ) ) {
	/**
	 * Main Class
	 *
	 * @since 1.0
	 */
	class Maintenance_Page {
		/**
		 * @var Maintenance_Page
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * Maintenance Page theme options Object
		 *
		 * @var object
		 * @since 1.0
		 */
		public $setting_options;

		/**
		 * Private constructor prevents construction outside this class.
		 */
		private function __construct() {
		}

		/**
		 * Main Maintenance Page Instance
		 *
		 * Insures that only one instance of Maintenance_Page exists in memory at any one
		 * time. In singleton class you cannot create a second instance
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @uses Maintenance_Page::setup_constants() Setup the contants needed
		 * @uses Maintenance_Page::includes() Include the required files
		 * @uses Maintenance_Page::setup_actions() Setup the hooks and actions
		 * @uses Maintenance_Page::load_textdomain() loads the textdomain for tranlation
		 * @see mp()
		 * @return The one true Maintenance_Page
		 */
		public static function getInstance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->load_textdomain();
				self::$instance->setting_options = new MP_Settings();
				self::$instance->setup_actions();
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'maintenance-page' ), '1.0' );
		}

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function setup_constants() {
			// Plugin version
			$this->version = '1.0';

			// Setup some base path and URL information
			$this->file       = __FILE__; // Plugin Root File
			$this->plugin_dir = apply_filters( 'mp_plugin_dir_path', plugin_dir_path( $this->file ) ); // Plugin Folder Path
			$this->plugin_url = apply_filters( 'mp_plugin_dir_url', plugin_dir_url( $this->file ) ); // Plugin Folder URL
			$this->basename   = apply_filters( 'mp_plugin_basenname', plugin_basename( $this->file ) ); //path relative to plugin folder
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function includes() {
			require_once $this->plugin_dir . 'includes/admin/class-settings-api.php';
			require_once $this->plugin_dir . 'includes/admin/settings-api.php';
			require_once $this->plugin_dir . 'includes/lib/class-maintenance-page-activator.php';
		}

		/**
		 * Setup the default hooks and actions
		 *
		 * @access private
		 * @since 1.0
		 * @uses add_filter() hook a function to specific filter action
		 */
		private function setup_actions() {
			add_action( 'template_redirect', array( $this, 'load_template_file' ), 8 );
			add_action( 'wp_ajax_subscribe_ajax', array( $this, 'subscribe_download' ) );
			/** This action is documented in includes/class-maintenance-page-activator.php */
			register_activation_hook( $this->file, array( 'Maintenance_Page_Activator', 'activate' ) );
			add_action( 'admin_bar_menu', array( $this, 'admin_bar_notice' ), 999 );
			//fires when new blog is created in multisite.
			add_action( 'wpmu_new_blog', array( $this, 'create_subscribe_table_mu' ), 10, 6 );
			add_filter( 'wpmu_drop_tables', array( $this, 'delete_subscribe_table_mu' ) );
			add_filter( 'rest_authentication_errors', array( $this, 'restrict_rest_api' ) );
		}

		/**
		 * Only allow REST API to be accessed by logged in users when maintenance page is activated
		 *
		 * @since 1.0.9
		 * @return void
		 */

		public function restrict_rest_api( $result ) {
			if ( $this->setting_options->get_option( 'mp_activate', 'mp_basics' ) == 'on' ) {

				if ( true === $result || is_wp_error( $result ) ) {
					return $result;
				}

				// Check if the user is logged in and is an administrator
				if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
					return new WP_Error(
						__( 'REST API is disabled. Currently under maintenance mode!!' ),
						array( 'status' => 403 )
					);
				}
				return $result;
			}
		}

		/**
		 * Loads the plugin language files
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'maintenance-page', false, dirname( $this->basename ) . '/languages' );
		}

		/**
		 * Loads the template file
		 *
		 * @since 1.0
		 * @return void
		 */
		public function load_template_file() {
			if ( $this->setting_options->get_option( 'mp_activate', 'mp_basics' ) == 'on' ) {
				//Allow logged in user to view site.
				if ( ! is_user_logged_in() ) {
					if ( ! $this->check_excluded_page() ) {
						$this->check_header_status();
						$file = $this->plugin_dir . 'public/template/maintenance-page-display.php';
						include $file;
						exit();
					}
				}
			}
		}

		/**
		 * Checks for page to exclude for redirect.
		 *
		 * @since 1.0
		 * @return boolean
		 */
		public function check_excluded_page() {
			$is_exclude = false;
			if ( $this->setting_options->get_option( 'mp_exclude', 'mp_basics' ) ) {
				$excluded = explode( "\n", $this->setting_options->get_option( 'mp_exclude', 'mp_basics' ) );
				foreach ( $excluded as $key => $excluded_slug ) {
					if ( rtrim( $excluded_slug ) == trim( $_SERVER['REQUEST_URI'], '/' ) ) {
						$is_exclude = true;
						break;
					}
				}
			}
			return $is_exclude;
		}

		/**
		 * Send 503 unavailable header status if activated
		 *
		 * @since 1.0
		 */
		function check_header_status() {
			// set headers
			nocache_headers();
			if ( $this->setting_options->get_option( 'mp_header_status', 'mp_basics' ) == 'on' ) {
				$protocol = 'HTTP/1.0';
				if ( 'HTTP/1.1' == $_SERVER['SERVER_PROTOCOL'] ) {
					$protocol = 'HTTP/1.1';
				}
				header( "$protocol 503 Service Unavailable", true, 503 );
				header( 'Retry-After: 86400' ); //retry in a day
				$mp_construction_file = WP_CONTENT_DIR . '/maintenance-page.php';
				if ( ! empty( $mp_construction_file ) and file_exists( $mp_construction_file ) ) {
					include_once $mp_construction_file;
					exit();
				}
			}
		}

		/**
		 * Download subscibers list
		 *
		 * @since 1.0
		 * @global $wpdb
		 * @return void
		 */
		public function subscribe_download() {

			if ( ! check_admin_referer( 'subscribe_download' ) || ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'Security check failed!', 'maintenance-page' ) );
			}

			global $wpdb;

			$results = $wpdb->get_results( "SELECT email, insert_date FROM {$wpdb->prefix}maintenance_page ORDER BY id DESC", ARRAY_A );
			if ( ! empty( $results ) ) {
				$filename = 'subscribers-' . date( 'Y-m-d-h-i-s' ) . '.csv';

				header( 'Content-Type: text/csv' );
				header( 'Content-Disposition: attachment;filename=' . $filename );

				$fp = fopen( 'php://output', 'w' );

				fputcsv( $fp, array( 'email', 'insert_date' ) );
				foreach ( $results as $item ) {
					fputcsv( $fp, $item );
				}

				fclose( $fp );
			}
		}

		/**
		 * Shows admin notice on/off
		 *
		 * @since 1.0
		 * @global $wp_admin_bar
		 * @return void
		 */
		function admin_bar_notice() {
			global $wp_admin_bar;
			$value = $this->setting_options->get_option( 'mp_activate', 'mp_basics' );
			if ( $value == 'on' ) {
				$argsParent = array(
					'id'    => 'admin_bar_notice',
					'title' => __( 'Maintenance Page On', 'maintenance-page' ),
					'href'  => admin_url( 'options-general.php?page=mp_settings_api' ),
					'meta'  => array( 'class' => 'mode-enable' ),
				);
			} else {
				// Add Parent Menu
				$argsParent = array(
					'id'    => 'admin_bar_notice',
					'title' => __( 'Maintenance Page Off', 'maintenance-page' ),
					'href'  => admin_url( 'options-general.php?page=mp_settings_api' ),
					'meta'  => array( 'class' => 'mode-disable' ),
				);

			}
			$wp_admin_bar->add_menu( $argsParent );
		}

		/**
		 * Create subscribe table for new multisite created
		 *
		 * @since 1.0.7
		 * @return void
		 */
		public function create_subscribe_table_mu( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
			if ( is_plugin_active_for_network( 'maintenance-page/maintenance-page.php' ) ) {
				switch_to_blog( $blog_id );
				Maintenance_Page_Activator::create_subscribe_table();
				restore_current_blog();
			}
		}

		/**
		 * Delete subscribe table when multisite blog is deleted
		 *
		 * @since 1.0.7
		 * @global $wpdb
		 * @return void
		 */
		public function delete_subscribe_table_mu( $tables ) {
			global $wpdb;
			$tables[] = $wpdb->prefix . 'maintenance_page';
			return $tables;
		}
	}
}

/**
 * The main function responsible for returning the one true Maintenance_Page
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @since 1.0
 * @return object The one true Maintenance_Page Instance
 */
function MP() {
	return Maintenance_Page::getInstance();
}

// Get mp Running
MP();
