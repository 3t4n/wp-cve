<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       https://timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoTarget {

	/**
	 * @since 1.1
	 * @var notices class
	 */
	public $geot_notices;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      GeoTarget_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $GeoTarget    The string used to uniquely identify this plugin.
	 */
	protected $GeoTarget;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Instance of GetFunctions
	 * @var object
	 */
	public $functions;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->GeoTarget = 'geotarget';
		$this->version = GEOT_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_helper_hooks();
		$this->define_public_hooks();
		$this->register_shortcodes();
		$this->define_admin_hooks();


	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - GeoTarget_Loader. Orchestrates the hooks of the plugin.
	 * - GeoTarget_i18n. Defines internationalization functionality.
	 * - GeoTarget_Admin. Defines all hooks for the dashboard.
	 * - GeoTarget_Public. Defines all hooks for the public side of the site.
	 * - GeoTarget_Function. Defines all main functions for targeting
	 * - GeoTarget_Filters. Defines all main filters helpers
	 * - GeoTarget_shortcodes. Defines all plugin shortcodes
	 * - GeoTarget_Widget. Defines plugin widget
	 * - GeoTarget_Widgets. Target all widgets with geot
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-geotarget-admin.php';

		/**
		 * Geotargeting functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-functions.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-geotarget-public.php';

		/**
		 * The class responsible for defining all helper filters
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-filters.php';

		/**
		 * The class responsible for registering shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-shortcodes.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-notices.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-geotarget-upgrader.php';



		$this->loader = new GeoTarget_Loader();

		$this->functions = new GeoTarget_Functions( $this->get_GeoTarget(), $this->get_version() );

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the GeoTarget_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new GeoTarget_i18n();
		$plugin_i18n->set_domain( $this->get_GeoTarget() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all helpers functions
	 * @since 1.0.0
	 * @access private
	 */
	private function define_helper_hooks() {
		$helpers = new GeoTarget_Filters( $this->get_GeoTarget(), $this->get_version() );

		$this->loader->add_filter( 'geot/get_post_types', $helpers, 'get_post_types',1,3 );
		$this->loader->add_filter( 'geot/get_countries', $helpers, 'get_countries',1 );
	}
	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new GeoTarget_Admin( $this->get_GeoTarget(), $this->get_version() );
		$this->geot_notices    = new GeoTarget_Notices( $this->version );
		if( get_option('geot_plugin_updated') && !get_option('geot_rate_plugin') )
			$this->loader->add_action( 'admin_notices', $this->geot_notices, 'rate_plugin' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// settings page
		$this->loader->add_action( 'admin_menu' , $plugin_admin, 'add_settings_menu' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new GeoTarget_Public( $this->get_GeoTarget(), $this->get_version(), $this->functions );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Popups rules
		$this->loader->add_action( 'init' , $plugin_public, 'register_popup_fields');
		$this->loader->add_filter( 'spu/metaboxes/rule_types', $plugin_public, 'add_popups_rules' );
		$this->loader->add_filter( 'spu/rules/rule_values/geot_country', $plugin_public, 'add_popups_rules_choices' );
		$this->loader->add_filter( 'spu/rules/rule_match/geot_country', $plugin_public, 'popup_match_rules', 10, 2 );

		$this->loader->add_action( 'wp_footer', $plugin_public, 'print_debug_info', 999 );
	}

	/**
	 * Register shortcodes
	 * @access   private
	 */
	private function register_shortcodes()
	{
		$shortcodes = new GeoTarget_Shortcodes( $this->get_GeoTarget(), $this->get_version(), $this->functions );

		add_shortcode('geot', array( $shortcodes, 'geot_filter') );
		add_shortcode('geot_country_name', array( $shortcodes, 'geot_name') );
		add_shortcode('geot_country_code', array( $shortcodes, 'geot_code') );
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
	public function get_GeoTarget() {
		return $this->GeoTarget;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    GeoTarget_Loader    Orchestrates the hooks of the plugin.
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
