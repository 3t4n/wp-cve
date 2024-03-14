<?php

/**
 * Core plugin file
 *
 * Maintains the plugin version. Hooks. Etcetera.
 *
 * @link       silvasoft.nl
 * @since      1.0.0
 *
 * @package    Silvasoft
 * @subpackage Silvasoft/includes
 */
 
global $silva_db_version;
	
class Silvasoft {
	
	protected $loader;
	protected $woohooks;
	protected $silvalogger;
	protected $apiconnector;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		//version and name
		if ( defined( 'PLUGIN_VERSION' ) ) {
			$this->version = PLUGIN_VERSION;
		} else {
			$this->version = '1.1';
		}
		$silva_db_version = '1.0';		
		$this->plugin_name = 'silvasoft';
		//load all the required stuff and subscribe to the hooks
		$this->load_dependencies();
		$this->define_hooks();
	
	}
	
	

	/**
	 *
	 * Load all the classes this plugin needs
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-silvasoft-loader.php';
		
		//admin area, menu and pages
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-silvasoft-admin.php';
		//logger, log to database
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-silvasoft-logger.php';
		//exectuion of hooks to the WooCommerce plugin
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'woo/class-woo-hooks.php';
		//connctor for the Silvasoft API
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-silvasoft-api-connector.php';

		//initiate classes
		$this->loader = new Silvasoft_Loader();
		$this->silvalogger = new Silvasoft_Logger();
		
		global $apiconnector;
		$apiconnector = new Silvasoft_ApiConnector( $this->plugin_name,$this->version,$this->silvalogger);
		$this->apiconnector = $apiconnector;
		
		$this->woohooks = new Silvasoft_WooHooks($this->plugin_name,$this->version,$this->silvalogger,$apiconnector);
		
	}

	

	/**
	 * Register all of the hooks
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_hooks() {
		//setup API global
		global $apiconnector;
		//admin pages & classes
		$plugin_admin = new Silvasoft_Admin( $this->get_plugin_name(), $this->get_version(), $this->loader);
		//admin styles and scripts
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		//custom action to load admin menu items
		$this->loader->add_action('admin_menu',  $plugin_admin, 'admin_menu');
		
		//custom action to execute when other plugins are loaded (usefull for dependencies on other plugins such as WooCommerce)
		$this->loader->add_action('plugins_loaded',  $this, 'when_plugins_are_loaded');
		
		//woocommerce hooks
		$this->loader->add_action( 'woocommerce_order_status_changed', $this->woohooks, 'woo_order_status_change_silvasoft',10,3);
	
		// Add your custom order status action button (for orders with "processing" status)
		$this->loader->add_filter( 'woocommerce_admin_order_actions', $this->woohooks, 'resend_order_to_silvasoft_custom', 100, 2 );
		
		//custom bulk action		
		$this->loader->add_filter( 'bulk_actions-edit-shop_order', $this->woohooks, 'sendorderstosilva_bulk_actions_edit_product', 20, 1 );
		$this->loader->add_filter( 'handle_bulk_actions-edit-shop_order', $this->woohooks, 'bulk_actions_sendorderstosilva', 10, 3 );
		$this->loader->add_action( 'admin_notices', $this->woohooks, 'sendorderstosilva_bulk_action_admin_notice');
		
		// Set Here the WooCommerce icon for your action button
		$this->loader->add_action( 'admin_head', $this->woohooks, 'add_custom_order_status_actions_button_css' );
		
		$this->loader->add_action( 'wp_ajax_sendordertosilva', $this->woohooks, 'sendordertosilva_ajax' );
		
		$this->loader->add_action('silvasoft_woo_cron',  $apiconnector, 'silvasoft_woo_cron');

	}
		
			
	 /* Function to be executed when other plugins are loaded */
	 public function when_plugins_are_loaded() {
		 if ( ! class_exists( 'WooCommerce' ) ) {
			add_action('admin_notices', array($this, 'wc_not_loaded'));	
			return; 
		 }

	 }
	
	 
	/* Missing WooCommerce plugin notice */
	public function wc_not_loaded() {
		echo '<div class="error notice"><p>';
       	echo _e( 'Het lijkt erop dat WooCommerce niet geactiveerd is in uw WordPress installatie. De Silvasoft plugin kan daarom niet uitgevoerd worden. ', 'my_plugin_textdomain' ); 
        echo '</div>';
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
	 * @return    Silvasoft_Loader    Orchestrates the hooks of the plugin.
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
