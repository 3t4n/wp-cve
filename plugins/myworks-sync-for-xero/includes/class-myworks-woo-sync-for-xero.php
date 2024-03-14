<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://myworks.software/
 * @since      1.0.0
 *
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/includes
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
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/includes
 * @author     MyWorks Software <support@myworks.design>
 */
class MyWorks_WC_Xero_Sync {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MyWorks_WC_Xero_Sync_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		
		if ( defined( 'MW_WC_XERO_SYNC_PLUGIN_VERSION' ) ) {
			$this->version = MW_WC_XERO_SYNC_PLUGIN_VERSION;
		}
		
		if ( defined( 'MW_WC_XERO_SYNC_PLUGIN_NAME' ) ) {
			$this->plugin_name = MW_WC_XERO_SYNC_PLUGIN_NAME;
		}		
		
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
	 * - MyWorks_WC_Xero_Sync_Loader. Orchestrates the hooks of the plugin.
	 * - MyWorks_WC_Xero_Sync_i18n. Defines internationalization functionality.
	 * - MyWorks_WC_Xero_Sync_Admin. Defines all hooks for the admin area.
	 * - MyWorks_WC_Xero_Sync_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		#Plugin Lib
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lib.php';		
		require_once plugin_dir_path( __FILE__ ) . 'class-functions/class-wc-data-list.php';
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-myworks-woo-sync-for-xero-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-myworks-woo-sync-for-xero-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-myworks-woo-sync-for-xero-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-myworks-woo-sync-for-xero-public.php';

		$this->loader = new MyWorks_WC_Xero_Sync_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MyWorks_WC_Xero_Sync_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MyWorks_WC_Xero_Sync_i18n();

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

		$plugin_admin = new MyWorks_WC_Xero_Sync_Admin( $this->get_plugin_name(), $this->get_version() );
		
		# Hooks
		# Init 
		$this->loader->add_action( 'init', $plugin_admin, 'mwxs_hook_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'mwxs_hook_admin_init' );
		
		# Css / Js
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		# Plugin main menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'create_admin_menus' );

		# Order Sync
		#$this->loader->add_action( 'woocommerce_thankyou', $plugin_admin, 'hook_order_add' );
		$this->loader->add_action( 'woocommerce_new_order', $plugin_admin, 'hook_order_add' );

		# Setting -> Automatically sync orders when they reach any of these statuses 
		$s_order_when_status_in = get_option('mw_wc_xero_sync_s_order_when_status_in');
		if(!empty($s_order_when_status_in)){
			$s_order_when_status_in = trim($s_order_when_status_in);
			if($s_order_when_status_in!=''){$s_order_when_status_in = explode(',',$s_order_when_status_in);}
			if(is_array($s_order_when_status_in) && count($s_order_when_status_in)){
				foreach($s_order_when_status_in as $os){
					$os = substr($os,3);
					if(!empty($os)){
						$os_action = 'woocommerce_order_status_'.$os;
						$this->loader->add_action( $os_action, $plugin_admin, 'hook_order_add' , 10,1 );
					}						
				}
			}
		}

		# Cancel order
		$this->loader->add_action( 'woocommerce_order_status_cancelled', $plugin_admin, 'hook_order_cancelled' );

		#Refund Sync
		$this->loader->add_action( 'woocommerce_order_refunded', $plugin_admin, 'hook_refund_add' );

		# Payment Sync
		$this->loader->add_action( 'woocommerce_payment_complete', $plugin_admin, 'hook_payment_add' );

		# Product Sync
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'hook_product_add', 999, 1 );

		# Variation Sync
		$this->loader->add_action( 'woocommerce_save_product_variation', $plugin_admin, 'hook_variation_add', 999, 1 );

		#Admin Side
		if(is_admin()){
			# Order Update
			$this->loader->add_action( 'post_updated', $plugin_admin, 'hook_order_update' , 10,3 );
		}

