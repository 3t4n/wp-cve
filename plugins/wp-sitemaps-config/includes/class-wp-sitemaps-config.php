<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.kybernetik-services.com/
 * @since      1.0.0
 *
 * @package    WP_Sitemaps_Config
 * @subpackage WP_Sitemaps_Config/includes
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
 * @package    WP_Sitemaps_Config
 * @subpackage WP_Sitemaps_Config/includes
 * @author     Kybernetik Services <wordpress@kybernetik.com.de>
 */
if ( ! class_exists( 'WP_Sitemaps_Config' ) ) {
class WP_Sitemaps_Config {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WP_Sitemaps_Config_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * The slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_slug    The slug of this plugin.
	 */
	protected $plugin_slug;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_version    The current version of the plugin.
	 */
	protected $plugin_version;

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

		$this->plugin_name = WP_SITEMAPS_CONFIG_NAME;
		$this->plugin_slug = sanitize_title( $this->plugin_name );
		$this->plugin_version = WP_SITEMAPS_CONFIG_VERSION;

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WP_Sitemaps_Config_Loader. Orchestrates the hooks of the plugin.
	 * - WP_Sitemaps_Config_Admin. Defines all hooks for the admin area.
	 * - WP_Sitemaps_Config_Public. Defines all hooks for the public side of the site.
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
		require_once WP_SITEMAPS_CONFIG_ROOT . 'includes/class-wp-sitemaps-config-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WP_SITEMAPS_CONFIG_ROOT . 'admin/class-wp-sitemaps-config-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once WP_SITEMAPS_CONFIG_ROOT . 'public/class-wp-sitemaps-config-public.php';

		$this->loader = new WP_Sitemaps_Config_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WP_Sitemaps_Config_Admin( array(
			'name' => $this->plugin_name, 
			'slug' => $this->plugin_slug, 
			'plugin_version' => $this->plugin_version,
			)
		);

		// load javascripts and stylesheets
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		// $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add the menu item to the options page in Settings.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_item_to_options_page' );

		// Add an action link pointing to the options page.
		$this->loader->add_filter( 'plugin_action_links_' . WP_SITEMAPS_CONFIG_BASENAME, $plugin_admin, 'add_action_links' );

		// specify and register the plugin's options
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_options' );

		// print options page content for tab 'General'
		$this->loader->add_action( 'wp_sitemaps_config_general', $plugin_admin, 'print_content_general' );

		// print options page content for tab 'Posts'
		$this->loader->add_action( 'wp_sitemaps_config_posts', $plugin_admin, 'print_content_posts' );

		// print options page content for tab 'Taxonomies'
		$this->loader->add_action( 'wp_sitemaps_config_taxonomies', $plugin_admin, 'print_content_taxonomies' );

		// print options page content for tab 'Users'
		$this->loader->add_action( 'wp_sitemaps_config_users', $plugin_admin, 'print_content_users' );

		// print options page content for tab 'Stylesheet'
		$this->loader->add_action( 'wp_sitemaps_config_stylesheet', $plugin_admin, 'print_content_stylesheet' );

		// hook on displaying a message after plugin activation
		// if single activation via link or bulk activation
		if ( isset( $_GET[ 'activate' ] ) || isset( $_GET[ 'activate-multi' ] ) ) {
			$plugin_was_activated = get_transient( WP_SITEMAPS_CONFIG_TRANSIENT_PLUGIN_ACTIVATED );
			if ( false !== $plugin_was_activated ) {
				$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_activation_message' );
				delete_transient( WP_SITEMAPS_CONFIG_TRANSIENT_PLUGIN_ACTIVATED );
			}
		}
		
		// print metabox on post edit page for posts related sitemap options
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_posts_metabox' );
		// save posts sitemaps settings
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_posts_sitemap_settings' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WP_Sitemaps_Config_Public( array(
			'name' => $this->plugin_name, 
			'slug' => $this->plugin_slug, 
			'plugin_version' => $this->plugin_version,
			)
		);

		// load javascripts and stylesheets
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// add or remove sitemaps at all
		$this->loader->add_filter( 'wp_sitemaps_enabled', $plugin_public, 'is_sitemaps_enabled' );
		// add or remove sitemap providers
		$this->loader->add_filter( 'wp_sitemaps_add_provider', $plugin_public, 'change_sitemaps_provider', 10, 2 );
		// add or remove sitemaps for certain post types
		$this->loader->add_filter( 'wp_sitemaps_post_types', $plugin_public, 'change_sitemaps_post_types' );
		// add or remove tags to sitemap entries
		$this->loader->add_filter( 'wp_sitemaps_posts_entry', $plugin_public, 'change_sitemaps_posts_entry', 10, 2 );
		// add or remove sitemaps for certain taxonomies
		$this->loader->add_filter( 'wp_sitemaps_taxonomies', $plugin_public, 'change_sitemaps_taxonomies' );
		// remove excluded posts from the sitemap
		$this->loader->add_filter( 'wp_sitemaps_posts_query_args', $plugin_public, 'exclude_single_posts', 10, 2 );
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 * @access   public
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
	 * @access    public
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The slug of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The slug of the plugin.
	 * @access    public
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WP_Sitemaps_Config_Loader    Orchestrates the hooks of the plugin.
	 * @access    public
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 * @access    public
	 */
	public function get_plugin_version() {
		return $this->plugin_version;
	}

}
}
