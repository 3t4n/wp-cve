<?php
use platy\etsy\EtsySyncer;
use platy\etsy\EtsyDataService;
use platy\etsy\MarketAttributes;
use platy\etsy\NoCurrentShopException;
use platy\etsy\api\OAuthException;
use platy\etsy\EtsyStockSyncer;
use platy\etsy\rest\shops\ShopsRestController;
use platy\etsy\rest\connections\ConnectionsRestController;
use platy\etsy\rest\options\OptionsRestController;
use platy\etsy\rest\templates\TemplatesRestController;
use platy\etsy\rest\products\ProductRestController;
use platy\etsy\rest\autosync\AutoSyncRestController;
use platy\etsy\rest\etsy\categories\EtsyCategoriesApi;
use platy\etsy\rest\pro\ProRestController;
use platy\etsy\GuzzleHttp\Exception\ClientException;
use platy\etsy\GuzzleHttp\Exception\ConnectException;
use platy\etsy\api\Client as Oauth2Client;
use platy\etsy\admin\LogTable;
use platy\etsy\logs\PlatyLogger;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       inon_kaplan
 * @since      1.0.0
 *
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/admin
 * @author     Inon Kaplan <inonkp@gmail.com>
 */
class Platy_Syncer_Etsy_Admin {
	/**
	 * 
	 *
	 * @var EtsySyncer
	 */
	protected $syncer;

