<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://cargus.ro/
 * @since      1.0.0
 *
 * @package    Cargus
 * @subpackage Cargus/includes
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
 * @package    Cargus
 * @subpackage Cargus/includes
 * @author     Cargus <contact@cargus.ro>
 */
class Cargus {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cargus_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'CARGUS_VERSION' ) ) {
			$this->version = CARGUS_VERSION;
		} else {
			$this->version = '1.4.2';
		}
		$this->plugin_name = 'cargus';

		$this->load_dependencies();
		$this->set_locale();
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			$this->define_admin_hooks();
			$this->define_public_hooks();
			$this->define_carguschrommodule_hooks();
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cargus_Loader. Orchestrates the hooks of the plugin.
	 * - Cargus_i18n. Defines internationalization functionality.
	 * - Cargus_Admin. Defines all hooks for the admin area.
	 * - Cargus_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cargus-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cargus-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cargus-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cargus-public.php';

		/**
		 * The class responsible loading the pudo locations.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cargus-cron.php';

		$this->loader = new Cargus_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cargus_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Cargus_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Cargus_Admin( $this->get_plugin_name(), $this->get_version() );

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

			// cargus enqueue admin scripts.
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'cargus_enqueue_scripts' );

			// cargus standard shipping method.
			$this->loader->add_action( 'woocommerce_shipping_init', $plugin_admin, 'cargus_shipping_method' );
			$this->loader->add_action( 'woocommerce_shipping_methods', $plugin_admin, 'add_cargus_shipping_method' );

			// cargus ship and go shipping method.
			$this->loader->add_action( 'woocommerce_shipping_init', $plugin_admin, 'cargus_ship_and_go_shipping' );
			$this->loader->add_filter( 'woocommerce_shipping_methods', $plugin_admin, 'cargus_add_ship_and_go_sipping' );
			$this->loader->add_action( 'woocommerce_after_checkout_validation', $plugin_admin, 'cargus_validate_cart', 10, 2 );

			// cargus saturday shipping method.
			$this->loader->add_action( 'woocommerce_shipping_init', $plugin_admin, 'cargus_saturday_shipping_method' );
			$this->loader->add_action( 'woocommerce_shipping_methods', $plugin_admin, 'add_cargus_saturday_shipping_method' );

			// cargus pre10 shipping method.
			$this->loader->add_action( 'woocommerce_shipping_init', $plugin_admin, 'cargus_pre10_shipping_method' );
			$this->loader->add_action( 'woocommerce_shipping_methods', $plugin_admin, 'add_cargus_pre10_shipping_method' );

			// cargus pre12 shipping method.
			$this->loader->add_action( 'woocommerce_shipping_init', $plugin_admin, 'cargus_pre12_shipping_method' );
			$this->loader->add_action( 'woocommerce_shipping_methods', $plugin_admin, 'add_cargus_pre12_shipping_method' );

			// cargus ship and go payment gateway.
			$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'cargus_ship_and_go_payment' );
			$this->loader->add_filter( 'woocommerce_payment_gateways', $plugin_admin, 'cargus_add_ship_and_go_gateway_class' );
			$this->loader->add_action( 'woocommerce_checkout_process', $plugin_admin, 'cargus_process_ship_and_go_gateway_class' );
			$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_admin, 'cargus_ship_and_go_payment_update_order_meta' );
			$this->loader->add_action( 'woocommerce_admin_order_data_after_billing_address', $plugin_admin, 'cargus_ship_and_go_checkout_field_display_admin_order_meta', 10, 1 );

			// admin order functions.
			$this->loader->add_action( 'woocommerce_checkout_create_order', $plugin_admin, 'cargus_before_checkout_create_order', 20, 2 );

			// ajax save selected pudo locations.
			$this->loader->add_action( 'wp_ajax_cargus_get_location_id', $plugin_admin, 'cargus_ajax_store_selected_box' );
			$this->loader->add_action( 'wp_ajax_nopriv_cargus_get_location_id', $plugin_admin, 'cargus_ajax_store_selected_box' );

			// woocommerce orders bulk actions.
			$this->loader->add_filter( 'bulk_actions-edit-shop_order', $plugin_admin, 'cargus_bulk_actions_add_awb_option', 20, 1 );
			$this->loader->add_filter( 'handle_bulk_actions-edit-shop_order', $plugin_admin, 'cargus_generate_awb_bulk', 10, 3 );
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'cargus_generate_awb_bulk_notice' );
			$this->loader->add_filter( 'handle_bulk_actions-edit-shop_order', $plugin_admin, 'cargus_delete_awb_bulk', 10, 3 );
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'cargus_delete_awb_bulk_notice' );
			$this->loader->add_filter( 'handle_bulk_actions-edit-shop_order', $plugin_admin, 'cargus_print_awb_bulk', 10, 3 );
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'cargus_print_awb_bulk_notice' );

			// cargus woocommerce order actions.
			$this->loader->add_action( 'admin_init', $plugin_admin, 'cargus_print_awbs', 20 );
			$this->loader->add_action( 'save_post', $plugin_admin, 'cargus_order_admin_actions', 10, 3 );

			// woocommerce order custom meta fields.
			$this->loader->add_action( 'admin_init', $plugin_admin, 'cargus_order_admin_add_metabox', 20 );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'cargus_order_admin_add_side_metabox', 20 );

			// hide shipping rates depending on the day.
			$this->loader->add_action( 'woocommerce_package_rates', $plugin_admin, 'cargus_hide_shipping_rates', 100, 2 );
			$this->loader->add_action( 'woocommerce_checkout_update_order_review', $plugin_admin, 'cargus_condition_additional_shipping_methods', 10, 1 );

			// create the rest route for exporting the orders to the shippingmanager platform.
			$this->loader->add_action( 'init', $plugin_admin, 'cargus_shippingmanager_register_rest_api_route' );
		}
	}

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Cargus_Public( $this->get_plugin_name(), $this->get_version() );

		// cargus enqueue public scripts.
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'cargus_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'cargus_enqueue_scripts' );

		// ajax get cargus regions.
		$this->loader->add_action( 'wp_ajax_cargus_get_regions', $plugin_public, 'cargus_get_cities' );
		$this->loader->add_action( 'wp_ajax_nopriv_cargus_get_regions', $plugin_public, 'cargus_get_cities' );
		$this->loader->add_action( 'wp_ajax_cargus_get_streets', $plugin_public, 'cargus_get_streets' );
		$this->loader->add_action( 'wp_ajax_nopriv_cargus_get_streets', $plugin_public, 'cargus_get_streets' );

		// display ship and go map button.
		$this->loader->add_action( 'woocommerce_cart_totals_before_order_total', $plugin_public, 'cargus_display_map_button' );
		$this->loader->add_action( 'woocommerce_review_order_before_order_total', $plugin_public, 'cargus_display_map_button' );

		// display ship and go map lockers map.
		$this->loader->add_action( 'wp_footer', $plugin_public, 'cargus_display_map', 999 );

		// add ship and go point details.
		$this->loader->add_action( 'woocommerce_after_order_notes', $plugin_public, 'cargus_map_hidden_details' );

		// display ship and go email banner.
		$this->loader->add_action( 'woocommerce_email_after_order_table', $plugin_public, 'cargus_customize_email_banner', 999, 2 );

		// display ramburs la ship and go email info.
		$this->loader->add_action( 'woocommerce_email_order_details', $plugin_public, 'cargus_customize_email_ramburs_payment', 10, 2 );

		// display cargus ship and go locker on thank you page.
		$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'cargus_add_point_name_on_checkout' );

		// display cargus ship and go locker on email body.
		$this->loader->add_action( 'woocommerce_email_order_details', $plugin_public, 'cargus_customize_email_ship_and_go_location', 20 );

		// change checkout fields order.
		$this->loader->add_filter( 'woocommerce_default_address_fields', $plugin_public, 'cargus_reorder_checkout_fields', 999, 1 );
		$this->loader->add_filter( 'woocommerce_checkout_fields', $plugin_public, 'cargus_checkout_disable_required', 999, 1 );
		$this->loader->add_filter( 'woocommerce_save_account_details', $plugin_public, 'cargus_checkout_fields_meta_save', 999, 1 );
		$this->loader->add_filter( 'woocommerce_billing_fields', $plugin_public, 'cargus_load_default_data_billing_fields', 999, 1 );
		$this->loader->add_filter( 'woocommerce_shipping_fields', $plugin_public, 'cargus_load_default_data_shipping_fields', 999, 1 );
		$this->loader->add_filter( 'woocommerce_checkout_update_order_review', $plugin_public, 'cargus_checkout_update_order_review' );

		// disable cargus shipping methods and payment gateway if client outside RO.
		$this->loader->add_filter( 'woocommerce_available_payment_gateways', $plugin_public, 'cargus_disable_payment_gateway' );

		// make default shipping state to none if client didn't set one.
		$this->loader->add_filter( 'default_checkout_billing_state', $plugin_public, 'change_default_checkout_state' );
		$this->loader->add_filter( 'default_checkout_shipping_state', $plugin_public, 'change_default_checkout_state' );

		//view order page hooks.
		$this->loader->add_action( 'woocommerce_view_order', $plugin_public, 'display_ship_and_go_point' );
		$this->loader->add_action( 'woocommerce_view_order', $plugin_public, 'display_return_awb_code' );
		$this->loader->add_action( 'woocommerce_view_order', $plugin_public, 'display_return_awb_code_qr' );
		$this->loader->add_action( 'woocommerce_view_order', $plugin_public, 'display_cargus_shipment_status' );

		//set saturday delivery session variable TO DO.
//		$this->loader->add_action( 'wp_ajax_cargus_saturday_delivery', $plugin_public, 'cargus_set_saturday_delivery' );
//		$this->loader->add_action( 'wp_ajax_nopriv_cargus_saturday_delivery', $plugin_public, 'cargus_set_saturday_delivery' );

		//get pre12 and pre10 delivery value.
		$this->loader->add_action( 'wp_ajax_cargus_pre_delivery', $plugin_public, 'cargus_ajax_get_additional_delivery' );
		$this->loader->add_action( 'wp_ajax_nopriv_cargus_pre_delivery', $plugin_public, 'cargus_ajax_get_additional_delivery' );
	}

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_carguschrommodule_hooks() {

		$locations_cron_module = new Cargus_Cron( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $locations_cron_module, 'cargus_schedulle_cron', 999 );

		// execute the function "cargus_load_pudo_points" when the action "cargus_get_ship_and_go_locations" is launched.
		$this->loader->add_action( 'cargus_get_ship_and_go_locations', $locations_cron_module, 'cargus_load_pudo_points' );
		$this->loader->add_action( 'cargus_get_ship_and_go_locations_initial_sync', $locations_cron_module, 'cargus_load_pudo_points' );

		// execute the function "cargus_load_counties" when the action "cargus_get_ship_and_go_locations" is launched.
		$this->loader->add_action( 'cargus_get_counties_initial_sync', $locations_cron_module, 'cargus_load_counties' );

		// refresh the login token.
		$this->loader->add_action( 'admin_init', $locations_cron_module, 'cargus_refresh_login_token_action_hook', 999 );
		$this->loader->add_action( 'cargus_refresh_login_token_action', $locations_cron_module, 'cargus_refresh_login_token' );
	}


	/**
	 * Run the loader to execute all the hooks with WordPress.
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
	 * @return    Cargus_Loader    Orchestrates the hooks of the plugin.
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
