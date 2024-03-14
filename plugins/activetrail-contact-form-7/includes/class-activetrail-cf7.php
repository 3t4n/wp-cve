<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link	   http://activetrail.com
 * @since	  1.0.0
 *
 * @package	Activetrail_Cf7
 * @subpackage Activetrail_Cf7/includes
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
 * @since	  1.0.0
 * @package	Activetrail_Cf7
 * @subpackage Activetrail_Cf7/includes
 * @author	 ActiveTrail <contact@activetrail.com>
 */
class Activetrail_Cf7 {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since	1.0.0
	 * @access   protected
	 * @var	  Activetrail_Cfs_Loader	$loader	Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since	1.0.0
	 * @access   protected
	 * @var	  string	$plugin_name	The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since	1.0.0
	 * @access   protected
	 * @var	  string	$version	The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since	1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'activetrail-cf7';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->define_admin_hooks();
		
		$this->at_cf7_initialize_integration();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Activetrail_Cfs_Loader. Orchestrates the hooks of the plugin.
	 * - Activetrail_Cf7_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since	1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/constants-activetrail-cf7.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/models/class-activetrail-cf7-call-api.php';
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-activetrail-cf7-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-activetrail-cf7-admin.php';

		$this->loader = new Activetrail_Cf7_Loader();
	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since	1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Activetrail_Cf7_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}
		
		
	private function at_cf7_initialize_integration() {
		$plugin_admin = new Activetrail_Cf7_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wpcf7_editor_panels', $plugin_admin, 'at_cf7_editor_panels' );
		
		$this->loader->add_action( 'wpcf7_after_save', $plugin_admin, 'at_cf7_save_form' );
		
		$this->loader->add_action( 'wpcf7_before_send_mail', $plugin_admin, 'at_cf7_post_form' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since	1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since	 1.0.0
	 * @return	string	The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since	 1.0.0
	 * @return	Activetrail_Cfs_Loader	Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since	 1.0.0
	 * @return	string	The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
