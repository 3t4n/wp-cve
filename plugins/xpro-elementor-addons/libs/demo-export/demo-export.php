<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit;
}

class Xpro_Elementor_Demo_Export {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      Xpro_Elementor_Demo_Export_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	public $loader;

	/**
	 * The admin class object of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      object Xpro_Elementor_Demo_Export_Admin    $admin
	 */
	public $admin;
	public array $actions;
	public array $filters;
	public WP_Error $errors;

	/**
	 * Empty construct
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Main Xpro_Elementor_Demo_Export Instance
	 *
	 * Insures that only one instance of Xpro_Elementor_Demo_Export exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null == $instance ) {
			$instance = new Xpro_Elementor_Demo_Export();

			$instance->setup_globals();
			$instance->load_dependencies();
			$instance->define_admin_hooks();
			$instance->run();
		}

		// Always return the instance
		return $instance;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Xpro_Elementor_Demo_Export_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function setup_globals() {

		//The array of actions and filters registered with this plugins.
		$this->actions = array();
		$this->filters = array();

		// Misc
		$this->errors = new WP_Error(); // errors
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . '/libs/demo-export/classes/class-demo-export-loader.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . '/libs/demo-export/function-form-load.php';
		require_once XPRO_ELEMENTOR_ADDONS_DIR_PATH . '/libs/demo-export/classes/class-demo-export-admin.php';

		$this->loader = new Xpro_Elementor_Demo_Export_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->admin = new Xpro_Elementor_Demo_Export_Admin();

		$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $this->admin, 'export_menu' );
		$this->loader->add_action( 'admin_init', $this->admin, 'export_content', 1 );
		$this->loader->add_action( 'wp_ajax_xpro_elementor_demo_export_ajax_form_load', $this->admin, 'form_load' );
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
	 * @return    Xpro_Elementor_Demo_Export_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}
}

Xpro_Elementor_Demo_Export::instance();
