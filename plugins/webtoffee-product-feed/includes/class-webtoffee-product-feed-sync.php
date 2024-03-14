<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.webtoffee.com
 * @since      1.0.0
 *
 * @package    Webtoffee_Product_Feed_Sync
 * @subpackage Webtoffee_Product_Feed_Sync/includes
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
 * @package    Webtoffee_Product_Feed_Sync
 * @subpackage Webtoffee_Product_Feed_Sync/includes
 * @author     WebToffee <info@webtoffee.com>
 */
if(!class_exists('Webtoffee_Product_Feed_Sync')){
class Webtoffee_Product_Feed_Sync {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Webtoffee_Product_Feed_Sync_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		
	public static $loaded_modules=array();

	public static $template_tb='wt_pf_mapping_template';
	public static $history_tb='wt_pf_action_history';
	public static $cron_tb='wt_pf_cron';

	public $plugin_admin;
	public $plugin_public;
	public $plugin_base_name;

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
		if ( defined( 'WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION' ) ) {
			$this->version = WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION;
		} else {
			$this->version = '2.1.6';
		}
		$this->plugin_name = 'webtoffee-product-feed';
		$this->plugin_base_name	 = WT_PRODUCT_FEED_BASE_NAME;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Webtoffee_Product_Feed_Sync_Loader. Orchestrates the hooks of the plugin.
	 * - Webtoffee_Product_Feed_Sync_i18n. Defines internationalization functionality.
	 * - Webtoffee_Product_Feed_Sync_Admin. Defines all hooks for the admin area.
	 * - Webtoffee_Product_Feed_Sync_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-webtoffee-product-feed-sync-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-webtoffee-product-feed-sync-i18n.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/fbcatalog/wt-fbfeed-category-map-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/fbcatalog/wt-fbfeed-attribute-map-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/fbcatalog/class-wt-catalog-manager-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/fbcatalog/wt-fbfeed-category-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/fbcatalog/class-wt-catalog-fbproducts.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/fbcatalog/wt-fbfeed-scheduler.php';

                $product_block_editor_enabled =  get_option( 'woocommerce_feature_product_block_editor_enabled' );
                if( 'yes' == $product_block_editor_enabled ){
                    require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/class-webtoffee-product-feed-blocks.php';
                }                
                
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-webtoffee-product-feed-sync-admin.php';		
								
		
		/**
		 * Class includes input sanitization and role checking
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'helpers/class-wt-security-helper.php';

		/**
		 * Class includes common helper functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'helpers/class-wt-common-helper.php';

		/**
		 * Class includes helper functions for import and export modules
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'helpers/class-pf-catalog-export-helper.php';
		/**
		 * Class includes helper functions for shipping calculations
		 */		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'helpers/class-wt-feed-shipping.php';

		
		
		
		

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-webtoffee-product-feed-sync-review-request.php';

		$this->loader = new Webtoffee_Product_Feed_Sync_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Webtoffee_Product_Feed_Sync_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Webtoffee_Product_Feed_Sync_i18n();

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

		$plugin_admin = new Webtoffee_Product_Feed_Sync_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		
				/* Loading admin modules */
		$plugin_admin->admin_modules();
		
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_item' );
		
				/* Admin menus */
		$this->loader->add_action('admin_menu',$plugin_admin, 'admin_menu',11);
		
		$this->loader->add_action('wp_ajax_wt_pf_save_settings_basic',$plugin_admin,'save_settings');
		
		$this->loader->add_action( 'admin_action_' . WT_Fb_Catalog_Manager_Settings::DISCONNECT_ACTION, $plugin_admin, 'handle_disconnect' );
		$this->loader->add_action( 'wp_ajax_wt_fbfeed_ajax_upload', $plugin_admin, 'wt_fbfeed_ajax_upload' );
		$this->loader->add_action( 'wp_ajax_fbfeed_batch_status_ajax', $plugin_admin, 'wt_fbfeed_batch_status' );
		
		$this->loader->add_action( 'wp_ajax_wt_fbfeed_ajax_save_category', $plugin_admin, 'wt_fbfeed_ajax_save_category' );
		$this->loader->add_filter( 'plugin_action_links_' . $this->get_plugin_base_name(), $plugin_admin, 'add_productfeed_action_links' );
                
                $this->loader->add_action( 'woocommerce_before_delete_product_variation', $plugin_admin, 'delete_product_from_fb', 10, 1 );
                $this->loader->add_action( 'wp_trash_post', $plugin_admin, 'delete_product_from_fb', 10, 1 );
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
	 * The name of the plugin basefile used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin basefile.
	 */	
	public function get_plugin_base_name() {
		return $this->plugin_base_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Webtoffee_Product_Feed_Sync_Loader    Orchestrates the hooks of the plugin.
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
	
	
	
	public static function get_module_id($module_base)
	{
		return WEBTOFFEE_PRODUCT_FEED_MAIN_ID.'_'.$module_base;
	}

	/**
	 * Some modules are not start by default. So need to initialize via code OR get object of already started modules
	 *
	 * @since    1.0.0
	 */
	public static function load_modules($module)
	{
		if(Webtoffee_Product_Feed_Sync_Admin::module_exists($module))
		{ 
			if(!isset(self::$loaded_modules[$module]))
			{
				$module_class='Webtoffee_Product_Feed_Sync_'.ucfirst($module);
				self::$loaded_modules[$module]=new $module_class;
			}
			return self::$loaded_modules[$module];
		}
		else
		{
			return null;
		}
	}

	/**
	 * Generate tab head for settings page.
	 * @since     1.0.0
	 */
	public static function generate_settings_tabhead($title_arr, $type="plugin")
	{	
		$out_arr=apply_filters("wt_pf_".$type."_settings_tabhead_basic",$title_arr);
		foreach($out_arr as $k=>$v)
		{			
			if(is_array($v))
			{
				$v=(isset($v[2]) ? $v[2] : '').$v[0].' '.(isset($v[1]) ? $v[1] : '');
			}
		?>
			<a class="nav-tab" href="#<?php echo $k;?>"><?php echo $v; ?></a>
		<?php
		}
	}

	/**
	*  	Get remote file adapters. Eg: FTP, Gdrive, OneDrive
	* 	@param string $action action to be executed, If the current adapter is not suitable for a specific action then skip it
	*   @param string $adapter optional specify an adapter name to retrive the specific one
	* 	@return array|single array of remote adapters or single adapter if the adapter name specified
	*/
	public static function get_remote_adapters($action, $adapter='')
	{
		$adapters=array();
		$adapters = apply_filters("wt_pf_remote_adapters_basic", $adapters, $action, $adapter);
		if($adapter != "")
		{
			return (isset($adapters[ $adapter ]) ? $adapters[ $adapter ] : null);
		}
		return $adapters;
	}
	

}
}
