<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/includes
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
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/includes
 * @author     ShopUp <info@shopup.lt>
 */
class Woocommerce_Shopup_Venipak_Shipping {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woocommerce_Shopup_Venipak_Shipping_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'WOOCOMMERCE_SHOPUP_VENIPAK_SHIPPING_VERSION' ) ) {
			$this->version = WOOCOMMERCE_SHOPUP_VENIPAK_SHIPPING_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woocommerce-shopup-venipak-shipping';

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
	 * - Woocommerce_Shopup_Venipak_Shipping_Loader. Orchestrates the hooks of the plugin.
	 * - Woocommerce_Shopup_Venipak_Shipping_i18n. Defines internationalization functionality.
	 * - Woocommerce_Shopup_Venipak_Shipping_Admin. Defines all hooks for the admin area.
	 * - Woocommerce_Shopup_Venipak_Shipping_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-shopup-venipak-shipping-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-shopup-venipak-shipping-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-shopup-venipak-shipping-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-shopup-venipak-shipping-admin-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-shopup-venipak-shipping-admin-order-edit.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-shopup-venipak-shipping-admin-orders-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-shopup-venipak-shipping-admin-dispatch.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-shopup-venipak-shipping-admin-product.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-shopup-venipak-shipping-admin-label.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-shopup-venipak-shipping-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-shopup-venipak-shipping-public-courier-checkout.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-shopup-venipak-shipping-public-pickup-checkout.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-shopup-venipak-shipping-public-email.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/venipak-fetch-pickups.php';

		$this->loader = new Woocommerce_Shopup_Venipak_Shipping_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woocommerce_Shopup_Venipak_Shipping_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woocommerce_Shopup_Venipak_Shipping_i18n();

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

		$plugin_admin = new Woocommerce_Shopup_Venipak_Shipping_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->settings = new Woocommerce_Shopup_Venipak_Shipping_Admin_Settings( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_menu', $this->settings, 'add_menu' );
		$this->loader->add_action( 'admin_init', $this->settings, 'register_settings' );
		$this->loader->add_action( 'admin_init', $this->settings, 'register_sections' );
		$this->loader->add_action( 'admin_init', $this->settings, 'register_fields' );

		if (
			$this->settings->get_option_by_key('shopup_venipak_shipping_field_userid') === '' ||
			$this->settings->get_option_by_key('shopup_venipak_shipping_field_username') === '' ||
			$this->settings->get_option_by_key('shopup_venipak_shipping_field_password') === ''
		) {
			return;
		}

		$plugin_admin_order_edit = new Woocommerce_Shopup_Venipak_Shipping_Admin_Order_Edit( $this->get_plugin_name(), $this->get_version(), $this->settings );
		$plugin_admin_orders_list = new Woocommerce_Shopup_Venipak_Shipping_Admin_Orders_List( $this->get_plugin_name(), $this->get_version(), $this->settings );
		$plugin_admin_dispatch = new Woocommerce_Shopup_Venipak_Shipping_Admin_Dispatch( $this->get_plugin_name(), $this->get_version(), $this->settings );
		$plugin_admin_product = new Woocommerce_Shopup_Venipak_Shipping_Admin_Product( $this->get_plugin_name(), $this->get_version());
		$plugin_admin_label = new Woocommerce_Shopup_Venipak_Shipping_Admin_Label( $this->get_plugin_name(), $this->get_version(), $this->settings );

		$this->loader->add_action( 'woocommerce_shipping_init', $plugin_admin, 'shopup_venipak_shipping_methods_init' );
		$this->loader->add_action( 'woocommerce_shipping_methods', $plugin_admin, 'add_shopup_venipak_shipping_methods' );

		$this->loader->add_action( 'woocommerce_admin_order_data_after_order_details', $plugin_admin_order_edit, 'add_venipak_shipping_order_edit' );
		$this->loader->add_action( 'woocommerce_process_shop_order_meta', $plugin_admin_order_edit, 'add_venipak_shipping_order_save' );

		$this->loader->add_action( 'bulk_actions-edit-shop_order', $plugin_admin_orders_list, 'add_venipak_shipping_bulk_action' );
		$this->loader->add_action( 'bulk_actions-woocommerce_page_wc-orders', $plugin_admin_orders_list, 'add_venipak_shipping_bulk_action' );



		$this->loader->add_action( 'admin_notices', $plugin_admin_orders_list, 'add_venipak_shipping_bulk_admin_notice' );

		$this->loader->add_action( 'manage_edit-shop_order_columns', $plugin_admin_orders_list, 'add_venipak_shipping_orders_list_columns', 20 );
		$this->loader->add_action( 'manage_shop_order_posts_custom_column', $plugin_admin_orders_list, 'add_venipak_shipping_orders_list_columns_content', 20, 2 );
		$this->loader->add_action( 'manage_woocommerce_page_wc-orders_columns', $plugin_admin_orders_list, 'add_venipak_shipping_orders_list_columns', 20 );
		$this->loader->add_action( 'manage_woocommerce_page_wc-orders_custom_column', $plugin_admin_orders_list, 'add_venipak_shipping_orders_list_columns_content', 20, 2 );


		$this->loader->add_action( 'wp_ajax_woocommerce_shopup_venipak_shipping_dispatch', $plugin_admin_dispatch, 'add_venipak_shipping_dispatch' );
		$this->loader->add_action( 'wp_ajax_woocommerce_shopup_venipak_shipping_dispatch_force', $plugin_admin_dispatch, 'add_venipak_shipping_dispatch_force' );
		$this->loader->add_action( 'handle_bulk_actions-edit-shop_order', $plugin_admin_dispatch, 'add_venipak_shipping_bulk_action_process', 10, 3  );
		$this->loader->add_action( 'handle_bulk_actions-woocommerce_page_wc-orders', $plugin_admin_dispatch, 'add_venipak_shipping_bulk_action_process', 10, 3  );

		$this->loader->add_action( 'woocommerce_product_options_shipping', $plugin_admin_product, 'add_venipak_shipping_options', 10, 0 );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin_product, 'save_venipak_shipping_options', 10, 1 );

