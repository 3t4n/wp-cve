<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.linkedin.com/in/tomas-groulik/
 * @since      1.0.0
 *
 * @package    GG_Monarch_Sidebar_Minimized_On_Mobile
 * @subpackage GG_Monarch_Sidebar_Minimized_On_Mobile/includes
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
 * @package    GG_Monarch_Sidebar_Minimized_On_Mobile
 * @subpackage GG_Monarch_Sidebar_Minimized_On_Mobile/includes
 * @author     Tomas Groulik <tomas.groulik@gmail.com>
 */
class GG_Monarch_Sidebar_Minimized_On_Mobile {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      GG_Monarch_Sidebar_Minimized_On_Mobile_Loader    $loader    Maintains and registers all hooks for the plugin.
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
    
    protected $enquier;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct($enquier) {
        $this->version = GG_MONARCH_SIDEBAR_MINIMIZED_ON_MOBILE_VERSION;
		$this->plugin_name = GG_MONARCH_SIDEBAR_MINIMIZED_ON_MOBILE_NAME;
        $this->enquier = $enquier;
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - GG_Monarch_Sidebar_Minimized_On_Mobile_Loader. Orchestrates the hooks of the plugin.
	 * - GG_Monarch_Sidebar_Minimized_On_Mobile_i18n. Defines internationalization functionality.
	 * - GG_Monarch_Sidebar_Minimized_On_Mobile_Admin. Defines all hooks for the admin area.
	 * - GG_Monarch_Sidebar_Minimized_On_Mobile_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gg-monarch-sidebar-minimized-on-mobile-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gg-monarch-sidebar-minimized-on-mobile-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-gg-monarch-sidebar-minimized-on-mobile-public.php';


        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-gg-monarch-sidebar-minimized-on-mobile-admin.php';

        /* Initialize REST API */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/gg-monarch-sidebar-minimized-on-mobile-route.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-gg-monarch-sidebar-minimized-on-mobile-admin-promo.php';

		$this->loader = new GG_Monarch_Sidebar_Minimized_On_Mobile_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the GG_Monarch_Sidebar_Minimized_On_Mobile_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new GG_Monarch_Sidebar_Minimized_On_Mobile_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
        $plugin_admin = new GG_Monarch_Sidebar_Minimized_On_Mobile_Admin( $this->get_plugin_name(), $this->get_version(), $this->enquier );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        
        $plugin_promo = new GG_Monarch_Sidebar_Minimized_On_Mobile_Admin_Promo($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action( 'admin_notices', $plugin_promo, 'add_admin_notice' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new GG_Monarch_Sidebar_Minimized_On_Mobile_Public( $this->get_plugin_name(), $this->get_version() , $this->enquier);

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    GG_Monarch_Sidebar_Minimized_On_Mobile_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
