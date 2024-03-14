<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       devmaverick.com
 * @since      1.0.0
 *
 * @package    Code_Snippet_Dm
 * @subpackage Code_Snippet_Dm/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Code_Snippet_Dm
 * @subpackage Code_Snippet_Dm/includes
 * @author     George Cretu <george@devmaverick.com>
 */
class CSDM_Code_Snippet_Dm {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      CSDM_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CSDM_PLUGIN_NAME_VERSION' ) ) {
			$this->version = CSDM_PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'code-snippet-dm';

		$this->csdm_load_dependencies();
		$this->csdm_set_locale();
		$this->csdm_define_admin_hooks();
		// $this->csdm_define_public_hooks();
		$this->csdm_shortcode_hooks();
		$this->csdm_tinymce_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - CSDM_Loader. Orchestrates the hooks of the plugin.
	 * - CSDM_i18n. Defines internationalization functionality.
	 * - CSDM_Admin. Defines all hooks for the admin area.
	 * - CSDM_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function csdm_load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-code-snippet-dm-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-code-snippet-dm-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-code-snippet-dm-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/code-snippet-dm-admin-tinymce.php';



		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-code-snippet-dm-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-code-snippet-dm-shortcode.php';

		$this->loader = new CSDM_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the CSDM_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function csdm_set_locale() {

		$plugin_i18n = new CSDM_i18n();

		$this->loader->csdm_add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function csdm_define_admin_hooks() {

		$plugin_admin = new CSDM_Admin( $this->csdm_get_plugin_name(), $this->csdm_get_version() );

		$this->loader->csdm_add_action( 'admin_enqueue_scripts', $plugin_admin, 'csdm_enqueue_styles' );
		$this->loader->csdm_add_action( 'admin_enqueue_scripts', $plugin_admin, 'csdm_enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function csdm_shortcode_hooks() {

		$shortcode = new CSDM_Shortcode($this->csdm_get_plugin_name(), $this->csdm_get_version());

	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function csdm_tinymce_hooks() {

		$csdm_admin_tinymce = new CSDM_Admin_Tinymce;

	}




	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function csdm_define_public_hooks() {

		$plugin_public = new CSDM_Public( $this->csdm_get_plugin_name(), $this->csdm_get_version() );

		$this->loader->csdm_add_action( 'wp_enqueue_scripts', $plugin_public, 'csdm_enqueue_styles' );
		$this->loader->csdm_add_action( 'wp_enqueue_scripts', $plugin_public, 'csdm_enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function csdm_run() {
		$this->loader->csdm_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function csdm_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    CSDM_Loader    Orchestrates the hooks of the plugin.
	 */
	public function csdm_get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function csdm_get_version() {
		return $this->version;
	}

}
