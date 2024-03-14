<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ABR
 * @subpackage ABR/includes
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
 * @package    ABR
 * @subpackage ABR/includes
 */
class ABR {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access protected
	 * @var ABR_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access protected
	 * @var string $abr The string used to uniquely identify this plugin.
	 */
	protected $abr;

	/**
	 * The current version of the plugin.
	 *
	 * @access protected
	 * @var string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 */
	public function __construct() {

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Get plugin data.
		$plugin_data = get_plugin_data( ABR_PATH . '/absolute-reviews.php' );

		$this->version = $plugin_data['Version'];
		$this->abr     = 'absolute-reviews';

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
	 * - ABR_Loader. Orchestrates the hooks of the plugin.
	 * - ABR_i18n. Defines internationalization functionality.
	 * - ABR_Admin. Defines all hooks for the admin area.
	 * - ABR_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Helpers Functions for the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helpers-absolute-reviews.php';

		/**
		 * Post Meta.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/post-meta.php';

		/**
		 * Posts Template.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/posts-template.php';

		/**
		 * Register widgets for the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-absolute-reviews-posts-widget.php';

		/**
		 * Register gutenberg blocks.
		 */
		if ( function_exists( 'register_block_type' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-absolute-reviews-block.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-absolute-reviews-posts-block.php';
		}

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-absolute-reviews-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-absolute-reviews-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-absolute-reviews-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-absolute-reviews-public.php';

		$this->loader = new ABR_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the ABR_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access private
	 */
	private function set_locale() {

		$plugin_i18n = new ABR_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new ABR_Admin( $this->get_abr(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_options_page' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'metabox_review_register' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'metabox_review_save', 10, 2 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'admin_enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access private
	 */
	private function define_public_hooks() {

		$plugin_public = new ABR_Public( $this->get_abr(), $this->get_version() );

		$this->loader->add_action( 'wp_head', $plugin_public, 'wp_head' );
		$this->loader->add_action( 'the_content', $plugin_public, 'the_content' );
		$this->loader->add_action( 'abr_reviews_posts_templates', $plugin_public, 'posts_templates' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'wp_enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return string The name of the plugin.
	 */
	public function get_abr() {
		return $this->abr;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return ABR_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
