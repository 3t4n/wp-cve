<?php
namespace PargoWp\Includes;

use PargoWp\Includes\Pargo_Activator;
use PargoWp\Includes\Pargo_Deactivator;
use PargoWp\Includes\Pargo_i18n;
use PargoWp\Includes\Pargo_Loader;

use PargoWp\PargoAdmin\Pargo_Admin;
use PargoWp\PargoAdmin\Pargo_Admin_API;
use PargoWp\PargoPublic\Pargo_Public;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       pargo.co.za
 * @since      1.0.0
 *
 * @package    Pargo
 * @subpackage Pargo/includes
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
 * @package    Pargo
 * @subpackage Pargo/includes
 * @author     Pargo <support@pargo.co.za>
 */
class Pargo {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pargo_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'PARGO_VERSION' ) ) {
			$this->version = PARGO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pargo';

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
	 * - Pargo_Loader. Orchestrates the hooks of the plugin.
	 * - Pargo_i18n. Defines internationalization functionality.
	 * - Pargo_Admin. Defines all hooks for the admin area.
	 * - Pargo_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$this->loader = new Pargo_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pargo_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Pargo_i18n();

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
		$plugin_admin = new Pargo_Admin( $this->get_plugin_name(), $this->get_version() );
		$pargo_api = new Pargo_Admin_API( $this->get_plugin_name(), $this->get_version() );
		// Global Filters
		$this->loader->add_filter( 'woocommerce_shipping_methods', $plugin_admin, 'add_pargo_pick_up_shipping_method' );
		$this->loader->add_filter( 'woocommerce_order_data_store_cpt_get_orders_query', $plugin_admin,'handle_order_pargo_waybill_query_var', 10 ,2);
		// Global Actions
		$this->loader->add_action( 'woocommerce_shipping_init', $plugin_admin, 'pargo_shipping_method' );
		$this->loader->add_action( 'rest_api_init', $pargo_api, 'register_routes' );
		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'create_css_folder' );
		// return void if the user is not on the admin page
		if (!is_admin()) return;
		// Filters
		$this->loader->add_filter( 'script_loader_tag', $plugin_admin, 'add_module_script', 10, 3);
		$this->loader->add_filter( 'woocommerce_admin_billing_fields', $plugin_admin, 'pargo_admin_billing_fields' );
		$this->loader->add_filter( 'woocommerce_admin_shipping_fields', $plugin_admin, 'pargo_admin_shipping_fields' );
		// Actions
        $this->loader->add_action( 'wp_ajax_woocommerce_shipping_zone_methods_save_settings', $plugin_admin,'save_shipping_zone_analytics_data', 10, 2 );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'pargo_wp_init_menu' );
		$this->loader->add_action( 'woocommerce_admin_order_data_after_billing_address', $plugin_admin, 'pargo_checkout_field_display_admin_order_meta', 10, 1 );
		$this->loader->add_action( 'save_post', $plugin_admin, 'update_post_action', 999, 3);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
        $plugin_public = new Pargo_Public( $this->get_plugin_name(), $this->get_version() );

        //Global Actions
        $this->loader->add_action( 'wp_ajax_set_pick_up_point', $plugin_public, 'set_pargo_pickup_point' );
		$this->loader->add_action( 'wp_ajax_nopriv_set_pick_up_point', $plugin_public, 'set_pargo_pickup_point' );
        $this->loader->add_action( 'woocommerce_order_status_changed', $plugin_public, 'place_pargo_order' );
		$this->loader->add_action( 'wc_ajax_update_shipping_method', $plugin_public, 'event_shipping_method_selected_cart');
		$this->loader->add_action( 'wc_ajax_nopriv_update_shipping_method', $plugin_public, 'event_shipping_method_selected_cart');
		$this->loader->add_action( 'wc_ajax_update_order_review', $plugin_public, 'event_shipping_method_selected_checkout');
		$this->loader->add_action( 'wc_ajax_nopriv_update_order_review', $plugin_public, 'event_shipping_method_selected_checkout');

		$this->loader->add_filter( 'woocommerce_default_address_fields', $plugin_public, 'pargo_default_address_fields' );
		$this->loader->add_filter( 'woocommerce_my_account_my_address_formatted_address', $plugin_public, 'pargo_account_suburb_address_field', 10, 3 );
		$this->loader->add_filter( 'woocommerce_order_formatted_billing_address', $plugin_public, 'pargo_order_suburb_billing_address_field', 10, 2 );
		$this->loader->add_filter( 'woocommerce_order_formatted_shipping_address', $plugin_public, 'pargo_order_suburb_shipping_address_field', 10, 2 );
		$this->loader->add_filter( 'woocommerce_formatted_address_replacements', $plugin_public, 'pargo_formatted_address_replacements', 10, 2 );
		$this->loader->add_filter( 'woocommerce_localisation_address_formats', $plugin_public, 'pargo_localisation_address_formats', 10, 1 );
        // return void if the user is on the admin page
		if (is_admin()) return;

		// Filters
		$this->loader->add_filter( 'woocommerce_cart_shipping_method_full_label', $plugin_public, 'pargo_label_change', 10, 2 );
		$this->loader->add_filter( 'woocommerce_checkout_fields' , $plugin_public, 'pargo_checkout_fields' );
		$this->loader->add_filter( 'woocommerce_is_rest_api_request', $plugin_public, 'simulate_as_not_rest' );
		$this->loader->add_filter( 'woocommerce_package_rates', $plugin_public, 'pargo_hide_shipping_based_on_order_weight', 10, 2 );
		// Actions
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_checkout_process', $plugin_public, 'validate_pargo_fields' );
		$this->loader->add_action( 'rest_api_init', $plugin_public, 'register_public_routes' );
		$this->loader->add_action( 'woocommerce_before_cart_totals', $plugin_public, 'display_selected_pickup_point' );
		$this->loader->add_action( 'woocommerce_review_order_before_submit', $plugin_public, 'display_selected_pickup_point' );
		$this->loader->add_action( 'woocommerce_new_order', $plugin_public, 'place_pargo_order' );
		$this->loader->add_action( 'woocommerce_after_checkout_validation', $plugin_public, 'pargo_after_checkout_validation' );
		$this->loader->add_action( 'woocommerce_order_status_completed', $plugin_public, 'pargo_order_status_completed' );
		$this->loader->add_action( 'woocommerce_order_details_after_customer_details', $plugin_public, 'account_order_details' );

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
	 * @return    Pargo_Loader    Orchestrates the hooks of the plugin.
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
