<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://sharabindu.com
 * @since      1.4.0
 *
 * @package    Elfi Masonry Addon
 * @subpackage Elfi Masonry Addon/includes
 */

class Elfi_Light {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.4.0
	 * @access   protected
	 * @var      Elfi_Light_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.4.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.4.0
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
	 * @since    1.4.0
	 */
	public function __construct() {
		if ( defined( 'ELFI_VERSION_LIGHT' ) ) {
			$this->version = ELFI_VERSION_LIGHT;
		} else {
			$this->version = '1.4.0';
		}
		$this->plugin_name = 'elfi-masonry-addon';

		$this->elfi_load_dependencies();
		$this->Elfi_Light_Admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Elfi_Light_Loader. Orchestrates the hooks of the plugin.
	 * - Elfi_i18n. Defines internationalization functionality.
	 * - Elfi_Light_Admin. Defines all hooks for the admin area.
	 * - Elfi_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.4.0
	 * @access   private
	 */
	private function elfi_load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once ELFI_PATH_LIGHT. 'includes/Class/class-elfi-loader.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once ELFI_PATH_LIGHT. 'includes/Class/class-elfi-admin.php';

		/**
		 * Responsible for defining all control that occur in the Elemntor Widget area.
		 */		

		require_once ELFI_PATH_LIGHT. 'includes/helper/elfi-helper-control.php';
		require_once ELFI_PATH_LIGHT. 'includes/helper/elfi-gallery-control.php';

		/**
		 * Responsible for defining all helper function occur in the Elemntor Widget area.
		 */		

		require_once ELFI_PATH_LIGHT. '/includes/helper/elfi-helper-function.php';

		/**
		 * The class responsible for defining all actions that occur in the elfi filter widgts area.
		 */
		require_once ELFI_PATH_LIGHT. 'includes/Class/class-elfi-filter.php';
		require_once ELFI_PATH_LIGHT. 'includes/Class/class-elfi-admin-dashborad.php';
		require_once ELFI_PATH_LIGHT. 'includes/Class/class-elfi-permalink.php';
		require_once ELFI_PATH_LIGHT. 'includes/metaData/class-elfi-previewlink.php';
		require_once ELFI_PATH_LIGHT. 'includes/metaData/class-elfi-videodata.php';


		$this->loader = new Elfi_Light_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.4.0
	 * @access   private
	 */
	private function Elfi_Light_Admin_hooks() {

		$plugin_admin = new Elfi_Light_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'elementor/elements/categories_registered', $plugin_admin, 'elfi_widget_categories' ,5);

		$this->loader->add_action( 'init', $plugin_admin, 'elfi_post_type');

		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'elfi_plugin_row_meta', 10, 2 );

		$this->loader->add_filter( 'post_updated_messages', $plugin_admin, 'rw_post_updated_messages');

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.4.0
	 */
	public function elfi_light_get_run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.4.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.4.0
	 * @return    Elfi_Light_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.4.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
