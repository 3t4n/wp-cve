<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://myworks.software
 * @since      1.0.0
 *
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/admin
 * @author     MyWorks Software <support@myworks.software>
 */
class MyWorks_WC_Xero_Sync_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	
	private $dlobj;
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		global $MWXS_L, $MWXS_A;
		if(class_exists('MyWorks_WC_Xero_Sync_Lib')){
			$MWXS_L = new MyWorks_WC_Xero_Sync_Lib();
		}
		
		if(class_exists('MyWorks_WC_Xero_Sync_Wc_Data_List')){			
			$this->dlobj = new MyWorks_WC_Xero_Sync_Wc_Data_List();
		}
		
		$MWXS_A = $this;
		
		# Ajax Request Page
		require_once plugin_dir_path( __FILE__ ).'ajax-actions.php';
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
		 * defined in MyWorks_WC_Xero_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MyWorks_WC_Xero_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		global $MWXS_L;
		wp_enqueue_style( $this->plugin_name.'-widget', plugin_dir_url( __FILE__ ) . 'css/wc-widget-css.css', array(), $this->version, 'all' );

		if($MWXS_L->is_plugin_admin_page()){			
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/'.$this->plugin_name.'-admin.css', array(), $this->version, 'all' );
		}
		
		if($MWXS_L->is_plugin_admin_page('connection')){
			wp_enqueue_style( $this->plugin_name.'-bootstrap-min', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), '5.2.3', 'all' );
			wp_enqueue_style( $this->plugin_name.'-connection', plugin_dir_url( __FILE__ ) . 'css/connection-page.css', array(), $this->version, 'all' );
		}

		if($this->is_include_css_js_lib('select2')){
			wp_enqueue_style( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), '4.0.13', 'all' );
		}

		if($this->is_include_css_js_lib('bootstrap-switch')){
			wp_enqueue_style( $this->plugin_name.'-bootstrap-switch', plugin_dir_url( __FILE__ ) . 'css/bootstrap-switch.css', array(), '3.3.4', 'all' );
		}

		if($this->is_include_css_js_lib('toggle-switch')){
			wp_enqueue_style( $this->plugin_name.'-toggle-switch', plugin_dir_url( __FILE__ ) . 'css/toggle-switch.css', array(), $this->version, 'all' );
		}

		if($this->is_include_css_js_lib('extra')){
			wp_enqueue_style( $this->plugin_name.'-extra', plugin_dir_url( __FILE__ ) . 'css/admin-pages-extra.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name.'-font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.css', array(), $this->version, 'all' );
		}
		
		if($this->is_include_css_js_lib('sweetalert')){
			wp_enqueue_style( $this->plugin_name.'-sweetalert', plugin_dir_url( __FILE__ ) . 'css/sweetalert.css', array(), $this->version, 'all' );
		}

		if($this->is_include_css_js_lib('datepicker')){
			# themes/base
			wp_enqueue_style( $this->plugin_name.'-jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), '1.13.2', 'all' );
		}
	}
	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in MyWorks_WC_Xero_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MyWorks_WC_Xero_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		global $MWXS_L;
		if($MWXS_L->is_plugin_admin_page()){
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/'.$this->plugin_name.'-admin.js', array( 'jquery' ), $this->version, false );
		}

		if($this->is_include_css_js_lib('select2')){
			wp_enqueue_script( $this->plugin_name.'-select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array(), '4.0.13', true );
		}

		if($this->is_include_css_js_lib('tablesorter')){
			wp_enqueue_script( $this->plugin_name.'-tablesorter', plugin_dir_url( __FILE__ ) . 'js/jquery.tablesorter.min.js', array('jquery'), '2.31.3', true );
		}

		if($this->is_include_css_js_lib('bootstrap-switch')){
			wp_enqueue_script( $this->plugin_name.'-bootstrap-switch', plugin_dir_url( __FILE__ ) . 'js/bootstrap-switch.min.js', array(), '3.3.4', true );
		}
		
		if($this->is_include_css_js_lib('chart')){
			wp_enqueue_script( $this->plugin_name.'-chart', plugin_dir_url( __FILE__ ) . 'js/chart.umd.min.js', array(), '4.2.1', true );
		}
		
		if($this->is_include_css_js_lib('sweetalert')){
			wp_enqueue_script( $this->plugin_name.'-sweetalert', plugin_dir_url( __FILE__ ) . 'js/sweetalert.min.js', array(), '2.1.2', false );
		}
		
		if($this->is_include_css_js_lib('datepicker')){
			wp_enqueue_script('jquery-ui-datepicker');
		}
	}

	# Check if lib needed in page
	private function is_include_css_js_lib($lib){
		global $MWXS_L;
		if($lib == 'select2'){
			if($MWXS_L->is_plugin_admin_page('map') || $MWXS_L->is_plugin_admin_page('settings')){
				return true;
			}
		}

		if($lib == 'tablesorter'){
			if($MWXS_L->is_plugin_admin_page('map') || $MWXS_L->is_plugin_admin_page('push') || $MWXS_L->is_plugin_admin_page('pull')){
				return true;
			}
		}

		if($lib == 'bootstrap-switch'){
			if($MWXS_L->is_plugin_admin_page('map','payment-method') || $MWXS_L->is_plugin_admin_page('settings') || $MWXS_L->is_plugin_admin_page('compatibility')){
				return true;
			}
		}

		if($lib == 'toggle-switch'){
			if($MWXS_L->is_plugin_admin_page('settings')){
				return true;
			}
		}

		if($lib == 'chart'){
			if($MWXS_L->is_plugin_admin_page('','dashboard')){
				return true;
			}
		}

		if($lib == 'extra'){
			if($MWXS_L->is_plugin_admin_page('map') || $MWXS_L->is_plugin_admin_page('settings')){
				return true;
			}
		}

		if($lib == 'sweetalert'){
			if($MWXS_L->is_plugin_admin_page('map') || $MWXS_L->is_plugin_admin_page('settings') || $MWXS_L->is_plugin_admin_page('compatibility')){
				return true;
			}
		}

		if($lib == 'datepicker'){
			if($MWXS_L->is_plugin_admin_page('push','order') || $MWXS_L->is_plugin_admin_page('push','payment')){
				return true;
			}
		}

		return false;
	}
	
	# Admin Menu
	public function create_admin_menus(){
		global $MWXS_L;
		global $wpdb;
		
		if(!class_exists('WooCommerce')) return false;
		if(!$MWXS_L->if_user_m_wc()){return false;}
		
		$parent_m_slug = 'myworks-wc-xero-sync';

		$is_license_active = $MWXS_L->is_license_active();
		
		#Main
		add_menu_page( 
			'MyWorks Sync<br><span style="font-size:10px;">Xero</span>',
			'MyWorks Sync<br><span style="font-size:10px;">Xero</span>',
			'read', 
			$parent_m_slug,
			array($this, 'admin_menu_home'),
			plugin_dir_url( __FILE__ ) . 'image/menu-icon-sync.png', 
			3
		);
		
		#Dashboard		
		add_submenu_page( 
			$parent_m_slug,
			__( 'Dashboard', 'myworks-sync-for-xero' ),
			__( 'Dashboard', 'myworks-sync-for-xero' ),
			'read',
			'myworks-wc-xero-sync',
			array($this, 'admin_menu_home')
		);				
		
		#Queue
		if($is_license_active){
			$sqm = true;
			if($sqm){
				$q_i_count = (int) $wpdb->get_var("SELECT COUNT(*) FROM `".$MWXS_L->gdtn('queue')."` WHERE `id` >0 AND `run` = 0 ");
				add_submenu_page(
					$parent_m_slug,
					__( 'Queue', 'myworks-sync-for-xero' ),
					__( 'Queue ('.$q_i_count.')', 'myworks-sync-for-xero' ),
					'read',
					'myworks-wc-xero-sync-queue',
					array($this, 'admin_menu_queue')
				);
			}
		}
		
		#Connection
		add_submenu_page(
			$parent_m_slug,
			__( 'Connection', 'myworks-sync-for-xero' ),
			__( 'Connection', 'myworks-sync-for-xero' ),
			'read',
			'myworks-wc-xero-sync-connection',
			array($this, 'admin_menu_connection')
		);
		
		if($is_license_active){
			if($MWXS_L->is_xero_connected() || $MWXS_L->is_queue_sync_e()){
				#Settings
				add_submenu_page(
					$parent_m_slug,
					__( 'Settings', 'myworks-sync-for-xero' ),
					__( 'Settings', 'myworks-sync-for-xero' ),
					'read',
					'myworks-wc-xero-sync-settings',
					array($this, 'admin_menu_settings')
				);
			}
			
			#Log
			add_submenu_page(
				$parent_m_slug,
				__( 'Log', 'myworks-sync-for-xero' ),
				__( 'Log', 'myworks-sync-for-xero' ),
				'read',
				'myworks-wc-xero-sync-log',
				array($this, 'admin_menu_log')
			);
			
			if($MWXS_L->is_xero_connected() || $MWXS_L->is_queue_sync_e()){
				#Map
				add_submenu_page(
					$parent_m_slug,
					__( 'Map', 'myworks-sync-for-xero' ),
					__( 'Map', 'myworks-sync-for-xero' ),
					'read',
					'myworks-wc-xero-sync-map',
					array($this, 'admin_menu_map')
				);
				
				#Push
				add_submenu_page(
					$parent_m_slug,
					__( 'Push', 'myworks-sync-for-xero' ),
					__( 'Push', 'myworks-sync-for-xero' ),
					'read',
					'myworks-wc-xero-sync-push',
					array($this, 'admin_menu_push')
				);
				
				#Pull
				add_submenu_page(
					$parent_m_slug,
					__( 'Pull', 'myworks-sync-for-xero' ),
					__( 'Pull', 'myworks-sync-for-xero' ),
					'read',
					'myworks-wc-xero-sync-pull',
					array($this, 'admin_menu_pull')
				);
				
				#Compatibility
				$scm = true;
				if($scm){
					add_submenu_page(
						$parent_m_slug,
						__( 'Compatibility', 'myworks-sync-for-xero' ),
						__( 'Compatibility', 'myworks-sync-for-xero' ),
						'read',
						'myworks-wc-xero-sync-compatibility',
						array($this, 'admin_menu_compatibility')
					);
				}
			}
		}
	}
	
	# Menu Functions
	private function include_admin_menu_page($page){
		$ap_xc_ra = array(			
			'connection',
			'settings',
		);
		
		global $MWXS_L;
		
		#Session - Plugin Admin Pages
		$MWXS_L->initialize_session();
		
		if($MWXS_L->is_queue_sync_e() && in_array($page,$ap_xc_ra)){
			$MWXS_L->xero_connect();
		}
		
		require_once plugin_dir_path( __FILE__ ) .'admin-page-hcj-functions.php';
		require_once plugin_dir_path( __FILE__ ) . 'partials/'.$page.'.php';

		# Hide WP Admin Notices in Plugin Pages
		myworks_woo_sync_for_xero_hide_wp_notices();
	}
	
	public function admin_menu_home(){
		$this->include_admin_menu_page('dashboard');
	}
	
	public function admin_menu_queue(){
		$this->include_admin_menu_page('queue');
	}
	
	public function admin_menu_connection(){
		$this->include_admin_menu_page('connection');
	}
	
	public function admin_menu_settings(){
		$this->include_admin_menu_page('settings');
	}
	
	public function admin_menu_log(){
		$this->include_admin_menu_page('log');
	}
	
	public function admin_menu_map(){
		$this->include_admin_menu_page('map');
	}	
	
	public function admin_menu_push(){
		$this->include_admin_menu_page('push');
	}
	
	public function admin_menu_pull(){
		$this->include_admin_menu_page('pull');
	}
	
	public function admin_menu_compatibility(){
		$this->include_admin_menu_page('compatibility');
	}

	# All Cron Schedules
	public function hook_cron_schedules($schedules){
		global $MWXS_L;
		if(!isset($schedules["MWXS_5min"])){
			$schedules["MWXS_5min"] = array(
				'interval' => 5*60,
				'display' => __('Once every 5 minutes')
			);
		}

		if(!isset($schedules["MWXS_10min"])){
			$schedules["MWXS_10min"] = array(
				'interval' => 10*60,
				'display' => __('Once every 10 minutes')
			);
		}

		if(!isset($schedules["MWXS_15min"])){
			$schedules["MWXS_15min"] = array(
				'interval' => 15*60,
				'display' => __('Once every 15 minutes')
			);
		}

		if(!isset($schedules["MWXS_30min"])){
			$schedules["MWXS_30min"] = array(
				'interval' => 30*60,
				'display' => __('Once every 30 minutes')
			);
		}

		if(!isset($schedules["MWXS_60min"])){
			$schedules["MWXS_60min"] = array(
				'interval' => 60*60,
				'display' => __('Once every 1 hour')
			);
		}

		if(!isset($schedules["MWXS_360min"])){
			$schedules["MWXS_360min"] = array(
				'interval' => 360*60,
				'display' => __('Once every 6 hour')
			);
		}

		return $schedules;
	}

	# Set Queue Cron
	public function mwxs_queue_cron_set(){
		global $MWXS_L;
		$qcit = $MWXS_L->get_option('mw_wc_xero_sync_queue_cron_interval_time');

		if(empty($qcit)){
			$qcit = 'MWXS_5min';
		}

		$qc_hook_name = 'mw_wc_xero_sync_queue_cron_hook';

		$is_set_cron = true;
		if($is_set_cron){
			if(!wp_next_scheduled($qc_hook_name)){		
				wp_schedule_event(time(), $qcit, $qc_hook_name);
			}
		}

		add_action($qc_hook_name, array($this,'mwxs_process_queue'));
	}

	# Set Inventory Pull Cron - Using this for all pull now
	public function mwxs_ivnt_pull_cron_set(){
		global $MWXS_L;
		$s_rt_pull_items = $MWXS_L->get_option('mw_wc_xero_sync_rt_pull_items');
		if(!empty($s_rt_pull_items)){
			$s_rt_pull_items = explode(',',$s_rt_pull_items);
		}

		if(is_array($s_rt_pull_items) && !empty($s_rt_pull_items)){
			if(in_array('Inventory',$s_rt_pull_items) || in_array('Product',$s_rt_pull_items) || in_array('Payment',$s_rt_pull_items)){
				$ipit = $MWXS_L->get_option('mw_wc_xero_sync_ivnt_pull_interval_time');
				if(empty($ipit)){
					$ipit = 'MWXS_5min';
				}

				$ip_hook_name = 'mw_wc_xero_sync_ivnt_pull_hook';

				$is_set_cron = true;
				if($is_set_cron){
					if(!wp_next_scheduled($ip_hook_name)){		
						wp_schedule_event(time(), $ipit, $ip_hook_name);
					}
				}

				add_action($ip_hook_name, array($this,'mwxs_process_ivnt_pull'));
			}
		}		
	}

	#Process Queue
	public function mwxs_process_queue(){
		global $MWXS_L;
		global $wpdb;
		#$MWXS_L->save_log(array('type'=>'Queue','title'=>'Queue Sync WP Cron','details'=>'Queue Sync Function Executed.','status'=>2));
		
		# Individual Item Type IDs
		$order_ids = array();
		$payment_ids = array();
		$refund_ids = array();

		$customer_ids = array();
		$product_ids = array();
		$variation_ids = array();

		$table = $MWXS_L->gdtn('queue');
		$sql = "SELECT * FROM `{$table}` WHERE `run` = 0 ORDER BY `added_date` ASC";
		$queue_data = $MWXS_L->get_data($sql);

		if(is_array($queue_data) && !empty($queue_data)){
			# Xero Connection
			$MWXS_L->xero_connect();
			
			$log_txt = "Queue Sync Run Started".PHP_EOL;
			$queue_total_count = count($queue_data);
			$queue_run_count = 0;

			# Individual Run Counts
			$order_count = 0;
			$payment_count = 0;
			$refund_count = 0;

			$customer_count = 0;
			$product_count = 0;
			$variation_count = 0;
			
			$dfp = 'Y-m-d H:i:s';
			foreach($queue_data as $data){
				$log_type = '';

				$row_id = $data['id'];
				$item_type = $data['item_type'];
				$item_id = (int) $data['item_id'];
				$item_action = $data['item_action'];

				$success = 0;
				$run = 0;
				$status = '';

				if($item_type=='Order' && $item_action=='Push'){
					if(!in_array($item_id,$order_ids)){
						$order_ids[] = $item_id;
						$log_type = 'Order';
						$order_count++;
						$queue_run_count++;
						$run = 1;

						if($this->hook_order_add(array('order_id'=>$item_id,'f_q_f'=>true))){
							$success = 1;
						}

						$status = ($success)?'s':'e';
					}
					
					$run_datetime = $MWXS_L->now($dfp);
					$wpdb->query($wpdb->prepare("UPDATE `{$table}` SET `run` = %d, `run_datetime` = %s, `status` = %s WHERE `id` = %d",1,$run_datetime,$status,$row_id));
				}
				
				if($item_type=='Payment' && $item_action=='Push'){
					if(!in_array($item_id,$payment_ids)){
						$payment_ids[] = $item_id;
						$log_type = 'Payment';
						$payment_count++;
						$queue_run_count++;
						$run = 1;

						if($this->hook_payment_add(array('order_id'=>$item_id,'f_q_f'=>true))){
							$success = 1;
						}

						$status = ($success)?'s':'e';
					}
					
					$run_datetime = $MWXS_L->now($dfp);
					$wpdb->query($wpdb->prepare("UPDATE `{$table}` SET `run` = %d, `run_datetime` = %s, `status` = %s WHERE `id` = %d",1,$run_datetime,$status,$row_id));					
				}

				if($item_type=='Refund' && $item_action=='Push'){
					
				}

				if($item_type=='Customer' && $item_action=='Push'){
					if(!in_array($item_id,$customer_ids)){
						$customer_ids[] = $item_id;
						$log_type = 'Customer';
						$customer_count++;
						$queue_run_count++;
						$run = 1;

						if($this->hook_user_add(array('user_id'=>$item_id,'f_q_f'=>true))){
							$success = 1;
						}

						$status = ($success)?'s':'e';
					}

					$run_datetime = $MWXS_L->now($dfp);
					$wpdb->query($wpdb->prepare("UPDATE `{$table}` SET `run` = %d, `run_datetime` = %s, `status` = %s WHERE `id` = %d",1,$run_datetime,$status,$row_id));
				}

				if($item_type=='Product' && $item_action=='Push'){
					if(!in_array($item_id,$product_ids)){
						$product_ids[] = $item_id;
						$log_type = 'Product';
						$product_count++;
						$queue_run_count++;
						$run = 1;

						if($this->hook_product_add(array('product_id'=>$item_id,'f_q_f'=>true))){
							$success = 1;
						}

						$status = ($success)?'s':'e';
					}

					$run_datetime = $MWXS_L->now($dfp);
					$wpdb->query($wpdb->prepare("UPDATE `{$table}` SET `run` = %d, `run_datetime` = %s, `status` = %s WHERE `id` = %d",1,$run_datetime,$status,$row_id));
				}
				
				if($item_type=='Variation' && $item_action=='Push'){
					if(!in_array($item_id,$variation_ids)){
						$variation_ids[] = $item_id;
						$log_type = 'Variation';
						$variation_count++;
						$queue_run_count++;
						$run = 1;

						if($this->hook_variation_add(array('variation_id'=>$item_id,'f_q_f'=>true))){
							$success = 1;
						}

						$status = ($success)?'s':'e';
					}

					$run_datetime = $MWXS_L->now($dfp);
					$wpdb->query($wpdb->prepare("UPDATE `{$table}` SET `run` = %d, `run_datetime` = %s, `status` = %s WHERE `id` = %d",1,$run_datetime,$status,$row_id));
				}

			}

			if($order_count){$log_txt.="Total Order Sync Run: $order_count".PHP_EOL;}
			if($payment_count){$log_txt.="Total Payment Sync Run: $payment_count".PHP_EOL;}
			if($refund_count){$log_txt.="Total Refund Sync Run: $refund_count".PHP_EOL;}

			if($customer_count){$log_txt.="Total Customer Sync Run: $customer_count".PHP_EOL;}
			if($product_count){$log_txt.="Total Product Sync Run: $product_count".PHP_EOL;}
			if($variation_count){$log_txt.="Total Variation Sync Run: $variation_count".PHP_EOL;}

			$log_txt.="Total Items in Queue: $queue_total_count".PHP_EOL;
			$log_txt.="Queue Sync Run Ended";

			if($queue_run_count > 0){
				$MWXS_L->save_log(array('type'=>'Queue','title'=>'Queue Sync Run','details'=>$log_txt,'status'=>2));
			}
		}
	}
	
	# Process Inventory Pull - Using this for all pull now
	public function mwxs_process_ivnt_pull(){
		global $MWXS_L;
		$s_rt_pull_items = $MWXS_L->get_option('mw_wc_xero_sync_rt_pull_items');
		if(!empty($s_rt_pull_items)){
			$s_rt_pull_items = explode(',',$s_rt_pull_items);
		}

		if(is_array($s_rt_pull_items) && !empty($s_rt_pull_items)){
			if(in_array('Inventory',$s_rt_pull_items) || in_array('Product',$s_rt_pull_items) || in_array('Payment',$s_rt_pull_items)){
				$MWXS_L->xero_connect();

				if(in_array('Inventory',$s_rt_pull_items)){
					$MWXS_L->X_Pull_Inventory();
				}
				
				if(in_array('Product',$s_rt_pull_items)){
					$MWXS_L->X_Pull_Product();
				}
				
				if(in_array('Payment',$s_rt_pull_items)){
					$MWXS_L->X_Pull_Payment();
				}
			}		
		}
	}
	
	# Admin Footer
	public function mwxs_admin_footer(){
		global $MWXS_L;
		require_once plugin_dir_path( __FILE__ ) .'admin-page-hcj-functions.php';
		myworks_woo_sync_for_xero_wc_admin_pages_footer_content();
	}

	#Meta Boxes
	public function mwxs_add_meta_boxes(){
		global $MWXS_L;
		global $woocommerce, $order, $post;

		#Order Edit Page
		add_meta_box( 'mb_xs_mwxs', __('Xero Status','myworks-sync-for-xero'), array($this,'meta_box_content_mb_xs_mwxs'), 'shop_order', 'side', 'core' );
	}

	# Order Edit Page Xero Status Meta Box Content
	public function meta_box_content_mb_xs_mwxs(){
		global $MWXS_L;
		global $woocommerce,$post;

		$order_id = (int) $post->ID;
		if($order_id > 0){
			$MWXS_L->xero_connect();

			if(!$MWXS_L->is_xero_connected()){
				echo '<p>Xero Not Connected</p>';
				return '';
			}
			
			$invoice_data = $MWXS_L->get_wc_order_details_from_order($order_id,$post);
			if(is_array($invoice_data) && !empty($invoice_data)){
				$order_sync_as = $MWXS_L->get_xero_order_sync_as($order_id,$invoice_data);

				if($order_sync_as == 'Invoice'){
					$xero_invoice = $MWXS_L->check_xero_invoice_get_obj($invoice_data);
					if(is_object($xero_invoice) && !empty($xero_invoice)){
						echo '<p class="mw_qbo_sync_status_p">';
						echo '<strong>Status:</strong> &nbsp;&nbsp;';
						echo '<span class="mw_qbo_sync_status_span mw_qbo_sync_status_paid">Synced</span>';
						echo '</p>';

						# Xero Invoice Number
						echo '<p class="mw_qbo_sync_number_p">';
						echo '<strong>Number:</strong>&nbsp;';
						echo '<span class="mw_qbo_sync_status_span mw_qbo_sync_status_info">'.$MWXS_L->escape($xero_invoice->getInvoiceNumber()).'</span>';
						echo '</p>';

						# View in Xero
						$vix_href = $MWXS_L->get_xero_view_invoice_link_by_id($xero_invoice->getInvoiceID());
						#$vix_title = 'Xero Invoice Id #'.$xero_invoice->getInvoiceID().' - Click to view it in Xero';
						$vix_title = 'Click to view it in Xero';
						
						echo '<p>';
						echo '<a target="_blank" href="'.esc_url($vix_href).'" title="'.$MWXS_L->escape($vix_title).'"><button class="button" type="button">View</button></a>';
						echo '</p>';

					}else{
						echo '<p class="mw_qbo_sync_status_p">';
						echo '<strong>Status:</strong> &nbsp;&nbsp;';
						echo '<span class="mw_qbo_sync_status_span mw_qbo_sync_status_due">Not Synced</span>';
						echo '</p>';
					}
				}

				if($order_sync_as == 'Quote'){
					
				}
			}
		}				
	}

	# WooCommerce Order Page Custom Column - Xero Sync Status
	public function mwxs_add_woocommerce_order_page_columns($columns){
		global $MWXS_L;
		$columns['c_xs_mwxs'] = __( 'Xero Status','myworks-sync-for-xero');
		return $columns;
	}

	public function mwxs_woocommerce_order_page_columns_content($column){
		global $MWXS_L;
		global $post, $woocommerce, $the_order;

		$order_id = 0;
		if(is_object($post) && !empty($post)){
			if(isset($post->ID) && isset($post->post_type) && $post->post_type == 'shop_order'){
				$order_id = (int) $post->ID;
			}
		}

		switch($column){
			case 'c_xs_mwxs' :
				if($order_id > 0){
					$MWXS_L->initialize_session();
					$wc_order_id_num_list = (array) $MWXS_L->get_session_val('wc_order_id_num_list',array());
					
					$wc_inv_no = $MWXS_L->get_woo_ord_number_from_order($order_id);
					#$wc_inv_no = '';
					
					if(!isset($wc_order_id_num_list[$order_id])){
						$wc_order_id_num_list[$order_id] = (!empty($wc_inv_no))?$wc_inv_no:$order_id;
					}
					
					$MWXS_L->set_session_val('wc_order_id_num_list',$wc_order_id_num_list);

					echo '<div id="c_xs_'.$MWXS_L->escape($order_id).'" class="c_xs_data">...</div>';
				}
				break;
		}
	}

	#Init Hooks
	private function call_init_hooks(){
		
	}

	private function call_admin_init_hooks(){
		# Order List Page Columns
		add_filter( 'manage_edit-shop_order_columns', array($this,'mwxs_add_woocommerce_order_page_columns'),11);
		add_action( 'manage_shop_order_posts_custom_column' , array($this,'mwxs_woocommerce_order_page_columns_content'), 10, 2 );	

		# Admin Footer
		add_action('admin_footer', array($this,'mwxs_admin_footer'));

		#Meta Boxes
		add_action( 'add_meta_boxes', array($this,'mwxs_add_meta_boxes') );
	}
	
	# Hook Functions
	public function mwxs_hook_init(){
		global $MWXS_L;
		
		$MWXS_L->init();
		$this->call_init_hooks();
		$this->mwxs_queue_cron_set();
		$this->mwxs_ivnt_pull_cron_set();
	}

	public function mwxs_hook_admin_init(){
		global $MWXS_L;
		$this->call_admin_init_hooks();
	}
	
	# Sync Hook Functions
	
	public function hook_user_add($user_info,$from_order=false,$customer_data=array()){
		if(!class_exists('WooCommerce')) return false;
		global $MWXS_L;
		
		$sync_instant = false;
		$manual = false;
		
		if(is_array($user_info)){
			$user_id = (int) $user_info['user_id'];
			if(isset($user_info['f_p_p'])){
				$sync_instant = true;
				$manual = true;
			}

			if(isset($user_info['f_q_f'])){
				$sync_instant = true;
				if(isset($user_info['manual']) && $user_info['manual']){
					$manual = true;
				}
			}
		}else{
			$user_id = (int) $user_info;
		}
		
		if(!$manual && !$from_order && !$MWXS_L->check_if_real_time_push_enable_for_item('Customer')){
			return false;
		}
		
		if($user_id > 0){
			$user_data = get_userdata($user_id);
			$wc_user_role = $MWXS_L->get_wc_user_role_by_id($user_id,$user_data);
			
			# Sync all orders to one Xero Customer
			if($MWXS_L->option_checked('mw_wc_xero_sync_s_all_orders_to_one_xero_customer')){
				$io_cs = true;
				
				if(!empty($wc_user_role)){
					$aotc_rcm_data = get_option('mw_wc_xero_sync_aotc_rcm_data');
					if(is_array($aotc_rcm_data) && !empty($aotc_rcm_data)){
						if(isset($aotc_rcm_data[$wc_user_role]) && !empty($aotc_rcm_data[$wc_user_role])){
							if($aotc_rcm_data[$wc_user_role] != 'Individual'){
								$io_cs = false;								
							}
						}
					}
				}
				
				if(!$io_cs){
					return false;
				}
			}
			
			# Wc user roles as customer - Disabled for now
			$is_sync_user_role = true;
			
			if(!$is_sync_user_role && isset($user_data->roles) && is_array($user_data->roles)){
				$sc_roles = $this->get_option('mw_wc_xero_sync_wc_user_roles_as_customer');
				if(!empty($sc_roles)){
					$sc_roles = explode(',',$sc_roles);
					if(is_array($sc_roles) && count($sc_roles)){
						foreach($sc_roles as $sr){
							if(in_array($sr,$user_data->roles)){
								$is_sync_user_role = true;
								break;
							}
						}
					}
				}

				if(!$is_sync_user_role){
					if(in_array('customer',$user_data->roles)){
						$is_sync_user_role = true;						
					}
				}
			}
			
			if(!$manual && !$is_sync_user_role){
				return false;
			}
			
			#Queue-Add
			if(!$sync_instant){
				$MWXS_L->wx_queue_add('Customer',$user_id,'Push',2);				
				return;
			}
			
			if(empty($customer_data)){
				$customer_data = $MWXS_L->get_wc_customer_info($user_id,$user_data,$manual);
			}
			
			if(!empty($customer_data)){
				if(!$xero_contact_id = $MWXS_L->if_xero_customer_exists($customer_data)){
					$MWXS_L->xero_connect();
					#Add					
					$ContactID = $MWXS_L->X_Add_Customer($customer_data);			
					if(!empty($ContactID) && strlen($ContactID) == 36){
						return true;
					}
				}else{
					#Update
				}
			}
		}		
		
	}
	
	public function hook_order_add($order_info){
		if(!class_exists('WooCommerce')) return false;
		global $MWXS_L;
		
		$sync_instant = false;
		$manual = false;
		
		if(is_array($order_info)){
			$order_id = (int) $order_info['order_id'];
			if(isset($order_info['f_p_p'])){
				$sync_instant = true;
				$manual = true;
			}

			if(isset($order_info['f_q_f'])){
				$sync_instant = true;
				if(isset($order_info['manual']) && $order_info['manual']){
					$manual = true;
				}
			}
		}else{
			$order_id = (int) $order_info;
			#$MWXS_L->save_log(array('type'=>'Order','title'=>'Order Hook Add Test #'.$order_id,'details'=>'Test','status'=>2));
		}
		
		if(!$manual && !$MWXS_L->check_if_real_time_push_enable_for_item('Order')){
			return false;
		}
		
		if($order_id > 0){
			# Minimum Order ID Restriction
			$ord_min_id  = (int) $MWXS_L->get_option('mw_wc_xero_sync_block_syncing_orders_before_id');
			if($ord_min_id > 0 && $order_id < $ord_min_id){
				if($manual){
					$MWXS_L->save_log(array('type'=>'Order','title'=>'Create Order Error #'.$order_id,'details'=>'Order sync not allowed for ID less than #'.$ord_min_id,'status'=>0));
				}
				return false;
			}
			
			$order = get_post($order_id);			
			if(!is_object($order) || empty($order)){
				if($manual){					
					$MWXS_L->save_log(array('type'=>'Order','title'=>'Create Order Error #'.$order_id,'details'=>'Woocommerce order not found.','status'=>0));
				}
				return false;
			}
			
			if($order->post_type!='shop_order'){
				if($manual){
					$MWXS_L->save_log(array('type'=>'Order','title'=>'Create Order Error #'.$order_id,'details'=>'Woocommerce order is not valid.','status'=>0));
				}
				return false;
			}
			
			if($order->post_status=='auto-draft'){
				return false;
			}
			
			if(!$manual && $order->post_status=='draft'){
				return false;
			}

			# Order Status Restriction (Realtime)
			$only_sync_status = $MWXS_L->get_option('mw_wc_xero_sync_s_order_when_status_in');
			if(!empty($only_sync_status)){
				$only_sync_status = explode(',',$only_sync_status);
			}

			if(!$manual && (!is_array($only_sync_status) || (is_array($only_sync_status) && !in_array($order->post_status,$only_sync_status)))){
				return false;
			}
			
			# $0 Order Restriction
			if($MWXS_L->option_checked('mw_wc_xero_sync_do_not_sync_0_orders')){
				$_order_total = (float) get_post_meta($order_id,'_order_total',true);
				if($_order_total == 0 || $_order_total < 0){
					if($manual){
						$MWXS_L->save_log(array('type'=>'Order','title'=>'Create Order Error #'.$order_id,'details'=>'Syncing Order amount 0 not allowed in settings','status'=>0));
					}
					return false;
				}
			}
			
			$invoice_data = $MWXS_L->get_wc_order_details_from_order($order_id,$order,$manual);
			if(!empty($invoice_data)){			

				$order_sync_as = $MWXS_L->get_xero_order_sync_as($order_id,$invoice_data);				
				#Queue-Add
				if(!$sync_instant){
					$extra = array('O_S_A'=>$order_sync_as);
					$MWXS_L->wx_queue_add('Order',$order_id,'Push',1,'',$extra);
					return;
				}
				
				$xero_connect_f_called = false;
				
				$wc_cus_id = (int) $MWXS_L->get_array_isset($invoice_data,'wc_cus_id',0,false);
				$wc_user_role = $MWXS_L->get_array_isset($invoice_data,'wc_user_role','');
				$is_guest = ($wc_cus_id > 0)?false:true;

				$customer_xrt_check = ($is_guest)?true:false;
				#$customer_xrt_check = true;

				$is_sync_customer = true;
				
				# Xero Connection
				if($customer_xrt_check){				
					$MWXS_L->xero_connect();
					$xero_connect_f_called = true;
				}
				
				$X_ContactID = $MWXS_L->get_xero_customer_for_order_sync($invoice_data);
				
				if(!empty($X_ContactID)){
					$is_sync_customer = false;
				}

				if($is_sync_customer && $MWXS_L->option_checked('mw_wc_xero_sync_s_all_orders_to_one_xero_customer')){
					if(!empty($wc_user_role)){		
						$aotc_rcm_data = get_option('mw_wc_xero_sync_aotc_rcm_data');
						if(is_array($aotc_rcm_data) && !empty($aotc_rcm_data)){
							if(isset($aotc_rcm_data[$wc_user_role]) && !empty($aotc_rcm_data[$wc_user_role])){
								if($aotc_rcm_data[$wc_user_role] != 'Individual'){
									$is_sync_customer = false;
								}
							}
						}
					}
				}

				if($is_sync_customer){
					if(!$xero_connect_f_called){
						$MWXS_L->xero_connect();
						$xero_connect_f_called = true;
					}

					if($wc_cus_id > 0){
						$customer_data = $MWXS_L->get_wc_customer_info($wc_cus_id);
						$customer_data['order_id'] = $order_id;
						$X_ContactID = $MWXS_L->check_save_get_xero_customer_id($customer_data);						
					}else{
						$customer_data = $MWXS_L->get_wc_customer_info_from_order($order_id);
						$X_ContactID = $MWXS_L->check_save_get_xero_guest_id($customer_data);						
					}
				}
				
				if(empty($X_ContactID)){
					$MWXS_L->save_log(array('type'=>'Order','title'=>'Create Order Error #'.$order_id,'details'=>'Xero customer not found.','status'=>0));
					return false;

				}

				$invoice_data['X_ContactID'] = $X_ContactID;
				
				# Xero Connection
				if(!$xero_connect_f_called){
					$MWXS_L->xero_connect();
				}				

				if($order_sync_as == 'Invoice'){
					$return = false;
					$_payment_method = $MWXS_L->get_array_isset($invoice_data,'_payment_method','',true);
					$_order_currency = $MWXS_L->get_array_isset($invoice_data,'_order_currency','',true);
					$_paid_date = $MWXS_L->get_array_isset($invoice_data,'_paid_date','');

					$pm_map_data = $MWXS_L->get_mapped_payment_method_data($_payment_method,$_currency_applicable);

					$enable_payment = (int) $MWXS_L->get_array_isset($pm_map_data,'enable_payment',0,false);
					$aps_order_status = $MWXS_L->get_array_isset($pm_map_data,'aps_order_status','',true);
					$is_artificial_payment = false;
					if($enable_payment && !empty($aps_order_status) && $order->post_status == $aps_order_status){
						$is_artificial_payment = true;
					}

					$sync_artificial_payment = false;

					$xero_invoice_object = $MWXS_L->check_xero_invoice_get_obj($invoice_data);
					if(!$xero_invoice_object){	
						#Add
						$InvoiceID = $MWXS_L->X_Add_Invoice($invoice_data);			
						if(!empty($InvoiceID) && strlen($InvoiceID) == 36){
							# Payment
							if(!empty($_paid_date) && $manual && isset($order_info['f_p_p'])){
								$this->hook_payment_add(array('order_id'=>$order_id,'f_p_p'=>true));
							}else{
								$sync_artificial_payment = true;
							}
							
							$return = true;
						}
					}else{
						#Update
						
						$sync_artificial_payment = true;
					}

					# Artificial Payment Sync
					if($sync_artificial_payment){
						if(!$xero_payment_id = $MWXS_L->if_xero_payment_exists($invoice_data,$xero_invoice_object)){
							$PaymentId = $MWXS_L->X_Add_Payment($invoice_data,$xero_invoice_object);		
							if(!empty($PaymentId) && strlen($PaymentId) == 36){
								#-> Payment Added
							}
						}
					}

					return $return;
				}
				
				if($order_sync_as == 'Quote'){
					#->
				}
			}
		}
	}
	
	public function hook_order_update($post_ID, $post_after, $post_before){
		
	}
	
	public function hook_order_cancelled($order_id){
		
	}
	
	public function hook_refund_add($order_sync_info=0,$refund_id=0){
		
	}
	
	public function hook_payment_add($order_info){
		if(!class_exists('WooCommerce')) return false;
		global $MWXS_L;
		
		$sync_instant = false;
		$manual = false;
		
		if(is_array($order_info)){
			$order_id = (int) $order_info['order_id'];
			if(isset($order_info['f_p_p'])){
				$sync_instant = true;
				$manual = true;
			}

			if(isset($order_info['f_q_f'])){
				$sync_instant = true;
				if(isset($order_info['manual']) && $order_info['manual']){
					$manual = true;
				}
			}
		}else{
			$order_id = (int) $order_info;
		}
		
		if(!$manual && !$MWXS_L->check_if_real_time_push_enable_for_item('Payment')){
			return false;
		}
		
		if($order_id > 0){
			$order = get_post($order_id);
			
			if(!is_object($order) || empty($order)){
				if($manual){					
					$MWXS_L->save_log(array('type'=>'Payment','title'=>'Create Payment Error for Order #'.$order_id,'details'=>'Woocommerce order not found.','status'=>0));
				}
				return false;
			}
			
			if($order->post_type!='shop_order'){
				if($manual){
					$MWXS_L->save_log(array('type'=>'Payment','title'=>'Create Payment Error for Order #'.$order_id,'details'=>'Woocommerce order is not valid.','status'=>0));
				}
				return false;
			}
			
			if($order->post_status=='auto-draft'){
				return false;
			}
			
			if(!$manual && $order->post_status=='draft'){
				return false;
			}

			$invoice_data = $MWXS_L->get_wc_order_details_from_order($order_id,$order,$manual);
			if(!empty($invoice_data)){
				
				$artificial_payment_allowed = false;
				$_paid_date = $MWXS_L->get_array_isset($invoice_data,'_paid_date','');
				
				if(!$artificial_payment_allowed && empty($_paid_date)){
					if($manual){
						$MWXS_L->save_log(array('type'=>'Order','title'=>'Create Payment Error for Order #'.$order_id,'details'=>'Invalid Payment - Payment date is empty.','status'=>0));
					}					
					return false;
				}
				
				# Payment enabled for gateway check
				$_payment_method = $MWXS_L->get_array_isset($invoice_data,'_payment_method','',true);
				if(empty($_payment_method)){
					if($manual){
						$MWXS_L->save_log(array('type'=>'Payment','title'=>'Create Payment Error for Order #'.$order_id,'details'=>'Payment method not found.','status'=>0));
					}
					return false;
				}

				#$_payment_method_title = $MWXS_L->get_array_isset($invoice_data,'_payment_method_title','',true);
				$_order_currency = $MWXS_L->get_array_isset($invoice_data,'_order_currency','',true);
				if(empty($_order_currency)){
					if($manual){
						$MWXS_L->save_log(array('type'=>'Payment','title'=>'Create Payment Error for Order #'.$order_id,'details'=>'Order currency not found.','status'=>0));
					}
					return false;
				}
				
				$_currency_applicable = $_order_currency;

				$pm_map_data = $MWXS_L->get_mapped_payment_method_data($_payment_method,$_currency_applicable);
				$enable_payment = (int) $MWXS_L->get_array_isset($pm_map_data,'enable_payment',0,false);
				$_order_total = (float) $MWXS_L->get_array_isset($invoice_data,'_order_total',0,false);

				$is_valid_payment = false;
				if($enable_payment == 1 && $_order_total>0){
					$is_valid_payment = true;
				}
				
				if(!$is_valid_payment){
					if($manual){
						$MWXS_L->save_log(array('type'=>'Payment','title'=>'Create Payment Error for Order #'.$order_id,'details'=>'Payment not enabled or invalid payment amount for gateway:'.$_payment_method.', currency:'.$_currency_applicable,'status'=>0));
					}
					return false;
				}

				$order_sync_as = $MWXS_L->get_xero_order_sync_as($order_id,$invoice_data);
				if($order_sync_as != 'Invoice'){
					return false;
				}

				#Queue-Add
				if(!$sync_instant){
					$extra = null;
					$MWXS_L->wx_queue_add('Payment',$order_id,'Push',0,'',$extra);
					return;
				}

				# Xero Connection
				$MWXS_L->xero_connect();

				# Xero Invoice check before payment sync
				$xero_invoice_object = $MWXS_L->check_xero_invoice_get_obj($invoice_data);				
				
				if(!$xero_invoice_object){
					$MWXS_L->save_log(array('type'=>'Order','title'=>'Create Payment Error for Order #'.$order_id,'details'=>'Xero invoice not found.','status'=>0));
					return false;
				}

				if($xero_invoice_object->getStatus() != 'AUTHORISED'){
					$MWXS_L->save_log(array('type'=>'Order','title'=>'Create Payment Error for Order #'.$order_id,'details'=>'Xero invoice not ready to accept payment.','status'=>0));
					return false;
				}

				if(!$xero_payment_id = $MWXS_L->if_xero_payment_exists($invoice_data,$xero_invoice_object)){
					#Add
					$PaymentId = $MWXS_L->X_Add_Payment($invoice_data,$xero_invoice_object);		
					if(!empty($PaymentId) && strlen($PaymentId) == 36){
						return true;
					}
				}
			}
		}
	}
	
	public function hook_product_add($product_info){
		if(!class_exists('WooCommerce')) return false;
		global $MWXS_L;
		global $wpdb;
		
		$sync_instant = false;
		$manual = false;
		
		if(is_array($product_info)){
			$product_id = (int) $product_info['product_id'];
			if(isset($product_info['f_p_p'])){
				$sync_instant = true;
				$manual = true;
			}
			
			if(isset($product_info['f_q_f'])){
				$sync_instant = true;
				if(isset($product_info['manual']) && $product_info['manual']){
					$manual = true;
				}
			}
		}else{
			$product_id = (int) $product_info;
		}
		
		if(!$manual && !$MWXS_L->check_if_real_time_push_enable_for_item('Product')){
			return false;
		}
		
		if($product_id>0){
			#Queue-Add
			if(!$sync_instant){
				$MWXS_L->wx_queue_add('Product',$product_id,'Push',2);
				return;
			}
			
			$_product = wc_get_product($product_id);
			
			if(empty($_product)){
				$MWXS_L->save_log(array('type'=>'Product','title'=>'Create Product Error #'.$product_id,'details'=>'Woocommerce product not found','status'=>0));
				return false;
			}

			if($_product->post->post_type!='product'){
				if($manual){					
					$MWXS_L->save_log(array('type'=>'Product','title'=>'Create Product Error #'.$product_id,'details'=>'Woocommerce product is not valid.','status'=>0));
				}
				return false;
			}
			
			if($_product->post->post_status=='auto-draft'){
				return false;
			}
			
			if(!$manual && $_product->post->post_status=='draft'){
				return false;
			}
			
			# Skip Parent Variation Product
			$cvp_q = $wpdb->prepare("SELECT post_parent FROM {$wpdb->posts} WHERE post_type = 'product_variation' AND post_parent=%d",$product_id);
			$chk_v_parent = $MWXS_L->get_row($cvp_q);
			if(is_array($chk_v_parent) && !empty($chk_v_parent)){
				return false;
			}
			
			$product_data = $MWXS_L->get_wc_product_info($product_id,$_product,$manual);
			if(!empty($product_data)){
				if(!isset($product_data['_manage_stock'])){
					return false;
				}
				
				if(!$xero_product_id = $MWXS_L->if_xero_product_exists($product_data)){
					$MWXS_L->xero_connect();
					#Add					
					$ItemID = $MWXS_L->X_Add_Product($product_data);
					if(!empty($ItemID) && strlen($ItemID) == 36){
						return true;
					}
				}else{
					#Update
				}
				
			}
		}
		
		return false;
	}
	
	public function hook_variation_add($variation_info){
		if(!class_exists('WooCommerce')) return false;
		global $MWXS_L;
		global $wpdb;
		
		$sync_instant = false;
		$manual = false;

		if(is_array($variation_info)){
			$variation_id = (int) $variation_info['variation_id'];
			if(isset($variation_info['f_p_p'])){
				$sync_instant = true;
				$manual = true;
			}
			
			if(isset($variation_info['f_q_f'])){
				$sync_instant = true;
				if(isset($variation_info['manual']) && $variation_info['manual']){
					$manual = true;
				}
			}
		}else{
			$variation_id = (int) $variation_info;
		}
		
		if(!$manual && !$MWXS_L->check_if_real_time_push_enable_for_item('Variation')){
			return false;
		}

		if($variation_id>0){
			#Queue-Add
			if(!$sync_instant){
				$MWXS_L->wx_queue_add('Variation',$variation_id,'Push',2);
				return;
			}

			$_variation = get_post($variation_id);

			if(empty($_variation)){
				$MWXS_L->save_log(array('type'=>'Variation','title'=>'Create Variation Error #'.$variation_id,'details'=>'Woocommerce variation not found','status'=>0));
				return false;
			}
			
			if($_variation->post_type!='product_variation'){
				if($manual){					
					$MWXS_L->save_log(array('type'=>'Variation','title'=>'Create Variation Error #'.$variation_id,'details'=>'Woocommerce variation is not valid.','status'=>0));
				}
				return false;
			}

			if($_variation->post_status=='auto-draft'){
				return false;
			}
			
			if(!$manual && $_variation->post_status=='draft'){
				return false;
			}
			
			$variation_data = $MWXS_L->get_wc_variation_info($variation_id,$_variation,$manual);
			if(!empty($variation_data)){
				if(!isset($variation_data['_manage_stock'])){
					return false;
				}
				
				if(!$xero_product_id = $MWXS_L->if_xero_product_exists($variation_data)){
					$MWXS_L->xero_connect();
					#Add					
					$ItemID = $MWXS_L->X_Add_Product($variation_data);
					if(!empty($ItemID) && strlen($ItemID) == 36){
						return true;
					}
				}else{
					#Update
				}
			}
		}

		return false;
	}
	
	public function hook_product_stock_update($ivnt_sync_info){
		
	}
	
	public function hook_variation_stock_update($ivnt_sync_info){
		
	}
	
	# Delete Variation Mapping
	public function hook_delete_variation_mapping($variation_id){
		if(!class_exists('WooCommerce')) return;
		global $post_type;
		if ( $post_type != 'product_variation' ) return false;

		$variation_id = (int) $variation_id;
		if($variation_id > 0){
			global $MWXS_L;
			global $wpdb;

			$vmt = $MWXS_L->gdtn('map_variations');
			$wpdb->query($wpdb->prepare("DELETE FROM `{$vmt}` WHERE `W_V_ID` = %d",$variation_id));
		}
	}

	# Delete Product Mapping
	public function hook_delete_product_mapping($product_id){
		if(!class_exists('WooCommerce')) return;
		global $post_type;
		if ( $post_type != 'product' ) return false;

		$product_id = (int) $product_id;
		if($product_id > 0){
			global $MWXS_L;
			global $wpdb;

			$pmt = $MWXS_L->gdtn('map_products');
			$wpdb->query($wpdb->prepare("DELETE FROM `{$pmt}` WHERE `W_P_ID` = %d",$product_id));
		}
	}
	
}