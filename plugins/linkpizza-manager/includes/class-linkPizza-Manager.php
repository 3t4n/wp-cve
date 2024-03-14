<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://linkpizza.com
 * @since      1.0.0
 *
 * @package    linkPizza-manager
 * @subpackage linkPizza-manager/includes
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
 * @package    linkPizza-manager
 * @subpackage linkPizza-manager/includes
 */
class linkPizza_Manager {

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
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'linkPizza-Manager';
		$this->version     = '5.1.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_widget_hooks();
		$this->define_shortcode_hooks();
		$this->define_jobs();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the dashboard.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-linkPizza-Manager-loader.php';

		/**
		 * The Class responsible for updating
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-linkPizza-Manager-updater.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-linkPizza-Manager-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-linkPizza-Manager-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the Widget.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widget/class-linkPizza-Manager-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widget/class-linkPizza-Manager-managed-widget.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-linkPizza-Manager-public.php';

		/**
		 * The classes responsible for handling Oauth communication with LinkPizza
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/OAuth2/Client.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/OAuth2/GrantType/IGrantType.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/OAuth2/GrantType/AuthorizationCode.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/OAuth2/GrantType/RefreshToken.php';

		/**
		 * The Class responsible for Oauth communications
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'shared/class-linkPizza-Manager-api-calls.php';

		/**
		 * The Class responsible for jobs
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-linkPizza-Manager-jobs.php';

		/**
		 * The Class responsible for shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'shortcode/class-linkPizza-Manager-shortcode.php';

		$this->loader = new linkPizza_Manager_Loader();

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

		$plugin_i18n = new linkPizza_Manager_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin   = new linkPizza_Manager_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_updater = new linkPizza_Manager_Updater( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_admin, 'pzz_check_buttons_pressed' );
		$this->loader->add_action( 'admin_post_pzz_logout', $plugin_admin, 'handle_logout' );
		$this->loader->add_action( 'init', $plugin_admin, 'handle_openid_callback' );
		$this->loader->add_filter( 'ppp_nonce_life', $plugin_admin, 'pzz_increase_preview_nonce' );
		$this->loader->add_action( 'admin_init', $plugin_updater, 'pzz_upgrade_plugin' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'pzz_add_admin_menu' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'pzz_settings_init' );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'pzz_render_posts_pages_list_column', 10, 2 );
		$this->loader->add_action( 'manage_pages_custom_column', $plugin_admin, 'pzz_render_posts_pages_list_column', 10, 2 );
		// Fire our meta box setup function on the post editor screen.
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'linkpizza_add_post_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_enqueue_style' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'pzz_admin_notice' );
		$this->loader->add_filter( 'manage_post_posts_columns', $plugin_admin, 'pzz_add_posts_pages_list_column' );
		$this->loader->add_filter( 'manage_pages_columns', $plugin_admin, 'pzz_add_posts_pages_list_column' );
		$this->loader->add_filter( 'hidden_meta_boxes', $plugin_admin, 'custom_hidden_meta_boxes' );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'plugin_row_meta', 10, 2 );
		// If WordPress version is higher than 4.6 because the filter only was added in 4.7.
		if ( version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
			$this->loader->add_filter( 'bulk_actions-edit-post', $plugin_admin, 'register_pzz_bulk_actions' );
			$this->loader->add_filter( 'handle_bulk_actions-edit-post', $plugin_admin, 'pzz_bulk_action_handler', 10, 3 );
			$this->loader->add_filter( 'bulk_actions-edit-page', $plugin_admin, 'register_pzz_bulk_actions' );
			$this->loader->add_filter( 'handle_bulk_actions-edit-page', $plugin_admin, 'pzz_bulk_action_handler', 10, 3 );
		}
		// Change admin title for option page tabs.
		$this->loader->add_action( 'admin_title', $plugin_admin, 'set_admin_title' );
	}


	/**
	 * Register all of the hooks related to widget
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_widget_hooks() {

		$plugin_widget         = new LinkPizza_Manager_Widget( $this->get_plugin_name(), $this->get_version() );
		$plugin_managed_widget = new LinkPizza_Manager_Managed_Widget( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'widgets_init', $plugin_widget, 'register_widget' );
		$this->loader->add_action( 'widgets_init', $plugin_managed_widget, 'register_widget' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new linkPizza_Manager_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'add_linkpizza_head' );
		$this->loader->add_filter( 'body_class', $plugin_public, 'add_linkpizza_body_classes' );
		$this->loader->add_filter( 'the_content', $plugin_public, 'pzz_add_link_summary' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    4.9.6
	 * @access   private
	 */
	private function define_shortcode_hooks() {

		$plugin_shortcode = new LinkPizza_Manager_shortcode( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_shortcode, 'pzz_register_shortcodes' );
	}

	/**
	 * Define cron jobs.
	 *
	 * @since 5.5.2
	 * @return void
	 */
	private function define_jobs() {
		$plugin_jobs = new LinkPizza_Manager_Jobs( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_jobs, 'init_hooks' );
		$this->loader->add_action( 'init', $plugin_jobs, 'schedule' );
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
