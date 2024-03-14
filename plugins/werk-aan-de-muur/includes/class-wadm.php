<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://werkaandemuur.nl/
 * @since      1.0.0
 *
 * @package    Wadm
 * @subpackage Wadm/includes
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
 * @package    Wadm
 * @subpackage Wadm/includes
 * @author     Sander van Leeuwen <sander@werkaandemuur.nl>
 */
class Wadm
{
	/**
	 * Set the text domain for translations in this plugin
	 */
	const TEXT_DOMAIN = 'wadm';

	/**
	 * Enable debugging to print error messages
	 */
	const DEBUG = false;

	/**
	 * Disable caching for development purposes
	 */
	const CACHE_ENABLED = true;

	/**
	 * Plugin version
	 */
	const VERSION = '1.4';

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wadm_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		$this->plugin_name = 'wadm';
		$this->version = self::VERSION;

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
	 * - Wadm_Loader. Orchestrates the hooks of the plugin.
	 * - Wadm_i18n. Defines internationalization functionality.
	 * - Wadm_Admin. Defines all hooks for the admin area.
	 * - Wadm_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wadm-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wadm-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wadm-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wadm-admin-setting.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wadm-admin-section.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wadm-admin-notice.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wadm-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wadm-feed-abstract.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wadm-feed-paged.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wadm-feed-artlist.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wadm-feed-album.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wadm-feed-artwork.php';

		$this->loader = new Wadm_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wadm_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wadm_i18n();

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

		$plugin_admin = new Wadm_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_setting' );
		$this->loader->add_action( 'admin_head-settings_page_' . $this->get_plugin_name(), $plugin_admin, 'testConnectionAndAuthentication');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wadm_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_shortcode('wadm_artlist', $plugin_public, 'artlist_shortcode');
        $this->loader->add_shortcode('omp_artlist', $plugin_public, 'artlist_shortcode');
        $this->loader->add_shortcode('artheroes_artlist', $plugin_public, 'artlist_shortcode');
		$this->loader->add_shortcode('wadm_album', $plugin_public, 'album_shortcode');
        $this->loader->add_shortcode('omp_album', $plugin_public, 'album_shortcode');
        $this->loader->add_shortcode('artheroes_album', $plugin_public, 'album_shortcode');
		$this->loader->add_shortcode('wadm_artwork', $plugin_public, 'artwork_shortcode');
        $this->loader->add_shortcode('omp_artwork', $plugin_public, 'artwork_shortcode');
        $this->loader->add_shortcode('artheroes_artwork', $plugin_public, 'artwork_shortcode');
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
	 * @return    Wadm_Loader    Orchestrates the hooks of the plugin.
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
