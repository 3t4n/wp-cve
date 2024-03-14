<?php
if (!defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       Epaka.pl
 * @since      1.0.0
 *
 * @package    Epaka
 * @subpackage Epaka/includes
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
 * @package    Epaka
 * @subpackage Epaka/includes
 * @author     Epaka <bok@epaka.pl>
 */
class Epaka {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Epaka_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'EPAKA_VERSION' ) ) {
			$this->version = EPAKA_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'epakapl';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_api_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Epaka_Loader. Orchestrates the hooks of the plugin.
	 * - Epaka_i18n. Defines internationalization functionality.
	 * - Epaka_Admin. Defines all hooks for the admin area.
	 * - Epaka_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-epaka-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-epaka-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-epaka-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-epaka-utils.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-epaka-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'api/class-epaka-api.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'api/class-epaka-api-controller.php';

		$this->loader = new Epaka_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Epaka_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Epaka_i18n();

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

		$plugin_admin = new Epaka_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'epaka_admin_menu' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'epaka_admin_actions' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'epaka_register_metaboxes' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_filter( 'manage_edit-shop_order_columns', $plugin_admin, 'edit_order_column');
		$this->loader->add_filter( 'manage_shop_order_posts_custom_column', $plugin_admin, 'order_column_custom_content');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Epaka_Public( $this->get_plugin_name(), $this->get_version());

		// $this->loader->add_filter( 'woocommerce_review_order_before_submit', $plugin_public, 'custom_ovveride_checkout_shipping' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'epaka_update_custom_meta',10,2);
		$this->loader->add_action( 'woocommerce_after_shipping_rate', $plugin_public, 'epaka_after_shipping_rate',10,2 );
		$this->loader->add_action( 'woocommerce_before_checkout_form', $plugin_public, 'epaka_map_checkout_code');
		$this->loader->add_filter( 'woocommerce_order_details_after_order_table', $plugin_public, 'epaka_custom_order_thankyou_fields' );
		$this->loader->add_action( 'woocommerce_after_checkout_validation', $plugin_public, 'epaka_validate_delivery_point', 10, 2);
	}

	/**
	 * Register all of the hooks related to the api-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_api_hooks() {
		$plugin_api = new Epaka_Api($this->get_plugin_name(), $this->get_version());
		// $plugin_api_jwt = new Epaka_JWT_Auth($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action( 'rest_api_init', $plugin_api, 'rest_api_routes' );
		// $this->loader->add_filter('rest_api_init', $plugin_api_jwt, 'add_cors_support');
        // $this->loader->add_filter('rest_pre_dispatch', $plugin_api_jwt, 'rest_pre_dispatch', 10, 2);
        // $this->loader->add_filter('determine_current_user', $plugin_api_jwt, 'determine_current_user', 10);
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
	 * @return    Epaka_Loader    Orchestrates the hooks of the plugin.
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