		$this->loader->add_action( 'wp_ajax_woocommerce_shopup_venipak_shipping_get_label_pdf', $plugin_admin_label, 'get_label_pdf' );
		$this->loader->add_action( 'wp_ajax_woocommerce_shopup_venipak_shipping_get_manifest_pdf', $plugin_admin_label, 'get_manifest_pdf' );
		$this->loader->add_action( 'handle_bulk_actions-edit-shop_order', $plugin_admin_label, 'add_venipak_shipping_bulk_action_process', 10, 3  );
		$this->loader->add_action( 'handle_bulk_actions-woocommerce_page_wc-orders', $plugin_admin_label, 'add_venipak_shipping_bulk_action_process', 10, 3  );

		$this->loader->add_action( 'admin_notices', $plugin_admin_label, 'add_venipak_shipping_bulk_admin_notice' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woocommerce_Shopup_Venipak_Shipping_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->settings = new Woocommerce_Shopup_Venipak_Shipping_Admin_Settings( $this->get_plugin_name(), $this->get_version() );

		if (
			$this->settings->get_option_by_key('shopup_venipak_shipping_field_userid') === '' ||
			$this->settings->get_option_by_key('shopup_venipak_shipping_field_username') === '' ||
			$this->settings->get_option_by_key('shopup_venipak_shipping_field_password') === ''
		) {
			return;
		}

		$plugin_public_courier_checkout = new Woocommerce_Shopup_Venipak_Shipping_Public_Courier_Checkout( $this->get_plugin_name(), $this->get_version(), $this->settings );
		$plugin_public_pickup_checkout = new Woocommerce_Shopup_Venipak_Shipping_Public_Pickup_Checkout( $this->get_plugin_name(), $this->get_version(), $this->settings );
		$plugin_public_email = new Woocommerce_Shopup_Venipak_Shipping_Public_Email( $this->get_plugin_name(), $this->get_version(), $this->settings );

		$this->loader->add_action( 'woocommerce_cart_shipping_method_full_label', $plugin_public, 'add_venipak_shipping_logo', 10, 2 );

		$this->loader->add_action( 'woocommerce_after_shipping_rate', $plugin_public_courier_checkout, 'add_venipak_shipping_courier_options', 20, 2 );
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public_courier_checkout, 'add_venipak_shipping_courier_update_order_meta' );

		$this->loader->add_action( 'woocommerce_review_order_after_shipping', $plugin_public_pickup_checkout, 'add_venipak_shipping_pickup_options', 20, 2 );
		$this->loader->add_action( 'woocommerce_checkout_process', $plugin_public_pickup_checkout, 'add_venipak_shipping_pickup_checkout_process' );
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public_pickup_checkout, 'add_venipak_shipping_pickup_update_order_meta' );
		$this->loader->add_action( 'wp_ajax_woocommerce_venipak_shipping_pickup_points', $plugin_public_pickup_checkout, 'add_venipak_shipping_pickup_points' );
		$this->loader->add_action( 'wp_ajax_nopriv_woocommerce_venipak_shipping_pickup_points', $plugin_public_pickup_checkout, 'add_venipak_shipping_pickup_points' );
		$this->loader->add_action( 'wp_ajax_woocommerce_venipak_shipping_checkout_settings', $plugin_public_pickup_checkout, 'add_venipak_shipping_checkout_settings' );
		$this->loader->add_action( 'wp_ajax_nopriv_woocommerce_venipak_shipping_checkout_settings', $plugin_public_pickup_checkout, 'add_venipak_shipping_checkout_settings' );

		$this->loader->add_action( 'woocommerce_email_before_order_table', $plugin_public_email, 'add_venipak_shipping_tracking_number', 10, 4 );
		$this->loader->add_action( 'woocommerce_email_after_order_table', $plugin_public_email, 'add_venipak_shipping_selected_pickup_info', 10, 4 );
		$this->loader->add_action( 'woocommerce_after_checkout_validation', $plugin_public_pickup_checkout, 'validate_cod', 9999, 2 );
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
	 * @return    Woocommerce_Shopup_Venipak_Shipping_Loader    Orchestrates the hooks of the plugin.
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