	/**
	 *
	 * @var EtsyDataService
	 */
	protected $data_service;
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->syncer = new EtsySyncer();
		$this->data_service = EtsyDataService::get_instance();

	}

	function get_syncer(){
		return $this->syncer;
	}

	function enqueue_icon_style(){
		wp_register_style('platy-syncer-dashicons', plugin_dir_url( __FILE__) .'/css/platy-syncer-dashicons.css');
		wp_enqueue_style('platy-syncer-dashicons');
	}

	public function admin_notices() {
		$pagename = empty($_GET['page']) ? "" : $_GET['page'];

		if(empty($this->get_platys())) {
			
        }
		$this->maybe_show_oauth_notice();
	}

	function set_up_product_table_scripts($screen){
		if($screen->id == "edit-product"){
			$this->enqueue_syncer_scripts();
			
			// wp_enqueue_style( 'select_all_css', plugins_url( 'css/check_all.css', __FILE__ ), array(), 1.4);
		}
	}

	function add_platy_bulk_action($bulk_actions){
		$bulk_actions['platy-syncer-etsy'] = __( 'Sync to Etsy', 'platy-syncer-etsy');
		if(@$_GET['platy-syncer-etsy-filter'] == 'synced'){
			$bulk_actions['platy-syncer-etsy-unlink'] = __( 'Unlink from Etsy', 'platy-syncer-etsy');
		}
		return $bulk_actions;
	}

	function handle_platy_bulk_action($redirect_url, $action, $post_ids) {
		if($action == 'platy-syncer-etsy-unlink'){
			$logger = $this->get_syncer()->get_item_logger();
			foreach($post_ids as $id) {
				$logger->delete_log($id, $this->syncer->get_shop_id());
				$logger->delete_child_logs($id, $this->syncer->get_shop_id());
				$logger->delete_meta_logs($id, $this->syncer->get_shop_id());
			}
			
		}
		return $redirect_url;
	}

	function maybe_show_oauth_notice() {
		if(!empty($_GET['page']) && $_GET['page'] == 'platy-syncer-etsy-oauth2') {
			if(!isset($_GET['error']) && !isset($_GET['error_description'])){
				return; // this means the shop is just about to be saved.
			}
		}
		
		try{
			$shop = $this->data_service->get_current_shop();
			$shops_page = menu_page_url($this->get_shops_menu_slug($this->data_service->is_shop_authenticated()), false);
			$class = 'notice notice-error';
			if($this->data_service->has_legacty_token_only($shop)){
				$message = __( "Etsy has updated their API. 
					Please reauthenticate your shop <a href='$shops_page'>here</a>", 'platy-etsy' );	
				printf( '<div class="%1$s"><p style="font-size: 1.2rem"><strong>%2$s</strong></p></div>', esc_attr( $class ), ( $message ) ); 

				return;
			}
			if($this->data_service->refresh_token_expired($shop)){
				$message = __( "Your Etsy API token has expired. 
					Please reauthenticate your shop <a href='$shops_page'>here</a>", 'platy-etsy' );	
				printf( '<div class="%1$s"><p style="font-size: 1.2rem"><strong>%2$s<b/></strong></div>', esc_attr( $class ), ( $message ) ); 

				return;
			}
		}catch(NoCurrentShopException $e) {
			return;
		}
		
	}

	function add_menus(){
		if ( ! defined( 'WC_VERSION' ) ) {
			add_action( 'admin_notices', function(){
				$class = 'notice notice-error';
				$message = __( 'Woocommerce is required to use Platy Syncer Etsy', 'sample-text-domain' );	
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
			} );
			return;
		}

		
		add_menu_page( "Platy Syncer", "Platy Syncer", "manage_woocommerce", "platy-syncer-etsy", function(){
			
		}, 'dashicons-platy');
		$shop_authenticated = $this->data_service->is_shop_authenticated();
		if($shop_authenticated){
			
			add_submenu_page( "platy-syncer-etsy", "Platy Syncer", "Platy Syncer", "manage_woocommerce", "platy-syncer-etsy", [$this, "enqueue_settings_scripts"], 0);
			
		}
		
		$shops_menu_slug = $this->get_shops_menu_slug($shop_authenticated);

		add_submenu_page( "platy-syncer-etsy", "Shops", "Shops", "manage_woocommerce", $shops_menu_slug, [$this, "enqueue_shops_scripts"], 1);

		//help
		add_submenu_page( "platy-syncer-etsy", "Help", "Help", "manage_woocommerce", "platy-syncer-etsy-help", [$this, "enqueue_help_scripts"], 30);

		//pro
		add_submenu_page( "platy-syncer-etsy", "Go Pro", "Go Pro", "manage_woocommerce", "platy-syncer-etsy-pro", [$this, "enqueue_go_pro_scripts"], 40);
		
		//oauth2
		add_submenu_page( null, "", "", "manage_woocommerce", "platy-syncer-etsy-oauth2", [$this, "enqueue_oauth2_scripts"], 40);
		
		add_submenu_page( null, "", "", "manage_woocommerce", "platy-syncer-etsy-sync-logs", [$this, "render_sync_logs"], 40);
		
	}

	public function render_sync_logs() {
		$logger = PlatyLogger::get_instance();

		$log_type = empty($_GET['log_type']) ? "%" : $_GET['log_type'];
		$max_entries = empty($_GET['max_entries']) ? 100 : $_GET['max_entries']; 
		$rows = $logger->get_logs($max_entries, $log_type);
		$log_table = new LogTable($rows);
		$log_table->render();
	}

	private function get_shops_menu_slug($shop_authenticated) {
		return $shop_authenticated ? "platy-syncer-etsy-shops" : "platy-syncer-etsy";
	}

	function init_rest_apis(){
		$this->init_shops_api();
		$this->init_options_api();
		$this->init_go_pro_api();
		if($this->data_service->is_shop_authenticated()){
			$this->init_connections_api();
			$this->init_templates_api();
			$this->init_products_api();
			$this->init_etsy_categories_api();
			$this->init_auto_sync_api();
		}
	}

	function init_auto_sync_api() {
		require_once plugin_dir_path(  __FILE__ ) . 'rest/auto-sync/class-auto-sync-api.php';
		$api = new AutoSyncRestController();
		$api->register_routes();
	}

	function init_go_pro_api(){
		require_once plugin_dir_path(  __FILE__ ) . 'rest/pro/class-go-pro-api.php';
		$api = new ProRestController();
		
		$api->register_routes();
	}

	function init_shops_api(){
		require_once plugin_dir_path(  __FILE__ ) . 'rest/shops/class-shops-api.php';
		$api = new ShopsRestController();
		
		$api->register_routes();
	}

	function init_options_api(){
		require_once plugin_dir_path(  __FILE__ ) . 'rest/options/class-options-api.php';
		$api = new OptionsRestController();
		
		$api->register_routes();
	}

	function init_connections_api(){
		require_once plugin_dir_path(  __FILE__ ) . 'rest/connections/class-connections-api.php';
		$api = new ConnectionsRestController();
		
		$api->register_routes();
	}

	function init_products_api(){
		require_once plugin_dir_path(  __FILE__ ) . 'rest/products/class-products-api.php';
		$api = new ProductRestController();
		
		$api->register_routes();
	}

	function init_templates_api(){
		require_once plugin_dir_path(  __FILE__ ) . 'rest/templates/class-templates-api.php';

		$api = new TemplatesRestController();
		$api->register_routes();
	}

	function init_etsy_categories_api(){
		require_once plugin_dir_path(  __FILE__ ) . 'rest/etsy-categories/class-etsy-categories-api.php';

		$api = new EtsyCategoriesApi();
		$api->register_routes();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Platy_Syncer_Etsy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Platy_Syncer_Etsy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/platy-syncer-etsy-admin.css', array(), $this->version, 'all' );
		
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_syncer_scripts() {

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/platy-syncer-etsy-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'platy_syncer_js', plugins_url( 'js/platy-syncer-etsy-admin.js', __FILE__ ), array(), 1.8, true );
		wp_enqueue_script( 'platy_select_all_js', plugins_url( 'js/platy-syncer-products-table.js', __FILE__ ), array(), 1.7, true );
		$svelte = glob(plugin_dir_path( __FILE__ ). 'js/syncer/bundle-*.js');
		$svelte = basename($svelte[0]);

		$templates = [];
		$draft_mode = true;
		if($this->data_service->has_current_shop()){
			$draft_mode = $this->data_service->get_option('draft_mode', true);
			$templates_with_kyes = $this->data_service->get_templates();
			foreach($templates_with_kyes as $t => $v){
				$templates[] = ['id' => $v['id'], 'name' => $v['name']];
			}
		}
		
		wp_enqueue_script( 'platy_bundle_js',plugins_url( 'js/syncer/' . $svelte, __FILE__ )  ,array("platy_syncer_js"), 1.0, true );
		try{
			wp_localize_script( 'platy_bundle_js', 'platySyncerAdapter',  
				[
					'logo-url' => PLATY_SYNCER_ETSY_DIR_URL . 'assets/images/logo2.png',
					'rest_root' =>  rest_url('/platy-syncer/v1/etsy-products/'),
					'pro_link' => 'https://platycorp.com/',
					'ajax_endpoint' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'platy-syncer' ),
					'rest_nonce' => wp_create_nonce( 'wp_rest' ),
					'platy' => $this->get_platys(),
					'help_link' => admin_url( 'admin.php?page=platy-syncer-etsy-help', 'http' ),
					"admin_url" => admin_url( "admin.php" ),
					'templates' => $templates,
					'draft_mode' => $draft_mode,
					'is_valid' => $this->data_service->is_valid(),
					'has_shop' => $this->data_service->has_current_shop(),
					'is_authenticated' => $this->data_service->is_shop_authenticated(),
					'shop_name' => $this->data_service->has_current_shop() ? $this->data_service->get_current_shop_name() : "",
					'has_template' => $this->data_service->has_templates(),
					'has_shipping_template' => $this->data_service->has_default_shipping_template(),
					'has_default_category' => $this->data_service->has_default_taxonomy(),
					'shops_page' => menu_page_url($this->get_shops_menu_slug($this->data_service->is_shop_authenticated()) , false),
					'settings_page' => menu_page_url('platy-syncer-etsy', false)
				] 
			);
		}catch(Exception $e){
			$class = 'notice notice-warning';
			$message = __( 'Platy Syncer failed to load', 'sample-text-domain' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
		}
		

	}

	function enqueue_oauth2_scripts() {
		if(!isset($_GET['error']) && !isset($_GET['error_description'])){
			if(empty($_GET['code'])){
				return;
			}
			$client = new Oauth2Client(EtsySyncer::API_KEY);
			try {
				$ret = $client->requestAccessToken('https://platycorp.com/wp-json/platy-corp/v1/etsy-oauth2', $_GET['code'],  $_GET['verifier']);
			}catch(OAuthException $e) {
				// in case the user push refresh
				// and the access code cannot be used again.
				$this->enqueue_shops_scripts();
				return;
			}
			$shop = $this->syncer->get_etsy_shop_by_name($_GET['shop']);
			$shop_id = $shop['shop_id'];
            $user_id = $shop['user_id'];
            $shop_label = $_GET['shop'];
			$this->data_service->save_shop([
				'id' => $shop_id,
				'name' => $shop_label,
				'user_id' => $user_id
			]);

			$this->syncer->save_ouath_credentials($shop_id, [
				'oauth2_token' => $ret['access_token'],
				'oauth2_refresh_token' => $ret['refresh_token']
			]);

			$this->data_service->save_default_shop($shop['shop_id']);
		}
		
            
		$this->enqueue_shops_scripts();
	}

	function enqueue_shops_scripts(){

		
		echo "<app-root></app-root>";
		$runtime = glob(plugin_dir_path( __FILE__ ). 'js/shops/runtime.*.js');
		$runtime = basename($runtime[0]);
		$polyfills = glob(plugin_dir_path( __FILE__ ). 'js/shops/polyfills.*.js');
		$polyfills = basename($polyfills[0]);
		$main = glob(plugin_dir_path( __FILE__ ). 'js/shops/main.*.js');
		$main = basename($main[0]);
		$style = glob(plugin_dir_path( __FILE__ ). 'css/shops/styles.*.css');
		$style = basename($style[0]);
		wp_enqueue_style( "platy_syncer_etsy_css",plugins_url( 'css/shops/' . $style, __FILE__ ));
		wp_enqueue_script( 'platy_bundle_runtime_js',plugins_url( 'js/shops/' . $runtime, __FILE__ )  ,array(), 1.0, true );
		wp_enqueue_script( 'platy_bundle_polyfills_js',plugins_url( 'js/shops/' . $polyfills, __FILE__ )  ,array(), 1.0, true );
		wp_enqueue_script( 'platy_bundle_main_js',plugins_url( 'js/shops/' . $main, __FILE__ )  ,array(), 1.1, true );

		$api = rest_url( 'platy-syncer/v1/');
		$shop = null;
		try{
			$shop = $this->data_service->get_current_shop();
		}catch(NoCurrentShopException $e){

		}
		wp_localize_script( 'platy_bundle_main_js', 'platySyncer',  
			[
				'logo-url' => PLATY_SYNCER_ETSY_DIR_URL . 'assets/images/logo.png',
				"shops" => $this->data_service->get_shops(),
				"currentShop" => $shop,
				"help-link" => admin_url( 'admin.php?page=platy-syncer-etsy-help', 'http' ),
				"settings-link" => admin_url( 'admin.php?page=platy-syncer-etsy', 'http' ),
				'options' => $this->data_service->get_options_grouped(),
				"api" => $api,
				"nonce" =>  wp_create_nonce( 'wp_rest' ),
				'platy' => []
			] 
		);
	}

	function get_platys(){
		return $this->syncer->get_platys();
	}

	function enqueue_settings_scripts(){
		
		try{
			$this->syncer->invalidate();
			$shipping_templates = $this->syncer->get_shipping_templates();
			$shop_sections = $this->syncer->get_shop_sections();
			$shop = $this->syncer->get_etsy_shop_by_id($this->data_service->get_current_shop_id());
			$shop_languages = array_map(function($lang) {return \substr($lang, 0, 2);},$shop['languages']);
		}catch(ClientException | ConnectException | \RuntimeException $e){
			$class = 'notice notice-error';
			$exception_message = $e->getMessage();
			$message = __( "Couldnt connect to Etsy. Received message $exception_message", 'platy-syncer' );

			printf( '<div class="%1$s"><h2>%2$s</h2></div>', esc_attr( $class ), ( $message ) ); 
			return;
		}

		if(!$this->data_service->has_templates()){
			$class = 'notice notice-error';
			$message = __( 'Please create a template', 'sample-text-domain' );

			printf( '<div class="%1$s"><h6>%2$s</h6></div>', esc_attr( $class ), esc_html( $message ) ); 

		}
		if(!$this->data_service->has_default_shipping_template()){
			$class = 'notice notice-error';
			$message = __( 'Please choose a default shipping template', 'sample-text-domain' );

			printf( '<div class="%1$s"><h6>%2$s</h6></div>', esc_attr( $class ), esc_html( $message ) ); 

		}
		if(!$this->data_service->has_default_taxonomy()){
			$class = 'notice notice-error';
			$message = __( 'Please select a default etsy category', 'sample-text-domain' );

			printf( '<div class="%1$s"><h6>%2$s</h6></div>', esc_attr( $class ), esc_html( $message ) ); 

		}


		$platys = $this->get_platys();

		echo "<app-root></app-root>";
		$runtime = glob(plugin_dir_path( __FILE__ ). 'js/settings/runtime.*.js');
		$runtime = basename($runtime[0]);
		$polyfills = glob(plugin_dir_path( __FILE__ ). 'js/settings/polyfills.*.js');
		$polyfills = basename($polyfills[0]);
		$main = glob(plugin_dir_path( __FILE__ ). 'js/settings/main.*.js');
		$main = basename($main[0]);
		$style = glob(plugin_dir_path( __FILE__ ). 'css/settings/styles.*.css');
		$style = basename($style[0]);
		wp_enqueue_style( "platy_syncer_etsy_css",plugins_url( 'css/settings/' . $style, __FILE__ ));
		wp_enqueue_script( 'platy_bundle_runtime_js',plugins_url( 'js/settings/' . $runtime, __FILE__ )  ,array(), 1.0, true );
		wp_enqueue_script( 'platy_bundle_polyfills_js',plugins_url( 'js/settings/' . $polyfills, __FILE__ )  ,array(), 1.0, true );
		wp_enqueue_script( 'platy_bundle_main_js',plugins_url( 'js/settings/' . $main, __FILE__ )  ,array(), 1.1, true );
		wp_localize_script( 'platy_bundle_main_js', 'platySyncer',  
			[
				'logo-url' => PLATY_SYNCER_ETSY_DIR_URL . 'assets/images/logo.png',
				'api' => rest_url( "/platy-syncer/v1/"),
				'pro_link' => 'https://platycorp.com/',
				'help-link' => admin_url( 'admin.php?page=platy-syncer-etsy-help', 'http' ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'platy' => $platys,
				'shop_id' => $this->data_service->get_current_shop_id(),
				'woo-categories' => $this->data_service->get_connectable_data_entities('product_cat'),
				'shipping-classes' => $this->data_service->get_connectable_data_entities("product_shipping_class"),
				'etsy-shipping-templates' => $shipping_templates,
				'options' => $this->data_service->get_options_grouped(),
				'etsy-sections' => $shop_sections,
				'etsy-templates' => $this->data_service->get_templates(),
				'shop-languages' => $shop_languages,
				"market-attributes" => [
					'who_made' => MarketAttributes::WHO_MADE_ARRAY,
					'when_made' => MarketAttributes::WHEN_MADE_ARRAY,
					"is_supply" => MarketAttributes::IS_SUPPLY_ARRAY
				]

			] 
		);

	}

	function enqueue_help_scripts(){
		echo "<app-root></app-root>";
		$runtime = glob(plugin_dir_path( __FILE__ ). 'js/help/runtime.*.js');
		$runtime = basename($runtime[0]);
		$polyfills = glob(plugin_dir_path( __FILE__ ). 'js/help/polyfills.*.js');
		$polyfills = basename($polyfills[0]);
		$main = glob(plugin_dir_path( __FILE__ ). 'js/help/main.*.js');
		$main = basename($main[0]);
		$style = glob(plugin_dir_path( __FILE__ ). 'css/help/styles.*.css');
		$style = basename($style[0]);
		wp_enqueue_style( "platy_syncer_etsy_css",plugins_url( 'css/help/' . $style, __FILE__ ));
		wp_enqueue_script( 'platy_bundle_runtime_js',plugins_url( 'js/help/' . $runtime, __FILE__ )  ,array(), 1.0, true );
		wp_enqueue_script( 'platy_bundle_polyfills_js',plugins_url( 'js/help/' . $polyfills, __FILE__ )  ,array(), 1.0, true );
		wp_enqueue_script( 'platy_bundle_main_js',plugins_url( 'js/help/' . $main, __FILE__ )  ,array(), 1.1, true );
		wp_localize_script( 'platy_bundle_main_js', 'platySyncer',  
		[
			'platy' => [],
			'admin_url' => admin_url( ),
			'logo-url' => PLATY_SYNCER_ETSY_DIR_URL . 'assets/images/logo.png',
			'pro_link' => 'https://platycorp.com/',
			'nonce' => wp_create_nonce( 'wp_rest' )
		]);
	}

	function enqueue_go_pro_scripts(){
		echo "<app-root></app-root>";
		$platys = $this->get_platys();
		$runtime = glob(plugin_dir_path( __FILE__ ). 'js/pro/runtime.*.js');
		$runtime = basename($runtime[0]);
		$polyfills = glob(plugin_dir_path( __FILE__ ). 'js/pro/polyfills.*.js');
		$polyfills = basename($polyfills[0]);
		$main = glob(plugin_dir_path( __FILE__ ). 'js/pro/main.*.js');
		$main = basename($main[0]);
		$style = glob(plugin_dir_path( __FILE__ ). 'css/pro/styles.*.css');
		$style = basename($style[0]);
		wp_enqueue_style( "platy_syncer_etsy_css",plugins_url( 'css/pro/' . $style, __FILE__ ));
		wp_enqueue_script( 'platy_bundle_runtime_js',plugins_url( 'js/pro/' . $runtime, __FILE__ )  ,array(), 1.0, true );
		wp_enqueue_script( 'platy_bundle_polyfills_js',plugins_url( 'js/pro/' . $polyfills, __FILE__ )  ,array(), 1.0, true );
		wp_enqueue_script( 'platy_bundle_main_js',plugins_url( 'js/pro/' . $main, __FILE__ )  ,array(), 1.1, true );
		wp_localize_script( 'platy_bundle_main_js', 'platySyncer',  
			[
				'platy' => [],
				'logo-url' => PLATY_SYNCER_ETSY_DIR_URL . 'assets/images/logo.png',
				'platyCorpLink' => "https://platycorp.com/product/platy-syncer-etsy/",
				'rest_nonce' =>  wp_create_nonce( 'wp_rest' ),
				'platy' => $platys,
				"paltyEtsyActivateUrl" => rest_url( '/platy-syncer/v1/go-pro' )
			] 
		);

	}

	public function post_updated_messages($messages){
		global $post;
		$error_message = get_transient( "platy_etsy_error_transient" );
		if(!empty($error_message)){
			?>
				<div id="message" class="notice notice-error is-dismissible"><p><?php echo $error_message; ?></p></div>
			<?php
		};

		$succes_message = get_transient( "platy_etsy_success_transient" );
		if(!empty($succes_message)){
			?>
				<div id="message" class="notice notice-success is-dismissible"><p><?php echo $succes_message; ?></p></div>
			<?php
		};
		return $messages;
	}
}
