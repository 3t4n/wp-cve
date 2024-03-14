<?php

/**
 * The file that defines the core plugin class
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 * @link       https://tranzly.io
 * @since      1.0.0
 * @package    Tranzly
 * @subpackage Tranzly/includes
 */
class Tranzly {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'TRANZLY_VERSION' ) ) {
			$this->version = TRANZLY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'tranzly';

		$this->define_constants();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tranzly-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tranzly-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-tranzly-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tranzly-public.php';

		/**
		 * The class responsible for settings page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tranzly-settings-page.php';

		/**
		 * The class responsible for translating the content.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tranzly-translator.php';

		/**
		* The class responsible for truncating or splitting the content.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tranzly-truncate-html.php';

		/**
		 * The class responsible for adding post meta box.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tranzly-meta-box.php';

		/**
		 * The class responsible for translating posts.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tranzly-posts-translator.php';

		/**
		 * The helper functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helper-functions.php';

		$this->loader = new Tranzly_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 */
	private function set_locale() {

		$plugin_i18n = new Tranzly_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Define constants for this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function define_constants() {
		$this->define( 'TRANZLY_PLUGIN_PATH', plugin_dir_path( dirname( __FILE__ ) ) );
		$this->define( 'TRANZLY_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
	    $this->define('TRANZLY_SITE_URL', get_site_url());
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Tranzly_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'save_post', $plugin_admin, 'tranzly_project_updated' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'tranzly_register_custom_widget' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 */
	private function define_public_hooks() {

		$plugin_public = new Tranzly_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'add_meta_tags',1,2 );
		$this->loader->add_action( 'pre_get_posts', $plugin_public ,'tranzly_posts_custom' );

		$this->loader->add_action( 'wp_ajax_tranzly_public_tranzly_ajax', $plugin_public, 'tranzly_ajax_handaler' );
		$this->loader->add_action( 'wp_ajax_nopriv_tranzly_public_tranzly_ajax', $plugin_public, 'tranzly_ajax_handaler' );

		// add_filter //
		$this->loader->add_filter( 'the_content', $plugin_public, 'tranzly_slug_filter_the_title' );

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
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Define constants if not already defined.
	 */
	public function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

}