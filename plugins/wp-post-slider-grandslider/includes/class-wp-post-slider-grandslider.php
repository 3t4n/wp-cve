<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://forhad.net/
 * @since      1.0.0
 *
 * @package    Wp_Post_Slider_Grandslider
 * @subpackage Wp_Post_Slider_Grandslider/includes
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
 * @package    Wp_Post_Slider_Grandslider
 * @subpackage Wp_Post_Slider_Grandslider/includes
 * @author     Forhad <need@forhad.net>
 */
class Wp_Post_Slider_Grandslider {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Post_Slider_Grandslider_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'WP_POST_SLIDER_GRANDSLIDER_VERSION' ) ) {
			$this->version = WP_POST_SLIDER_GRANDSLIDER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-post-slider-grandslider';

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
	 * - Wp_Post_Slider_Grandslider_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Post_Slider_Grandslider_i18n. Defines internationalization functionality.
	 * - WP_Post_Slider_Grandslider_Admin. Defines all hooks for the admin area.
	 * - Wp_Post_Slider_Grandslider_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-slider-grandslider-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-slider-grandslider-i18n.php';

		/**
		 * The class responsible for a custom post type.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpps-cpt.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-post-slider-grandslider-admin.php';

		/**
		 * The class responsible for defining admin display with a menu.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wppsgs-admin-display.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-post-slider-grandslider-public.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/wppsgs-shortcode-display.php';

		$this->loader = new Wp_Post_Slider_Grandslider_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Post_Slider_Grandslider_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Post_Slider_Grandslider_i18n();

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

		$plugin_admin = new WP_Post_Slider_Grandslider_Admin( $this->get_plugin_name(), $this->get_version() );

		// Enqueuing.
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Plugin admin custom post types.
		$plugin_admin_cpt = new WP_Post_Slider_Grandslider_CPT( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_admin_cpt, 'wppsgs_post_type' );
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin_cpt, 'wppsgs_updated_messages', 10, 2 );
		$this->loader->add_filter( 'manage_wppsgs_blocks_posts_columns', $plugin_admin_cpt, 'wppsgs_admin_column' );
		$this->loader->add_action( 'manage_wppsgs_blocks_posts_custom_column', $plugin_admin_cpt, 'wppsgs_admin_field', 10, 2 );

		// Admin Display Page.
		$wppsgs_help_menu = new WP_Post_Slider_Grandslider_Admin_Display();
		$this->loader->add_action( 'admin_menu', $wppsgs_help_menu, 'wppsgs_admin_display', 25 );
		$this->loader->add_filter( 'plugin_action_links', $wppsgs_help_menu, 'wppsgs_add_action_plugin', 10, 5 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Post_Slider_Grandslider_Public( $this->get_plugin_name(), $this->get_version() );

		// Enqueuing.
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Shortcode.
		$plugin_shortcode = new WPPSGS_Shortcode( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wppsgs_action_tag_for_shortcode', $plugin_shortcode, 'wppsgs_shortcode_execute' );
		add_shortcode( 'wppsgs_slider', array( $plugin_shortcode, 'wppsgs_shortcode_execute' ) );
		add_shortcode( 'wppsgs_tmonial', array( $plugin_shortcode, 'wppsgs_shortcode_tmonial_execute' ) );

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
	 * @return    Wp_Post_Slider_Grandslider_Loader    Orchestrates the hooks of the plugin.
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
