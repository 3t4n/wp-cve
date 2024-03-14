<?php

use platy\etsy\admin\EtsyProductTab;
use platy\etsy\admin\ProductTableColumn;
use platy\etsy\admin\AutoSyncStatus;
use platy\etsy\admin\EtsyProductEditView;
use platy\etsy\orders\OrderTableColumn;
use platy\etsy\orders\OrderTableFilter;

use platy\etsy\admin\ProductTableFilter;
use platy\etsy\EtsySyncerException;
use platy\etsy\EtsySyncer;
use platy\etsy\EtsyStockSyncer;
use platy\etsy\EtsyProductStockSyncer;

use platy\etsy\logs\PlatySyncerLogger;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       inon_kaplan
 * @since      1.0.0
 *
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/includes
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
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/includes
 * @author     Inon Kaplan <inonkp@gmail.com>
 */
class Platy_Syncer_Etsy {
	const SHOP_TABLE_NAME = "plty_etsy_shops";
	const TEMPLATES_TABLE_NAME = "plty_etsy_templates";
	const TEMPLATES_META_TABLE_NAME = "plty_etsy_templates_meta";
	const CONNECTIONS_TABLE_NAME = "plty_etsy_connections";
	const OPTIONS_TABLE_NAME = "plty_etsy_options";
	const PRODUCT_ATTRIBUTES_TABLE_NAME = "plty_etsy_product_attributes";
	const PRODUCT_TABLE_NAME = "plty_etsy_products";
	const PRODUCT_META_TABLE_NAME = "plty_etsy_products_meta";
	const LOG_TABLE_NAME = "plty_etsy_logs";

	/**
	 * 
	 *
	 * @var Platy_Syncer_Etsy_Admin
	 */
	protected $plugin_admin;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Platy_Syncer_Etsy_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * @var Platy_Syncer_Etsy_Order_Admin
	 */
	protected $order_admin;

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
		if ( defined( 'PLATY_SYNCER_ETSY_VERSION' ) ) {
			$this->version = PLATY_SYNCER_ETSY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'platy-syncer-etsy';
		$this->load_logger();
		$this->load_dependencies();
		$this->set_locale();
		$this->plugin_admin = new Platy_Syncer_Etsy_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->order_admin = new Platy_Syncer_Etsy_Order_Admin($this->get_plugin_name(), $this->get_version());
		$this->define_admin_hooks();
		$this->define_rest_hooks();
		$this->define_public_hooks();
	}

	private function load_logger(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/logs/class-platy-logger.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/logs/class-platy-syncer-logger.php';
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Platy_Syncer_Etsy_Loader. Orchestrates the hooks of the plugin.
	 * - Platy_Syncer_Etsy_i18n. Defines internationalization functionality.
	 * - Platy_Syncer_Etsy_Admin. Defines all hooks for the admin area.
	 * - Platy_Syncer_Etsy_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-platy-syncer-etsy-loader.php';
		$this->loader = new Platy_Syncer_Etsy_Loader();

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-platy-syncer-etsy-uninstaller.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-platy-syncer-etsy-i18n.php';
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/etsy/api.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

		$this->load_utils();
		$this->load_exceptions();
		$this->load_syncers();
		$this->load_models();
		$this->load_data_services();

		$this->load_oauth();

		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/rest/api.php';
		
		if(defined('DOING_AJAX') && DOING_AJAX)
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/ajax/ajax.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-platy-syncer-etsy-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/product-tab/class-etsy-product-tab.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/product-tab/class-etsy-attributes-tab-content.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/product-table/class-product-table-column.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/product-table/class-product-table-filter.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/product-edit/class-product-edit-view.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/admin-bar/class-auto-sync-status.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/logs/log-table/class-log-table.php';

		$this->load_order_dependencies();
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-platy-syncer-etsy-public.php';


	}

	private function load_data_services() {
		$this->load_recursive(plugin_dir_path(dirname( __FILE__ ) ) . "includes/data/");
	}

	private function load_models() {
		$this->loader->add_action("woocommerce_init", null, function(){
			$this->load_recursive(plugin_dir_path(dirname( __FILE__ ) ) . "includes/model/");
		});
	}

	private function load_exceptions() {
		$this->load_recursive(plugin_dir_path(dirname( __FILE__ ) ) . "includes/exceptions/");
	}

	private function load_syncers() {
		$this->load_recursive(plugin_dir_path(dirname( __FILE__ ) ) . "includes/syncers/");
	}

