<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wbsync.com
 * @since      1.0.0
 *
 * @package    Data_Sync_X_Woocommerce
 * @subpackage Data_Sync_X_Woocommerce/includes
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
 * @package    Data_Sync_X_Woocommerce
 * @subpackage Data_Sync_X_Woocommerce/includes
 * @author     Michael Pierotti <hello@wbsync.com>
 */
class Data_Sync_X_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Data_Sync_X_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	
	/**
	 *	wbsync_xero_api
	 *	wbsync_woo_xero_sync_guid
	 *	wbsync_xero_con_guid
	 *	wbsync_xero_sync_active
	 * 	wbsync_woo_xero_taxcodes
	 *	wbsync_woo_xero_items
	 *	wbsync_woo_xero_customers
	 *	wbsync_xero_sync_settings
	 * 	wbsync_woo_xero_accounts
	 */
	

	public function __construct() {
		
		if ( defined( 'DATA_SYNC_X_WOOCOMMERCE_VERSION' ) ) {
			$this->version = DATA_SYNC_X_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'data-sync-x-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		register_activation_hook( __FILE__, function() {
			$url = 'https://sys.wbsync.com/?e=WBSYNC-PLUGIN-ACTIVATE';
			wp_remote_post( $url, [ 'body' => ['url' => get_home_url(), 
											   'email' => wp_get_current_user()->user_email,
											   'plugin' => 'WOO-QBO'
												]	]);
		});
		
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			
			$this->slug = "data_sync_x_wbsync_settings";
			$this->init_settings();
			
			add_action( 'admin_menu', array( $this, 'wbsync_xero_settings_page' ) );
			
			if(get_option('wbsync_xero_api') != '' && get_option('wbsync_woo_xero_sync_guid') != '' && get_option('wbsync_xero_con_guid') != '' && get_option('wbsync_xero_sync_active') == 1){
				add_action( 'wp_loaded', array($this, 'wbsync_xero_init_frontend_hooks'), 10 );
			}
			
		}

	}

	function init_settings(){
		
		$setting_keys = ['wbsync_xero_api',	
						 'wbsync_woo_xero_sync_guid',
						 'wbsync_xero_con_guid',
						 'wbsync_xero_sync_active',
						 'wbsync_woo_xero_taxcodes',
						 'wbsync_woo_xero_items',
						 'wbsync_woo_xero_customers',
						 'wbsync_xero_sync_settings',
						 'wbsync_woo_xero_accounts'];
		
		foreach($setting_keys as $s){
			if(is_null(get_option($s))){
				update_option($s, '');
			}
		}
		
	}
	function wbsync_xero_init_frontend_hooks() {
		
		if( is_admin() ){
			
			add_action( 'woocommerce_order_status_changed', array( $this, 'wbsync_process_order_change'), 10, 3 );
			
		}else{
		
			add_action( 'woocommerce_checkout_update_order_meta',  array( $this,'wbsync_process_order'), 10, 2 );
		
		}
		
	}
	
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Data_Sync_X_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Data_Sync_X_Woocommerce_i18n. Defines internationalization functionality.
	 * - Data_Sync_X_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Data_Sync_X_Woocommerce_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-data-sync-x-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-data-sync-x-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-data-sync-x-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-data-sync-x-woocommerce-public.php';

		$this->loader = new Data_Sync_X_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Data_Sync_X_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Data_Sync_X_Woocommerce_i18n();

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

		$plugin_admin = new Data_Sync_X_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Data_Sync_X_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    Data_Sync_X_Woocommerce_Loader    Orchestrates the hooks of the plugin.
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

	function wbsync_process_order($order_id, $data){
		
	
		$this->wbsync_process_order_final($order_id);
		

	}
	function wbsync_process_order_change($order_id, $prev, $currnet){
		
		
		$this->wbsync_process_order_final($order_id);
		//

	}
	
	function wbsync_process_order_final($order_id){
		
		$order = wc_get_order($order_id);
		
		$data = [];
		
		$data = $order->get_data();
		
		$data['paid_date'] = $order->get_date_paid();
		
		foreach($order->get_items() as $i){
			
			$item_temp = $i->get_data();
			$item_temp['sku'] = '';
			$product = wc_get_product( $i->get_product_id() );
			$item_temp['sku'] = $product->get_sku();
			$data['items'][] = $item_temp;
			
		}
		
		
		foreach($order->get_fees() as $i){
			$data['fees'][] = $i->get_data();
		}
		
		foreach($order->get_taxes() as $i){
			$data['tax'][] = $i->get_data();
		}
		
		foreach($order->get_shipping_methods() as $i){
			$data['shipping'][] = $i->get_data();
		}
		
		unset($data['tax_lines']);
		unset($data['shipping_lines']);
		unset($data['fee_lines']);
		unset($data['coupon_lines']);
		unset($data['line_items']);
		
	
		
		$url = 'https://sys.wbsync.com/?e=WBSYNC-SYNC-PRCESS';
		$response = wp_remote_post( $url, [ 'body' => ['hash' => get_option('wbsync_xero_api'), 'guid' => get_option('wbsync_woo_xero_sync_guid'),
													  'data' => urlencode ( wp_json_encode($data) ) ]	]);

		
		if ( is_wp_error( $response ) ) {

			$error_message = $response->get_error_message();
			echo "Something went wrong connecting to wbsync server: $error_message";

		}else{
			
			
		}
		
	}
	
	
	public function wbsync_xero_settings_page() {
		
		$page_title = 'Data Sync Xero Settings';
		$menu_title = 'Data Sync Xero';
		$capability = 'manage_options';
		$slug = 'data_sync_x_wbsync_settings';
		$callback = array( $this, 'plugin_settings_page_content' );
		$icon = 'dashicons-leftright';
		$position = 100;
		
		add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
		
	}
	
	public function handle_form_settings($__POST){
		
		$temp_settings['sync_order'] = "false";
		$temp_settings['xero_use_code'] = "0";
		$temp_settings['xero_cust_code'] = "";
		$temp_settings['use_woo_on'] = "false";
		$temp_settings['sync_payment'] = "false";
		$temp_settings['xero_match_cust_on'] = "name";
		$temp_settings['xero_on_prefix'] = "false";
		$temp_settings['order_code_match'] = "false";
		$temp_settings['non_stock_code'] = "";
		$temp_settings['xero_no_tax_code'] = "false";
		$temp_settings['xero_tax_code'] = "false";
		$temp_settings['sync_inv'] = "false";
		$temp_settings['i_match'] = "";
		$temp_settings['i_sync_price'] = "";
		$temp_settings['i_sync_price_direct'] = "";
		$temp_settings['i_sync_qty'] = "";
		$temp_settings['i_sync_qty_direct'] = "";

		$final = [];

		if(isset($__POST['sync_order_status'])){

			$final['sync_order_status'] = $__POST['sync_order_status'];

			unset($__POST['sync_order_status']);

		}else{

			$final['sync_order_status'] = [];
		}


		foreach($temp_settings as $k=>$v){

			if( !in_array( $k, array_keys($__POST) ) ){

				$final[$k] = $v;
			}else{
				$final[$k] = $__POST[$k];
			}

		}
		
		$active = 1;
		if(!isset($__POST['active'])){
			$active = 0;
		}
		
		$final['active'] = $active;
		
		
		$url = 'https://sys.wbsync.com/?e=WBSYNC-UPDATESYNC';
		$response = wp_remote_post( $url, [ 'body' => ['hash' => get_option('wbsync_xero_api'), 'guid' => get_option('wbsync_woo_xero_sync_guid'), 'data' => urlencode(json_encode($final) ) ]	]);

		if ( is_wp_error( $response ) ) {

			$error_message = $response->get_error_message();
			echo "Something went wrong connecting to wbsync server: $error_message";

		}
	}
	
	public function handle_form_sync_xero(){
		
		$url = 'https://sys.wbsync.com/?e=XERO-TAXCODES';
		$response = wp_remote_post( $url, [ 'body' => ['hash' => get_option('wbsync_xero_api'), 'guid' => get_option('wbsync_xero_con_guid') ]	]);

		if ( is_wp_error( $response ) ) {

			$error_message = $response->get_error_message();
			echo "Something went wrong connecting to wbsync server: $error_message";

		}else{
			
			$res_sync_taxcodes = json_decode($response['body'], TRUE);
			
			update_option('wbsync_woo_xero_taxcodes', json_encode($res_sync_taxcodes['data']) );
			
		}
		
		$url = 'https://sys.wbsync.com/?e=XERO-ITEMS';
		$response = wp_remote_post( $url, [ 'body' => ['hash' => get_option('wbsync_xero_api'), 'guid' => get_option('wbsync_xero_con_guid') ]	]);

		
		if ( is_wp_error( $response ) ) {

			$error_message = $response->get_error_message();
			echo "Something went wrong connecting to wbsync server: $error_message";

		}else{
			$res_sync_items = json_decode($response['body'], TRUE);
			update_option('wbsync_woo_xero_items', json_encode($res_sync_items['data']) );
			
		}
		sleep(1);
		$url = 'https://sys.wbsync.com/?e=XERO-CONTACTS';
		$response = wp_remote_post( $url, [ 'body' => ['hash' => get_option('wbsync_xero_api'), 'guid' => get_option('wbsync_xero_con_guid') ]	]);
		
		if ( is_wp_error( $response ) ) {

			$error_message = $response->get_error_message();
			echo "Something went wrong connecting to wbsync server: $error_message";

		}else{
			$res_sync_cust = json_decode($response['body'], TRUE);
			update_option('wbsync_woo_xero_customers', sanitize_text_field( json_encode($res_sync_cust['data']) ) );
			
		}
		
		$url = 'https://sys.wbsync.com/?e=XERO-ACCOUNTS';
		$response = wp_remote_post( $url, [ 'body' => ['hash' => get_option('wbsync_xero_api'), 'guid' => get_option('wbsync_xero_con_guid') ]	]);
		
		if ( is_wp_error( $response ) ) {

			$error_message = $response->get_error_message();
			echo "Something went wrong connecting to wbsync server: $error_message";

		}else{
			$res_sync_cust = json_decode($response['body'], TRUE);
			update_option('wbsync_woo_xero_accounts', sanitize_text_field( json_encode($res_sync_cust['data']) ) );
			
		}
		
	}
	
	public function handle_form() {
		
		if(
			! isset( $_POST['wbsync_form'] ) ||
			! wp_verify_nonce( $_POST['wbsync_form'], 'wbsync_update' )
		){ ?>
			<div class="error">
			   <p>Sorry, your nonce was not correct. Please try again.</p>
			</div> <?php
			exit;
		} else {
			
			if(isset( $_POST['form_name']) && sanitize_text_field($_POST['form_name']) == 'xero_settings'){
				
				$this->handle_form_settings($_POST);
				
			}elseif(isset($_POST['form_name']) && sanitize_text_field($_POST['form_name']) == 'xero_sync_data'){
				
				$this->handle_form_sync_xero();
			
			}elseif(isset($_POST['form_name']) && sanitize_text_field($_POST['form_name']) == 'xero_disconnect'){
				
				update_option('wbsync_xero_con_guid', '');
					
			}elseif(isset($_POST['form_name']) && sanitize_text_field($_POST['form_name']) == 'xero_add_sync'){
				
				if(get_option('wbsync_xero_woo_guid') != ''  && sanitize_text_field($_POST['wbsync_xero_conn_guid']) != ''){
					$url = 'https://sys.wbsync.com/?e=WBSYNC-SYNC-CREATE';
					$response = wp_remote_post( $url, [ 'body' => [
						'hash' 			=> get_option('wbsync_xero_api'), 
						'con1' 			=> get_option('wbsync_xero_woo_guid'),
						'con2'			=> sanitize_text_field($_POST['wbsync_xero_conn_guid']),
						'source_table'	=> '2_e_woocom',
						'dest_table'	=> '2_e_xero',
					] 	]);

					if ( is_wp_error( $response ) ) {

						$error_message = $response->get_error_message();
						echo "Something went wrong connecting to wbsync server: $error_message";

					} else {

					
						$res = json_decode($response['body'], TRUE);


						if($res['status'] == 'success'){

							update_option('wbsync_woo_xero_sync_guid', sanitize_text_field($res['data']['guid']) );
							update_option('wbsync_xero_sync_settings', sanitize_text_field($res['data']['params']) );
							update_option('wbsync_xero_con_guid', sanitize_text_field($_POST['wbsync_xero_conn_guid']));

							
						}
					}
				}else{
					echo "Missing data.";
				}
				
				
			}else{
			
				if(isset($_POST['wbsync_xero_api'])){
					
					if(get_option('wbsync_xero_api') == '' ){
					
						//CONFIRM GUID
						$url = 'https://sys.wbsync.com/?e=WBSYNC-COMPANY';
						$response = wp_remote_post( $url, [ 'body' => ['hash' => sanitize_text_field($_POST['wbsync_xero_api']) ]	]);

						if ( is_wp_error( $response ) ) {

							$error_message = $response->get_error_message();
							echo "Something went wrong connecting to wbsync server: $error_message";

						} else {

							$res = json_decode($response['body'], TRUE);

							if($res['status'] == 'success'){
								
								update_option('wbsync_xero_api', sanitize_text_field($_POST['wbsync_xero_api']) );
								
							}
						}

						

					}else{
						update_option('wbsync_xero_api', sanitize_text_field( $_POST['wbsync_xero_api']) );
					}
				
				}

				if(!isset($_POST['wbsync_xero_set']['active'])){
					$_POST['wbsync_xero_set']['active'] = 0;
				}

				if(isset($_POST['wbsync_xero_set'])){

					if(get_option('wbsync_woo_xero_sync_guid') != '' && get_option('wbsync_xero_api') != ''){


						$wbsync_post_dat = sanitize_text_field($_POST['wbsync_xero_set']);

						$url = 'https://sys.wbsync.com/?e=WBSYNC-UPDATESYNC';
						$response = wp_remote_post( $url, [ 'body' => array_merge(  ['hash' => get_option('wbsync_xero_api'), 'guid' => get_option('wbsync_woo_xero_sync_guid') ], $wbsync_post_dat)]	);

						$res = json_decode($response['body'], TRUE);

						if($res['status'] == 'success'){

							echo '<div class="updated">
								   <p>Settings have been updated on Wbsync.</p>
								</div>';

						}else{

							echo '<div class="error">
								   <p>Error updating on Wbsync server. This has been logged.</p>
								</div>';
						}

						
					}
				}
			}
			
		}
	}
	
	public function plugin_settings_page_content() {

		if( sanitize_text_field( $_POST['updated']) === 'true' ){
			$this->handle_form();
		}
		
		$active_tab =  'display_options';
		
		if(isset( $_GET[ 'tab' ] )){
			
			$tabs = ['display_options', 'logs', 'docs'];
			
			if(in_array($_GET['tab'], $tabs)){
				$active_tab = $_GET['tab'];
			}
		}
			
		$is_active = FALSE;

		if(get_option('wbsync_xero_api') != ''){

			$url = 'https://sys.wbsync.com/?e=WBSYNC-COMPANY';
			$response = wp_remote_post( $url, [ 'body' => ['hash' => get_option('wbsync_xero_api') ]	]);

			if ( is_wp_error( $response ) ) {

				$error_message = $response->get_error_message();
				echo "Something went wrong connecting to wbsync server: $error_message";

			} else {

				$res = json_decode($response['body'], TRUE);

				if($res['status'] != 'success'){
					//HANDLE ERROR
				}else{
					
					//GET XERO GUID FROM SERVER
					$url = 'https://sys.wbsync.com/?e=WBSYNC-PLUGIN-CREATE';
					$response = wp_remote_post( $url, [ 'body' => ['hash' => get_option('wbsync_xero_api'), 'src' => 'PLUGIN-XERO', 'url' => get_home_url(), 'type' => 'woocom' ]	]);

					if ( is_wp_error( $response ) ) {

						$error_message = $response->get_error_message();
						echo "Something went wrong connecting to wbsync server: $error_message";

					}else{
						
						$res_create = json_decode($response['body'], TRUE);

						if($res_create['status'] == 'success'){
							
							update_option('wbsync_xero_woo_guid', sanitize_text_field($res_create['data']['guid']) );
							
						}
					}
				}
			}
		}
		
		$connect_xero = '';
		
		if(get_option('wbsync_xero_api') != '' && $res['status'] == 'success'){
			
			
			
			if(get_option('wbsync_xero_con_guid') == ''){
			
				/**
				 * NO SYNC SET - GET VALID xero CONNECTIONS
				 */
				
				$url = 'https://sys.wbsync.com/?e=WBSYNC-GETCONS';
				//echo $url;
				$response = wp_remote_post( $url, [ 'body' => ['hash' => get_option('wbsync_xero_api'), 'type' => 'xero' ]	]);

				if ( is_wp_error( $response ) ) {

					$error_message = $response->get_error_message();
					echo "Something went wrong connecting to wbsync server: $error_message";

				} else {

					$conns = json_decode($response['body'], TRUE);

					$conn_sel = "<hr><a role='button' href='https://xero.wbsync.com/connect.php?woo=true&guid=".get_option('wbsync_xero_api')."' target='_blank' class='wb-btn wb-btn-outline-success'>Add Xero company</a>";

					if($conns['status'] == 'success'){
						//HANDLE ERROR
						if(count($conns['data']) > 0){
							foreach($conns['data'] as $c){
								$selected = '';
								if(get_option('wbsync_xero_con_guid') == $c['guid']){
									$selected = ' selected';
								}
								$conn_sel .= "<option value='". esc_attr($c['guid'])."' {$selected}>". esc_attr($c['label'])."</option>";
							}

							$conn_sel = "

								<form method='POST'>

									<input type='hidden' name='form_name' value='xero_add_sync' />
									<input type='hidden' name='updated' value='true' />
									". wp_nonce_field( 'wbsync_update', 'wbsync_form' ) . "
									<div class='wb-form-group'>
										<label>Connections</label>
										<select class='wb-form-control mt-3' name='wbsync_xero_conn_guid'>$conn_sel</select>
									</div>
									<button type='submit' id='new-sync-submit' class='wb-btn wb-btn-outline-primary mt-3'>USE CONNECTION</button>
								</form>

								<hr>
								<a role='button' href='https://xero.wbsync.com/connect.php?woo=true&guid=".get_option('wbsync_xero_api')."' target='_blank' class='wb-btn wb-btn-outline-success'>Add Xero company</a>";
						}

					}
				}
				
			}else{
				
				//GET SYNC SETTINGS
				$url = 'https://sys.wbsync.com/?e=WBSYNC-GETSYNC';
				$response = wp_remote_post( $url, [ 'body' => ['hash' => get_option('wbsync_xero_api'), 'guid' => get_option('wbsync_woo_xero_sync_guid') ]	]);

				if ( is_wp_error( $response ) ) {

					$error_message = $response->get_error_message();
					echo "Something went wrong connecting to wbsync server: $error_message";

				}else{
					$res_sync = json_decode($response['body'], TRUE);
					
					if($res_sync['status'] == 'success'){
						
						update_option('wbsync_xero_sync_settings', sanitize_text_field($res_sync['data']['params']));
						update_option('wbsync_xero_sync_active',sanitize_text_field( $res_sync['data']['active']));
					}
				}
				$tax_code_count = 0;
				$item_count = 0;
				$cust_count = 0;
				
				$sel_xero_taxcode = '';
				$sel_xero_items = '';
				$sel_xero_cust = '';
				
				if( get_option('wbsync_woo_xero_taxcodes') !== null && get_option('wbsync_woo_xero_taxcodes') != ''){
				
					$xero_taxcodes = json_decode( get_option('wbsync_woo_xero_taxcodes'), TRUE);
					$tax_code_count = count($xero_taxcodes);
					
					$sel_xero_taxcode .= "<option value=''></option>";
					foreach( $xero_taxcodes as $d){
						$sel_xero_taxcode .= "<option value='" . esc_attr($d['Name'])."'>".esc_attr($d['Name'])." (".esc_attr($d['TaxType']).")</option>";
					}
				}
				if( get_option('wbsync_woo_xero_items') !== null && get_option('wbsync_woo_xero_items') != ''){
				
					$xero_items = json_decode( get_option('wbsync_woo_xero_items'), TRUE);
					$item_count = count($xero_items);

					$sel_xero_items .= "<option value=''></option>";
					
					foreach( $xero_items as $d){
						$sel_xero_items .= "<option value='" .esc_attr( $d['ItemID'] ). "'>".esc_attr($d['Description'])."</option>";
					}
				}
				if( get_option('wbsync_woo_xero_customers') !== null && get_option('wbsync_woo_xero_customers') != ''){
				
					$xero_cust = json_decode( get_option('wbsync_woo_xero_customers'), TRUE);
					$cust_count = count($xero_cust);

					$sel_xero_cust .= "<option value=''></option>";
					foreach( $xero_cust as $d){
						$sel_xero_cust .= "<option value='".esc_attr($d['ContactID'])."'>".esc_attr($d['Name'])."</option>";
					}
				}
				if( get_option('wbsync_woo_xero_accounts') !== null && get_option('wbsync_woo_xero_accounts') != ''){
				
					$xero_accounts = json_decode( get_option('wbsync_woo_xero_accounts'), TRUE);
					$account_count = 0;

					$sel_xero_acc .= "<option value=''></option>";
					foreach( $xero_accounts as $d){
						if($d['Type'] == 'BANK' || $d['EnablePaymentsToAccount'] == 'true'){
							$sel_xero_acc .= "<option value='".esc_attr($d['AccountId'])."'>".esc_attr($d['Name'])."</option>";
							$account_count++;
						}
					}
				}
				
				
				$conn_sel = "<p>WooCommerce is connected to Xero.</p>
								<form method='POST'>
									" . wp_nonce_field( 'wbsync_update', 'wbsync_form' ) ."
									<input type='hidden' name='updated' value='true' />
									<input type='hidden' name='form_name' value='xero_disconnect'/>
									<p><button role='button' type='submit' class='btn btn-link'>Disconnect</button></p>
								</form>
							<p><a role='button' href='https://app.wbsync.com/settings/sync-manager/" . get_option('wbsync_woo_xero_sync_guid') . "' target='_blank' class='btn btn-outline-success'>View in Wbsync</a></p>
							<hr>
							<p>
								<form method='POST'>
									" . wp_nonce_field( 'wbsync_update', 'wbsync_form' ) ."
									<input type='hidden' name='updated' value='true' />
									<input type='hidden' name='form_name' value='xero_sync_data'/>
									<p>Items: $item_count</p>
									<p>Customers: $cust_count</p>
									<p>Tax codes: $tax_code_count</p>
									<p>Accounts: $account_count</p>
									<button class='btn btn-outline-primary' type='submit'>Sync Xero Items</button>
								</form>
							</p>";
				
			}
			
			

			$connect_xero = "
				<div class='col-4'>
					<div class='wb-card' style=''>
						<div class='wb-card-body'>
							<h3>Xero Connection</h3>
							<img class='col-6' src='https://wbsync.com/img/xero.png' alt='Xero'>
							{$conn_sel}

						</div>
					</div>
				</div>
			";
			
			if($res['status'] == 'success' && $res_sync['status'] == 'success'){
				$is_active = TRUE;
			}
			
			//GET LOGS
			
			if($active_tab == 'logs'){
				$url = 'https://sys.wbsync.com/?e=WBSYNC-GETLOGS';
				$response = wp_remote_post( $url, [ 'body' => ['hash' => get_option('wbsync_xero_api'), 'guid' => get_option('wbsync_woo_xero_sync_guid'), 'type' => 'sync' ]	]);
				if ( is_wp_error( $response ) ) {
					$error_message = $response->get_error_message();
					echo "Something went wrong connecting to wbsync server: $error_message";
				}else{
					$logs = $response['body'];
				}
			}

		}
	
		
		?>

		<h2 class="nav-tab-wrapper">
			<a href="?page=data_sync_x_wbsync_settings&tab=display_options" class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>">Setup Options</a>
			<a href="?page=data_sync_x_wbsync_settings&tab=logs" class="nav-tab <?php echo $active_tab == 'logs' ? 'nav-tab-active' : ''; ?>">Sync Logs</a>
			<a href="?page=data_sync_x_wbsync_settings&tab=docs" class="nav-tab <?php echo $active_tab == 'docs' ? 'nav-tab-active' : ''; ?>">Documentation</a>
		</h2>

		<?php		
		if($active_tab == 'display_options'){
		?>
	
			

			<div class='wb-card'>
				<div class='wb-card-body'>
					
					<h2>Wbsync Settings Page</h2>
					<hr>
					<div class='row'>
						<div class='col-8'>
							<form method="POST" id='wbsync-frm-api-token'>
								<input type="hidden" name="updated" value="true" />
								<?php wp_nonce_field( 'wbsync_update', 'wbsync_form' ); ?>
								<table class="form-table">
									<tbody>
										<tr>
											<th><label for="wbsync_xero_api">Wbsync API Key</label></th>
											<td>

												<input name="wbsync_xero_api" id="wbsync_xero_api" type="text" value="<?php echo esc_attr(get_option('wbsync_xero_api')); ?>" class="regular-text" />
												<?php 
													if(get_option('wbsync_xero_api') != ''){

														if($res['status'] == 'success'){ ?>
														<p class='description'>Connected to Wbsync account: <?php esc_html_e( $res['data']['account'], 'text_domain' );?></p>
														<p><a href='https://app.wbsync.com/' target="_blank">Go to Wbsync</a>

													<?php 
														}elseif( ($res['status'] == 'fail' &&  $res['data'] == 'Not found') || $res['status'] == 'not_authed'){ ?>
														<p class='description'>API key does not match. Please chack again.</p>
														<p><a href='#' onclick="wbsync_Popup();">Get your Wbsync API key here</a></p>
													<?php 
														}
													}else{ ?>
												<p><a href='#' onclick="wbsync_Popup();">Get your Wbsync API key here</a></p>
												<?php 
													} ?>

											</td>
										</tr>
									</tbody>
								</table>
								
								
								
								<p class="submit">
									<input type="submit" name="submit" id="wbsync-btn-apikey-submit" class="button button-primary" value="SAVE API KEY">
								</p>
							</form>
							<?php 
							if(get_option('wbsync_woo_xero_sync_guid') != '' && get_option('wbsync_xero_con_guid') != ''){

								if($res_sync['status'] == 'success'){ 
									$settings = json_decode(get_option('wbsync_xero_sync_settings'), TRUE);
							
							?>
							
							<script>
								function wbsync_Popup() {

									var width = 1200;
									var height = 800;
									var title = 'Signup to Wbsync'

									var url ='https://app.wbsync.com/register?type=wp-xero&email=<?php echo esc_html( wp_get_current_user()->user_email );?>&name=<?php echo esc_html( wp_get_current_user()->user_firstname ) . ' ' . esc_html( wp_get_current_user()->user_lastname );?>&company=<?php echo esc_html( get_bloginfo('name') );?>';

									var left = (screen.width / 2) - (width / 2);
									var top = (screen.height / 2) - (height / 2);
									var options = '';    
									options += ',width=' + width;
									options += ',height=' + height;
									options += ',top=' + top;
									options += ',left=' + left;    
									return window.open(url, title, options);
								}

								window.addEventListener("message", wbsync_setData, false);

								function wbsync_setData(data) {
									var json = data.data;

									if(typeof json.token !== 'undefined'){

										jQuery("#wbsync_xero_api").val( json.token );
										jQuery("#wbsync-btn-apikey-submit").trigger("click");


									}

								}
							</script>
							
							<form method="POST">
								<input type="hidden" name="updated" value="true" />
								<input type="hidden" name="form_name" value="xero_settings" />
								<?php wp_nonce_field( 'wbsync_update', 'wbsync_form' ); ?>
								<table class="form-table">
									<tbody>	
										
										<tr>
											<th colspan="2"><label for="">
												<input name="active" id="wb-chk-active" type="checkbox" value="1" <?php echo (get_option('wbsync_xero_sync_active') == 1 ? " checked" : ''); ?> class="regular-text" />
							
												<strong>Sync is active</strong>
											</th>
										</tr>
										
										
											<tr class='cont-settings'>
												<th colspan="2"><label for="">
													<input type="checkbox" name='sync_order' value='true' <?php echo ($settings['sync_order'] == 'true' ? " checked" : "");?>/>

													When an order is created in <strong>WooCommerce</strong> create an invoice in <strong>Xero</strong>
												</th>

											</tr>

											<tr class='cont-settings'>
												<th><label for="">Sync order from WooCommerce with status</label></th>
												<td>

													<div class="custom-control custom-checkbox mb-3">
														<input type="checkbox" class="custom-control-input" id="customCheck1" value="processing" name='sync_order_status[]' <?php echo (@in_array("processing", $settings['sync_order_status']) ? " checked" : "");?> >
														<label class="custom-control-label" for="customCheck1">Processing</label>
													</div>

													<div class="custom-control custom-checkbox mb-3">
														<input type="checkbox" class="custom-control-input" id="customCheck2" value="completed" name='sync_order_status[]' <?php echo (@in_array("completed", $settings['sync_order_status']) ? " checked" : "");?>  >
														<label class="custom-control-label" for="customCheck2">Completed</label>
													</div>

												</td>
											</tr>
											<tr class='cont-settings'>
												<th><label for="">Create Xero invoice with status</label></th>
												<td>

													<div class='form-group'>
														<select id='sel-cust-type' class='form-control' name='xero_inv_status'>
															<option value='draft'>Draft</option>
															<option value='approved'>Approved</option>
														</select>
													</div>
													

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">Send payments to Xero</label></th>
												<td>

													<input class="form-check-input" type="checkbox" value="true" id="" name='sync_payment' <?php esc_html_e( ($settings['sync_payment'] == 'true' ? " checked" : ""), 'text_domain' );?>>


												</td>
											</tr>
											<tr class='cont-settings'>
												<th><label for="">Payments account in Xero</label></th>
												<td>

													<select id='sel-xero-payment-account' class='form-control' name='xero_payment_account'>
														<?php echo $sel_xero_acc;?>
													</select>


												</td>
											</tr>
										
											<tr class='cont-settings'>
												<th><label for="">Send orders with customer type</label></th>
												<td>

													<div class='form-group'>
														<select id='sel-cust-type' class='form-control' name='xero_use_code'>
															<option value='1'>Existing Code</option>
															<option value='2'>Create customer</option>
														</select>
													</div>

												</td>
											</tr>
											
											<tr class='cont-settings'>
												<th><label for="">Existing customer code</label></th>
												<td>

													<div class='form-group'>
														<select id='' class='form-control' name='xero_cust_code'>
															<?php echo ( $sel_xero_cust);?>
														</select>
													</div>

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">Match customers on</label></th>
												<td>

													<select id='sel-terms' class='form-control' name='xero_match_cust_on'>
														<option value='name'>First Name + Last Name/Company Name</option>
														<option value='email'>Email</option>
													</select>

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">Use WooCommerce as Xero invoice number</label></th>
												<td>

													<div class="form-check">
														<input class="form-check-input" type="checkbox" value="true" id="" name='use_woo_on' <?php echo ($settings['use_woo_on'] == 'true' ? " checked" : "");?>>
													</div>

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">If using WooCommerce order number, use a prefix</label></th>
												<td>

													<input class='form-control col-2' placeholder='Prefix' name='xero_on_prefix' value='<?php echo @$settings['xero_on_prefix'];?>'>

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">Match stock on</label></th>
												<td>

													<select class='form-control col-4' name='order_code_match'>
														<option value="Sku">WooCommerce SKU</option>
													</select>

													<p>
														The stock item's code in Xero will match this field in WooCommerce
													</p>

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">Use code for items not found</label></th>
												<td>

													<select class='form-control' name='non_stock_code'>
														<?php echo $sel_xero_items;?>
													</select>
													

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">No tax in WooCommerce</label></th>
												<td>

													<select id="sel-xero-no-tax" class='form-control col-4' name='xero_no_tax_code'>
														<?php echo $sel_xero_taxcode;?>
													</select>

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">Tax in WooCommerce</label></th>
												<td>

													<select id="sel-xero-tax" class='form-control col-4' name='xero_tax_code'>
														<?php echo $sel_xero_taxcode;?>
													</select>

												</td>
											</tr>

											<hr>

											<tr class='cont-settings'>
												<th colspan="2"><label for="">
													<input type="checkbox" name='sync_inv' value='true' <?php echo ($settings['sync_inv'] == 'true' ? " checked" : "");?>/>

													Sync inventory between <strong>Xero</strong> and <strong>WooCommerce</strong>
												</th>

											</tr>

											<tr class='cont-settings'>
												<th><label for="">Sync order from WooCommerce with status</label></th>
												<td>

													<select class='form-control col-4' name='i_match'>
														<option value="sku">WooCommerce SKU</option>
													</select>

													<p>
														The stock item's code in Xero will match this field in WooCommerce
													</p>

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">Sync pricing</label></th>
												<td>

													<input type="checkbox" class="" id="" value="true" name='i_sync_price' <?php echo ($settings['i_sync_price'] == 'true' ? " checked" : "");?> >

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">Direction</label></th>
												<td>

													<select class='form-control col-4' name='i_sync_price_direct'>
														<option value="woo">From WooCommerce to Xero</option>
														<option value="xero"> From Xero to WooCommerce</option>
													</select>

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">Sync quantity</label></th>
												<td>

													<input type="checkbox" class="" id="" value="true" name='i_sync_price' <?php echo ($settings['i_sync_qty'] == 'true' ? " checked" : "");?> >

												</td>
											</tr>

											<tr class='cont-settings'>
												<th><label for="">Direction</label></th>
												<td>

													<select class='form-control col-4' name='i_sync_qty_direct'>
														<option value="woo">From WooCommerce to Xero</option>
														<option value="xero"> From Xero to WooCommerce</option>
													</select>

												</td>
											</tr>
										
										
										
										<script>
											
											
											
											jQuery(function($) {
												doChecks();
											});
											
											jQuery("#wb-chk-active").on("click", function(){
												doChecks();
											});
											
											function doChecks(){
												if( jQuery("#wb-chk-active").is(':checked') ){
													jQuery(".cont-settings").show();
													doSelects()
												}else{
													jQuery(".cont-settings").hide();
												}
											}
											
											
											function doSelects(){
												
											   	jQuery("[name='order_code_match']").val( "<?php echo esc_attr(@$settings['order_code_match']);?>" );
												jQuery("[name='xero_use_code']").val( "<?php echo esc_attr(@$settings['xero_use_code']);?>" );
												jQuery("[name='i_match']").val( "<?php echo esc_attr(@$settings['i_match']);?>" );
												jQuery("[name='i_sync_qty_direct']").val( "<?php echo esc_attr(@$settings['i_sync_qty_direct']);?>" );
												jQuery("[name='i_sync_price_direct']").val( "<?php echo esc_attr(@$settings['i_sync_price_direct']);?>" );
												jQuery("[name='non_stock_code']").val( "<?php echo esc_attr(@$settings['non_stock_code']);?>" );
												jQuery("[name='xero_cust_code']").val( "<?php echo esc_attr(@$settings['xero_cust_code']);?>" );
											  	jQuery("#sel-xero-no-tax").val( '<?php echo esc_attr(@$settings['xero_no_tax_code']); ?>' );
												jQuery("#sel-xero-tax").val( '<?php echo esc_attr(@$settings['xero_tax_code']); ?>' );
												jQuery("#sel-xero-payment-account").val( '<?php echo esc_attr(@$settings['xero_payment_account']); ?>' );
											}
										</script>
										
										<?php

											} 

										} ?>
									</tbody>
								</table>
							
								<p class="submit">
									<input type="submit" name="submit" id="submit" class="btn btn-success" value="SAVE SETTINGS">
								</p>
							</form>
								
						</div>
						<?php echo $connect_xero; ?>
					</div>
				
					<?php if ( !isset($res_sync['status']) || get_option('wbsync_woo_qbo_sync_guid') == ''){ ?>
					<div class='row'>
						<div class='col-12'>

							<h4>
								Quick setup steps:
							</h4>
							<p>
								1. Get your Wbsync API key
							</p>
							<p>
								2. Add a Quickbooks connections to your Wbsync plugin
							</p>
							<p>
								3. Connect WooCommerce and your Quickbooks linked company
							</p>
							<p>
								4. Configure Wbsync to your liking
							</p>
							<p>
								5. Sit back and relax as orders and inventory are automatically synced
							</p>
						</div>

					</div>
					<?php } ?>
					
				</div>	
			</div>

		<?php
		
		}elseif($active_tab == 'logs'){ 
		?>


		<div class='wb-card'>
			<div class='card-body'>

				<h2>Wbsync Logs</h2>

				<table>
					<?php echo $logs;?>
				</table>
			</div>
		</div>
		

<?php
		}elseif($active_tab == 'docs'){  ?>
	
		
		<div class='wb-card'>
			<div class='card-body'>
				
				<h2>Docmentation</h2>
				
				<table>
					<?php echo $documentation;?>
				</table>
			</div>
		</div>
<?php
		}
	}
}