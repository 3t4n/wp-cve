<?php

class Wp_Disabler {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		if ( defined( 'WP_DISABLER_VERSION' ) ) {
			$this->version = WP_DISABLER_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		if ( defined( 'PLUGIN_BASENAME' ) ) {
			$this->plugin_basename = PLUGIN_BASENAME;
		} else {
			$this->plugin_basename = 'Wp Disabler/wp-disabler.php';
		}
		$this->plugin_name = 'wp_disabler';

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
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-disabler-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-disabler-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/lib/apf/admin-page-framework.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-disabler-apf.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-disabler-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wp-disabler-admin-display.php';
		

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-disabler-public.php';
		

		$this->loader = new Wp_Disabler_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Disabler_i18n();

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

		$plugin_admin = new Wp_Disabler_Admin( $this->get_plugin_name(), $this->get_version() );
		$disable_ctp_update = AdminPageFramework::getOption( 'APF_Tabs', 'disable_ctp_update' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'wpdisabler_plugin_row_meta', 10, 2 );
		$this->loader->add_filter( 'plugin_action_links_' . $this->plugin_basename, $plugin_admin, 'add_action_links' );

		$this->loader->add_action( 'current_screen', $plugin_admin, 'wpdisabler_plugin_screen' );

		if ($disable_ctp_update ==  '1') {
			remove_action('load-update-core.php','wp_update_plugins');
			remove_action('load-update-core.php','wp_update_themes');
			add_filter('pre_site_transient_update_core','__return_null');
			add_filter('pre_site_transient_update_plugins','__return_null');
			add_filter('pre_site_transient_update_themes','__return_null');
		}


	}	

	

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Disabler_Public( $this->get_plugin_name(), $this->get_version() );
		$disable_adminbar = AdminPageFramework::getOption( 'APF_Tabs', 'disable_adminbar' );
		$disable_feed = AdminPageFramework::getOption( 'APF_Tabs', 'disable_feed' );
		$disable_rest_api = AdminPageFramework::getOption( 'APF_Tabs', 'disable_rest_api' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'wpdisabler_toast_message' );

		


		if ( $disable_adminbar == '1') {
			$this->loader->add_filter( 'show_admin_bar', $plugin_public, 'hide_admin_bar' );
		}


		if ($disable_feed == '1') {

			$this->loader->add_action('do_feed', $plugin_public, 'wpd_disable_feed', 1);
			$this->loader->add_action('do_feed_rdf', $plugin_public, 'wpd_disable_feed', 1);
			$this->loader->add_action('do_feed_rss', $plugin_public, 'wpd_disable_feed', 1);
			$this->loader->add_action('do_feed_rss2', $plugin_public, 'wpd_disable_feed', 1);
			$this->loader->add_action('do_feed_atom', $plugin_public, 'wpd_disable_feed', 1);
			$this->loader->add_action('do_feed_rss2_comments', $plugin_public, 'wpd_disable_feed', 1);
			$this->loader->add_action('do_feed_atom_comments', $plugin_public, 'wpd_disable_feed', 1);
			add_action( 'feed_links_show_posts_feed',    '__return_false', -1 );
			add_action( 'feed_links_show_comments_feed', '__return_false', -1 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
			remove_action( 'wp_head', 'feed_links', 2 );

		}

		if ($disable_rest_api == '1') {
			add_filter( 'rest_endpoints', 'wpdisabler_remove_default_endpoints' );
			add_filter( 'rest_endpoints', 'wpdisabler_remove_default_endpoints_smarter' );
		}

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
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
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