	private function load_stock_hooks() {
		add_action("platy_etsy_opt_group_stock_management_save", "platy\\etsy\\EtsyStockSyncer::update_stock_sync", 10, 2);

		if(!EtsyStockSyncer::is_auto_stock_managed()) {
			return;
		}

		$etsy_syncer = new EtsyStockSyncer();
		add_action("platy_etsy_stock_cron_hook", "platy\\etsy\\EtsyStockSyncer::sync_stock_from_cron");

		$this->loader->add_action('woocommerce_updated_product_stock', $etsy_syncer, 'add_to_stock_queue');
		$this->loader->add_action('updated_post_meta', $etsy_syncer, 'on_stock_meta_update', 10, 4);
		$this->loader->add_action("platy_etsy_transaction_synced", $etsy_syncer, 'remove_from_stock_queue', 10, 2);

		$this->loader->add_action("shutdown", $etsy_syncer, 'sync_stock_update_queue');
		$this->loader->add_action("woocommerce_order_status_changed", $etsy_syncer, 'log_order_status_change', 100, 3);

		if($etsy_syncer->get_option("mask_product_stock_on_view", false)) {
			$this->loader->add_action("woocommerce_before_single_product" , $etsy_syncer, 'mask_product_stock_on_view');
		}

		if($etsy_syncer->get_option("safeguard_checkout_stock", true)) {
			$this->loader->add_action('woocommerce_checkout_process', $etsy_syncer, 'safeguard_checkout_stock');
		}
		// $this->loader->add_action("save_post", $etsy_syncer, 'sync_stock_from_save', 9999);
		// $this->loader->add_action("woocommerce_ajax_save_product_variations", $etsy_syncer, 'sync_stock_from_save');
		// $this->loader->add_action("woocommerce_order_status_changed", $etsy_syncer, 'sync_stock_from_order_status_change', 100, 3);

	}

	private function load_order_dependencies(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/orders/class-platy-syncer-etsy-order-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/orders/class-etsy-carrier-list.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/orders/meta-box/class-etsy-items-meta-box.php";
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/orders/orders-table/class-order-table-column.php";
		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/orders/orders-table/class-order-table-filter.php";
	}

