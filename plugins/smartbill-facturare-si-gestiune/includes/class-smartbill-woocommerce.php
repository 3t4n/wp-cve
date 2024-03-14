<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.smartbill.ro
 * @since      1.0.0
 *
 * @copyright  Intelligent IT SRL 2018
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
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
 * @copyright  Intelligent IT SRL 2018
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 * @author     Intelligent IT SRL <vreauapi@smartbill.ro>
 */
class Smartbill_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Smartbill_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'smartbill-woocommerce';

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
	 * - Smartbill_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Smartbill_Woocommerce_I18n. Defines internationalization functionality.
	 * - Smartbill_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Smartbill_Woocommerce_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smartbill-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smartbill-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-smartbill-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-smartbill-woocommerce-public.php';

		/**
		 * Clasa responsabila pentru apelurile catre SmartBill Cloud
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smartbill-cloud-rest-client.php';
		/**
		 * Class responsible for creating the Authentication View
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-smartbill-woocommerce-admin-auth-screen.php';

		/**
		 * Class responsible for registering the settings fields
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-smartbill-woocommerce-admin-settings-fields.php';

		/**
		 * Class responsible for managing smartbill products
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smartbill-product.php';

		/**
		 * Class responsible for registering the settings fields
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smartbillutils.php';
		/**
		 * Class responsible for communicating with ANAF
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-anafapiclient.php';
		/**
		 * Functions responsible for communicating with WooCommerce
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/woocommerce-functions.php';
		/**
		 * Class responsible for storing sent / received data
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-smartbill-data-logger.php';
		/**
		 * Class responsible for stocks, measuring units etc.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-smartbill-woocommerce-settings.php';

		$this->loader = new Smartbill_Woocommerce_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Smartbill_Woocommerce_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Smartbill_Woocommerce_I18n();

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

		$plugin_admin = new Smartbill_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts', 9 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_pages' );
		$this->loader->add_action( 'admin_init', $plugin_admin->get_auth_screen(), 'register_fields' );
		$this->loader->add_action( 'admin_init', $plugin_admin->get_settings_fields(), 'register_fields' );
		$this->loader->add_action( 'woocommerce_admin_order_data_after_billing_address', $plugin_admin, 'smartbill_add_billing_fileds_in_admin_order' );
		$this->loader->add_action( 'woocommerce_admin_order_data_after_shipping_address', $plugin_admin, 'smartbill_add_shipping_fileds_in_admin_order' );
		$this->loader->add_action( 'woocommerce_process_shop_order_meta', $plugin_admin, 'smartbill_billing_order_save_fields' );
		$this->loader->add_action( 'wp_ajax_smartbill_woocommerce_sync_settings', $plugin_admin->get_settings_fields(), 'smartbill_woocommerce_sync_settings' );
		$this->loader->add_action( 'wp_ajax_smartbill_woocommerce_download_stock_history', $plugin_admin->get_settings_fields(), 'smartbill_woocommerce_download_stock_history' );
		$this->loader->add_action( 'wp_ajax_smartbill_woocommerce_manually_sync_stock', $plugin_admin->get_settings_fields(), 'smartbill_woocommerce_manually_sync_stock' );
		$this->loader->add_action( 'wp_ajax_smartbill_woocommerce_download_manual_stock_history', $plugin_admin->get_settings_fields(), 'smartbill_woocommerce_download_manual_stock_history' );
		$this->loader->add_action( 'woocommerce_view_order', $plugin_admin, 'show_public_invoice', 11 );
		$this->loader->add_action( 'woocommerce_new_order_item', $plugin_admin, 'smartbill_add_order_custom_item_meta', 10, 3 );
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'add_plugin_settings_link', 10, 2 );
		$this->loader->add_filter( 'woocommerce_hidden_order_itemmeta', $plugin_admin, 'smartbill_hide_custom_item_meta', 10, 1 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Smartbill_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$this->loader->add_action( 'woocommerce_checkout_update_user_meta', $plugin_public, 'smartbill_billing_fields_update_user_meta' );
		$this->loader->add_action( 'woocommerce_checkout_order_processed', $plugin_public, 'smartbill_billing_fields_update_order_meta' );
		$this->loader->add_filter( 'woocommerce_billing_fields', $plugin_public, 'smartbill_custom_billing_fields' );
		$this->loader->add_filter( 'woocommerce_shipping_fields', $plugin_public, 'smartbill_custom_shipping_fields' );

		$this->loader->add_filter( 'woocommerce_order_formatted_billing_address', $plugin_public, 'smartbill_formatted_billing_address', 10, 2 );
		$this->loader->add_filter( 'woocommerce_order_formatted_shipping_address', $plugin_public, 'smartbill_formatted_shipping_address', 10, 2 );
		$this->loader->add_filter( 'woocommerce_formatted_address_replacements', $plugin_public, 'smartbill_billing_fields_replacements', 10, 2 );
		$this->loader->add_filter( 'woocommerce_localisation_address_formats', $plugin_public, 'smartbill_address_formats', 10, 1 );
		$this->loader->add_filter( 'woocommerce_order_get_formatted_shipping_address', $plugin_public, 'smartbill_shipping_address_format', 10, 3 );
		$this->loader->add_filter( 'woocommerce_after_checkout_validation', $plugin_public, 'smartbill_checkout_validation', 10, 2 );
		$this->loader->add_action( 'woocommerce_after_save_address_validation', $plugin_public, 'smartbill_address_validation', 10, 0 );
		$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_public, 'smartbill_woocommerce_automatically_issue_document_by_status', 10, 3 );

		$this->loader->add_action( 'rest_api_init', $plugin_public, 'smartbill_sync_stock_route', 10, 0 );
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
	 * @return    Smartbill_Woocommerce_Loader    Orchestrates the hooks of the plugin.
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