		# Delete Hooks -> Mapping Delete
		$this->loader->add_action( 'woocommerce_delete_product_variation', $plugin_admin, 'hook_delete_variation_mapping' );
		$this->loader->add_action( 'delete_post', $plugin_admin, 'hook_delete_product_mapping' );
		$this->loader->add_action( 'wp_trash_post', $plugin_admin, 'hook_delete_product_mapping' );

		# Cron Schedules
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'hook_cron_schedules' );
		
		# Ajax Actions		
		# License Check
		add_action( 'wp_ajax_myworks_wc_xero_sync_check_license', 'myworks_wc_xero_sync_check_license');
		
		# Refresh Dashboard Graph
		add_action( 'wp_ajax_myworks_wc_xero_sync_refresh_log_chart', 'myworks_wc_xero_sync_refresh_log_chart' );
		
		#Save Xero Connection Key
		add_action( 'wp_ajax_myworks_wc_xero_sync_save_xero_c_key', 'myworks_wc_xero_sync_save_xero_c_key' );
		
		# Quick Refresh Xero Customers & Products
		add_action( 'wp_ajax_myworks_wc_xero_sync_quick_refresh_cp', 'myworks_wc_xero_sync_quick_refresh_cp' );
		add_action( 'wp_ajax_myworks_wc_xero_sync_quick_refresh_customers', 'myworks_wc_xero_sync_quick_refresh_customers' );
		add_action( 'wp_ajax_myworks_wc_xero_sync_quick_refresh_products', 'myworks_wc_xero_sync_quick_refresh_products' );
		
		# Clear Mappings
		add_action( 'wp_ajax_myworks_wc_xero_sync_clear_all_mappings', 'myworks_wc_xero_sync_clear_all_mappings' );
		add_action( 'wp_ajax_myworks_wc_xero_sync_clear_customer_mappings', 'myworks_wc_xero_sync_clear_customer_mappings' );
		add_action( 'wp_ajax_myworks_wc_xero_sync_clear_product_mappings', 'myworks_wc_xero_sync_clear_product_mappings' );
		add_action( 'wp_ajax_myworks_wc_xero_sync_clear_variation_mappings', 'myworks_wc_xero_sync_clear_variation_mappings' );
		
		# Clear Log
		add_action( 'wp_ajax_myworks_wc_xero_sync_clear_all_logs', 'myworks_wc_xero_sync_clear_all_logs' );
		add_action( 'wp_ajax_myworks_wc_xero_sync_clear_all_log_errors', 'myworks_wc_xero_sync_clear_all_log_errors' );
		
		# Clear Queue
		add_action( 'wp_ajax_myworks_wc_xero_sync_clear_all_pending_queues', 'myworks_wc_xero_sync_clear_all_pending_queues' );
		add_action( 'wp_ajax_myworks_wc_xero_sync_clear_all_queues', 'myworks_wc_xero_sync_clear_all_queues' );
		
		# Auto Map
		add_action( 'wp_ajax_myworks_wc_xero_sync_automap_customers_wf_xf', 'myworks_wc_xero_sync_automap_customers_wf_xf' );
		add_action( 'wp_ajax_myworks_wc_xero_sync_automap_products_wf_xf', 'myworks_wc_xero_sync_automap_products_wf_xf' );
		add_action( 'wp_ajax_myworks_wc_xero_sync_automap_variations_wf_xf', 'myworks_wc_xero_sync_automap_variations_wf_xf' );
		
		# Sync Window
		add_action( 'wp_ajax_myworks_wc_xero_sync_window', 'myworks_wc_xero_sync_window' );
		
		# Order Sync Status List
		add_action( 'wp_ajax_myworks_wc_xero_sync_order_sync_status_list', 'myworks_wc_xero_sync_order_sync_status_list' );
		
	}
	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new MyWorks_WC_Xero_Sync_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_action( 'init', $plugin_public, 'public_api_init' );
		$this->loader->add_filter( 'query_vars', $plugin_public, 'public_api_query_vars' );
		$this->loader->add_action( 'parse_request', $plugin_public, 'public_api_request' );

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
	
	public function get_plugin_title() {
		return $this->plugin_title;
	}
	
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    MyWorks_WC_Xero_Sync_Loader    Orchestrates the hooks of the plugin.
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