	private function load_utils() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/html2text/class-html-2-text.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'utils/inventory-utils/class-inventory-utils.php';
	}


	private function load_recursive($dir) {
		foreach (glob($dir . "*.php") as $filename)
		{
			
			require_once $filename;
		}

		foreach (glob($dir . "*", GLOB_ONLYDIR | GLOB_MARK) as $dirname)
		{
			if($dirname == $dir) { 
				continue;
			}
			$this->load_recursive($dirname);
		}
	}

	private function load_oauth() {
		$this->load_recursive(plugin_dir_path(dirname( __FILE__ ) ) . "includes/api/");
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Platy_Syncer_Etsy_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Platy_Syncer_Etsy_i18n();

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

		$this->load_auth_hooks();

		$this->loader->add_action("admin_enqueue_scripts", $this->plugin_admin, 'enqueue_icon_style');
		// $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		// $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action("admin_menu", $this->plugin_admin, 'add_menus');
		$this->loader->add_action( 'current_screen', $this->plugin_admin, 'set_up_product_table_scripts' );
		$this->loader->add_action( 'bulk_actions-edit-product', $this->plugin_admin, 'add_platy_bulk_action' );
		$this->loader->add_filter("handle_bulk_actions-edit-product", $this->plugin_admin, 'handle_platy_bulk_action', 10, 3);
		$this->loader->add_action("admin_notices", $this->plugin_admin, 'admin_notices');

		$this->load_product_tab_hooks();
		$this->load_product_table_hooks();
		$this->load_product_edit_hooks();

		$this->load_admin_bar_status_menu();

		$this->load_stock_hooks();
		
		$this->load_order_hooks();
		$this->loader->add_filter( 'post_updated_messages', $this->plugin_admin, 'post_updated_messages', 1000 );

	}

	function load_admin_bar_status_menu() {
		$bar = new AutoSyncStatus();
		$this->loader->add_action("admin_bar_menu", $bar, 'load_admin_bar_status_menu', 1000 ,2);

	}

	function load_auth_hooks() {
		$this->loader->add_action("platy_etsy_refresh_oauth2_token", $this->plugin_admin->get_syncer(), 'save_ouath_credentials', 10 ,2);
	}

	function load_product_edit_hooks() {
		$editor = new EtsyProductEditView();
		$this->loader->add_action('post_submitbox_start', $editor, 'render_update_and_sync_button', 10, 1 );
		$this->loader->add_action('save_post', $editor, 'sync_to_etsy', 10000, 1 );
	}

	function load_product_tab_hooks(){
		
		try {
			$product_tabs = new EtsyProductTab();
		} catch(EtsySyncerException $e) {
			return;
		}

		$this->loader->add_filter( 'woocommerce_product_data_tabs', $product_tabs, 'add_product_tab' );
        $this->loader->add_action('woocommerce_product_data_panels', $product_tabs, 'create_tab_content');
		$this->loader->add_action( 'woocommerce_process_product_meta', $product_tabs,'save_settings' );
		$this->loader->add_action( 'woocommerce_product_options_pricing', $product_tabs,'add_etsy_price_field' );
		$this->loader->add_action( 'woocommerce_variation_options_pricing', $product_tabs,'add_variation_etsy_price_field' ,1,3);
		$this->loader->add_action( 'woocommerce_variation_options', $product_tabs,'add_variation_options' ,1,3);
		$this->loader->add_action( 'woocommerce_save_product_variation', $product_tabs,'save_variation_settings',1,2);
		$this->loader->add_action( 'admin_enqueue_scripts', $product_tabs,'enqueue_attribute_scripts', 10, 1 );

	}

	function define_rest_hooks() {
		$this->loader->add_action( "rest_api_init", $this->order_admin, 'init_rest_apis');
		$this->loader->add_action("rest_api_init", $this->plugin_admin, 'init_rest_apis');

	}

	function load_order_hooks(){
		// $this->loader->add_filter( 'woocommerce_order_class', $this->order_admin, 'filter_order_class' ,5,3);
		// $this->loader->add_filter( 'woocommerce_get_order_item_classname', $this->order_admin, 'filter_order_item_class' ,10,3);
		$this->loader->add_action( 'add_meta_boxes', $this->order_admin, 'remove_meta_boxes', 40 );
		$this->loader->add_action( 'add_meta_boxes', $this->order_admin, 'add_meta_boxes', 50 );
		$this->loader->add_action( 'woocommerce_init', $this->order_admin, 'register_save_order_meta_hooks', 60 );
		$this->loader->add_filter( 'woocommerce_order_actions', $this->order_admin, 'filter_order_actions', 60 );
		$this->loader->add_filter( "woocommerce_order_number", $this->order_admin, 'filter_order_number',10,2);
		$this->loader->add_action( "admin_head", $this->order_admin, 'edit_screen_title',10);
		$this->loader->add_action( "load-post.php", $this->order_admin, 'remove_add_button',10);
		$this->loader->add_action( "admin_menu", $this->order_admin, 'add_menus', 20);
		$this->loader->add_filter( "woocommerce_admin_order_actions", $this->order_admin, "filter_woocommcerce_order_actions", 10 ,2);
		add_action("platy_etsy_orders_cron_hook", "platy\\etsy\\orders\\EtsyOrdersSyncer::do_cron_task");

		$product_column = new OrderTableColumn($this->order_admin->get_syncer());
		$this->loader->add_filter( "woocommerce_shop_order_list_table_columns", $product_column, 'add_column' );
		$this->loader->add_filter( "woocommerce_shop_order_list_table_custom_column", $product_column, 'populate_column', 10, 2 );
		$this->loader->add_filter( 'manage_edit-shop_order_columns', $product_column, 'add_column' );
		$this->loader->add_filter( 'manage_shop_order_posts_custom_column', $product_column, 'populate_column_legacy' );
		try{
			$order_filter = new OrderTableFilter($this->order_admin->get_syncer());
			$this->loader->add_action( 'restrict_manage_posts', $order_filter, 'add_etsy_orders_filter_legacy' );
			$this->loader->add_action( 'restrict_manage_posts', $order_filter, 'maybe_add_shops_select_legacy' );
			$this->loader->add_action( 'posts_clauses', $order_filter, 'add_prorduct_filter_sql' );
			$this->loader->add_action( 'woocommerce_order_list_table_restrict_manage_orders', $order_filter, 'add_etsy_orders_filter' );
			$this->loader->add_action( 'woocommerce_order_list_table_restrict_manage_orders', $order_filter, 'maybe_add_shops_select' );
			$this->loader->add_action( 'woocommerce_order_list_table_prepare_items_query_args', $order_filter, 'add_order_query_args' );
		}catch(EtsySyncerException $e){

		
		}
	}

	
	function load_product_table_hooks(){

		$product_column = new ProductTableColumn($this->plugin_admin->get_syncer());
		$this->loader->add_filter( 'manage_edit-product_columns', $product_column, 'add_column' );
		$this->loader->add_filter( 'manage_product_posts_custom_column', $product_column, 'populate_column' );

		try{
			$product_filter = new ProductTableFilter($this->plugin_admin->get_syncer());
			$this->loader->add_action( 'woocommerce_products_admin_list_table_filters', $product_filter, 'add_etsy_filter' );
			$this->loader->add_action( 'posts_clauses', $product_filter, 'add_prorduct_filter_sql' );
		}catch(EtsySyncerException $e){

		
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

		$etys_syncer = new EtsySyncer();
		$plugin_public = new Platy_Syncer_Etsy_Public( $this->get_plugin_name(), $this->get_version(), $etys_syncer);

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action('woocommerce_after_single_product', $plugin_public, 'add_powered_by_footer', 1000);
		if($etys_syncer->get_option("enable_public_product_link", false)) {
			$this->loader->add_action( 'the_content', $plugin_public, 'add_etsy_link_to_description', 1000 );
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
	 * @return    Platy_Syncer_Etsy_Loader    Orchestrates the hooks of the plugin.
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
