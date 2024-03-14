<?php

use Elementor\Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit;
}

/**
 * The starter site plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 */
class Xpro_Elementor_Starter_Sites {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Xpro_Elementor_Starter_Sites_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The admin class object of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      object Xpro_Elementor_Starter_Sites_Admin    $admin
	 */
	public $admin;
	public $actions;
	public $filters;
	public $errors;

	/**
	 * Main Xpro_Elementor_Starter_Sites Instance
	 *
	 * Insures that only one instance of Xpro_Elementor_Starter_Sites exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @return object
	 * @uses Xpro_Elementor_Starter_Sites::setup_globals() Setup the globals needed
	 * @uses Xpro_Elementor_Starter_Sites::load_dependencies() Include the required files
	 * @uses Xpro_Elementor_Starter_Sites::define_admin_hooks() Setup admin hooks and actions
	 * @uses Xpro_Elementor_Starter_Sites::run() run
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null == $instance ) {
			$instance = new Xpro_Elementor_Starter_Sites();

			$instance->setup_globals();
			$instance->load_dependencies();
			$instance->define_admin_hooks();
			$instance->run();
		}

		// Always return the instance
		return $instance;
	}

	/**
	 * Empty construct
	 *
	 * @since    1.0.0
	 */
	public function __construct() {}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function setup_globals() {

		// The array of actions and filters registered with this plugins.
		$this->actions = array();
		$this->filters = array();

		// Misc
		$this->errors = new WP_Error(); // errors
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Xpro_Elementor_Starter_Sites_Loader. Orchestrates the hooks of the plugin.
	 * - Xpro_Elementor_Starter_Sites_Admin. Defines all hooks for the admin area.
	 * - Xpro_Elementor_Starter_Sites_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/starter-sites/classes/class-starter-sites-loader.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/starter-sites/classes/class-starter-sites-admin.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/starter-sites/classes/class-elementor-import.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . 'libs/starter-sites/classes/class-wp-site-reset.php';

		$this->loader = new Xpro_Elementor_Starter_Sites_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->admin = xpro_elementor_starter_sites_admin();

		$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_scripts' );

		/*add mime types*/
		$this->loader->add_action( 'mime_types', $this->admin, 'mime_types' );
		$this->loader->add_action( 'upload_mimes', $this->admin, 'mime_types' );

		/*add menu*/
		$this->loader->add_action( 'admin_menu', $this->admin, 'import_menu' );

		/*ajax process*/
		$this->loader->add_action( 'wp_ajax_xpro_elementor_starter_sites_ajax_setup', $this->admin, 'upload_zip' );
		$this->loader->add_action( 'wp_ajax_demo_download_and_unzip', $this->admin, 'demo_download_and_unzip' );
		$this->loader->add_action( 'wp_ajax_plugin_screen', $this->admin, 'plugin_screen' );
		$this->loader->add_action( 'wp_ajax_install_plugin', $this->admin, 'install_plugin' );
		$this->loader->add_action( 'wp_ajax_content_screen', $this->admin, 'content_screen' );
		$this->loader->add_action( 'wp_ajax_import_content', $this->admin, 'import_content' );
		$this->loader->add_action( 'wp_ajax_complete_screen', $this->admin, 'complete_screen' );

		/*Reset Process*/
		$this->loader->add_action( 'admin_init', xpro_elementor_starter_sites_reset_wordpress(), 'reset_wizard_actions', - 1 );
		$this->loader->add_action( 'wp_ajax_xpro_elementor_starter_sites_before_reset', xpro_elementor_starter_sites_reset_wordpress(), 'before_reset' );

		/*Starter Sites Data*/
		add_filter( 'xpro_elementor_starter_sites_demo_lists', array( $this, 'add_template_library' ) );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Xpro_Elementor_Starter_Sites_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	public function add_template_library( $current_demo_list ) {

		/*finally fetch template library data from live*/
		$templates_list = array();

		$url = 'https://demodata.wpxpro.com/demo-data/themes.json';

		$body_args = array(
			/*API version*/
			'api_version' => wp_get_theme()['Version'],
			/*lang*/
			'site_lang'   => get_bloginfo( 'language' ),
		);

		$raw_json = wp_safe_remote_get(
			$url,
			array(
				'timeout'   => 100,
				'sslverify' => false,
				'body'      => $body_args,
			)
		);

		if ( ! is_wp_error( $raw_json ) ) {
			$demo_server = json_decode( wp_remote_retrieve_body( $raw_json ), true );
			if ( json_last_error() == JSON_ERROR_NONE ) {
				if ( is_array( $demo_server ) ) {
					$templates_list = $demo_server;
				}
			}
		}

		return array_merge( $current_demo_list, $templates_list );
	}
}


Xpro_Elementor_Starter_Sites::instance();
