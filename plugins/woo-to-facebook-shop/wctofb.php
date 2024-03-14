<?php
/*
Plugin Name: Social Commerce for WooCommerce
Plugin URI: https://wordpress.org/plugins/woo-to-facebook-shop/
Description: Now you can start your facebook shop free. With WooCommerce to facebook shop plugin you can easily sync or unsync your products from your woocommerce website to your facebook fan page very quickly. No manual import or Export required everything in real time.
Author: premiumthemes
Version: 2.5.4
Author URI: https://www.premium-themes.co/
WC requires at least: 2.0.0
WC tested up to: 4.0.1
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define ('wctofb_version', '2.5.4');
// Global Variables
global $bulk_action;
$bulk_action = '';
$wctofb_register = 'https://www.premium-themes.co';
$wctofb_site_url = 'https://fbshop.premium-themes.co';
/****** Stop plugin activation untill woocommerce is not activated ******/
add_action( 'admin_init', 'wctofb_child_plugin_has_parent_plugin' );
function wctofb_child_plugin_has_parent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) ); 
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}
/****** Check WooCommerce installed and Active so WCtoFB plugin could get installed ******/
function wctofb_woocommerce_exists() {
	if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
	$run_once = esc_attr(get_option('wctofb_runonce'));
	if (!$run_once){ // for very first installation.?>
	<div class="notice notice-info "><p>
	<?php _e( '<strong>Woocommerce to Facebook Shop</strong>: plugin has been activated. The Installed version is '.wctofb_version, 'WCtoFB' ); update_option( "wctofb_pg_version", wctofb_version );?>
	</p></div>
	<?php update_option('wctofb_runonce',true);
	}
	}
	// check woocommerce active
	if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
	// WooCommerce is installed.		
	} else {
	// WooCommerce is not yet installed. ?>
	<div class="notice notice-error is-dismissible"><p>
	<?php _e('<strong>Woocommerce to Facebook Shop</strong>: required plugin WooCommerce is Missing. <a href="'. esc_url( network_admin_url('plugin-install.php?s=WooCommerce&tab=search' ) ) .'" title="Install WooCommerce">Click here</a> to Install &amp; Activate.', 'WCtoFB' );?>
	</p></div>
	<?php }	
}
add_action( 'admin_notices', 'wctofb_woocommerce_exists' );
/****** Integrate settings links on plugin page on activation ******/
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wctofb_action_links' );
function wctofb_action_links( $links ) {
	global $wctofb_site_url,$wctofb_register;
	$links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=wc-settings&tab=settings_tab_wctofb') ) .'">Settings</a>';
	if(esc_attr(get_option('wctofb_apikey_success'))=='' ||  esc_attr(get_option('wctofb_apikey_success'))=='0'){
	$links[] = '<a href="'. esc_url($wctofb_register.'/setup-wizard/?shop='.get_bloginfo('url')) .'">Get Key</a>';
	}
	return $links;
}
}
/****** Create option when plugin install ******/
function wctofb_install(){
	global $wpdb;
	// create a api option
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'wctofb';
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		product_id bigint(250) NOT NULL,
		product_status varchar(250) NOT NULL,
		sync_status int(10) NOT NULL DEFAULT '0',
		activity_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY id (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'wctofb_apikey_success');
	add_option( 'wctofb_api');
	flush_rewrite_rules();
	}
register_activation_hook(__FILE__,'wctofb_install');
function wctofb_recurrence_interval( $schedules ) {
 
    $schedules['every_two_minutes'] = array(
            'interval'  => 120,
            'display'   => __( 'Every 2 Minutes', 'textdomain' )
    );
     
    return $schedules;
}
add_filter( 'cron_schedules', 'wctofb_recurrence_interval' );
function wctofbcronstarter_activation() {
	if( !wp_next_scheduled( 'wctofbcronjob' ) ) {  
		$timestamp = strtotime( date('H:i:s'));  
		wp_schedule_event($timestamp, 'every_two_minutes', 'wctofbcronjob' );  
	}
}
register_activation_hook(__FILE__, 'wctofbcronstarter_activation');
function wctofb_repeat_function() {
	global $wpdb; global $post;
	$table_name = $wpdb->prefix . 'wctofb';
	$check_products = $wpdb->get_results("SELECT * FROM $table_name");
	$offset = count($check_products);
	$my_query = query_posts(array('post_type'=> 'product', 'post_status' => 'publish','posts_per_page' =>300,'offset'            => $offset,'order'=>'ASC'));
	if ( have_posts() ) :
		while (have_posts()): the_post();
		global $product;
		if( ($product->is_type( 'simple' ) || $product->is_type('external')) && $product->get_regular_price()!=''){
			$product_id = get_the_ID();
			$wpdb->insert( $table_name, array( 'product_id' => esc_sql($product_id),'product_status' => 'unsync' ) );
		}
		elseif( $product->is_type( 'grouped' )){
			$product_id = get_the_ID();
			$wpdb->insert( $table_name, array( 'product_id' => esc_sql($product_id),'product_status' => 'unsync'  ) );
		}
		elseif( $product->is_type( 'variable' ) && $product->get_variation_regular_price('min',true)!=''){
			$product_id = get_the_ID();
			$wpdb->insert( $table_name, array( 'product_id' => esc_sql($product_id),'product_status' => 'unsync'  ) );
		}
		endwhile;
		else :
			wp_clear_scheduled_hook('wctofbcronjob');
		endif;
}
add_action ('wctofbcronjob', 'wctofb_repeat_function');
/****** Create WCtoFB tab under woocommerce setting  ******/
if( is_admin() && ! empty ( $_SERVER['PHP_SELF'] ) && 'upload.php' !== basename( $_SERVER['PHP_SELF'] ) ) {
    function wctofb_admin_load_styles_and_scripts() {
        wp_enqueue_media();
    }
    add_action( 'admin_enqueue_scripts', 'wctofb_admin_load_styles_and_scripts' );
}
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
function wctofb_woocommerce_settings_saved() { 
global $current_tab;
if($current_tab == "settings_tab_wctofb"){
echo '<style>#message, .first_time {  display: none; }</style>';
}
}; 
add_action( 'woocommerce_settings_saved', 'wctofb_woocommerce_settings_saved', 10, 0 ); 
class WC_Settings_Tab_Wctofb {
	public static function wctofb_init() {
		add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::wctofb_add_settings_tab', 50 );
		add_action( 'woocommerce_settings_tabs_settings_tab_wctofb', __CLASS__ . '::wctofb_settings_tab' );
		add_action( 'woocommerce_update_options_settings_tab_wctofb', __CLASS__ . '::wctofb_update_settings' );
	}
	// add tab
	public static function wctofb_add_settings_tab( $settings_tabs ) {
		$settings_tabs['settings_tab_wctofb'] = __( 'WCtoFB', 'woocommerce-settings-tab-wctofb' ); 
		return $settings_tabs;
	}
	// save tab setting
	public static function wctofb_settings_tab() {
	global $wctofb_site_url,$wctofb_register;
		woocommerce_admin_fields( self::wctofb_get_settings() );
		if(esc_attr(get_option('wctofb_apikey_success'))=='' ||  esc_attr(get_option('wctofb_apikey_success'))=='0'){ 
		?>
		<div class="notice notice-error is-dismissible first_time"><p>
		<?php _e( 'Please <a href="' . esc_url( $wctofb_register.'/setup-wizard/' ) . '" title="Woocommerce to Facebook Shop" target="_blank">Register here</a> to obtain your API key &amp; enable the Facebook Shop.', 'wctofb' );?>
		</p></div>
		<?php }
		if(esc_attr( get_option('wctofb_apikey_success')) == "1" ){  ?>
		<p><?php echo '<a href="'.esc_url( $wctofb_site_url.'/my-account/fan-page').'" title="Woocommerce to Facebook Shop" target="_blank"><img src="'.plugins_url( "images/Starter.jpg", __FILE__).'"></a>';?></p>
         <a href="<?php echo $wctofb_site_url;?>/my-account/fan-page?sync=now" target="_blank"><img src="<?php echo plugins_url( "images/button-sync.jpg", __FILE__ );?>" ></a>
		<?php 
	
	} elseif(esc_attr( get_option('wctofb_apikey_success')) == "2" ){ ?>
		<p><?php echo '<a href="'.esc_url( $wctofb_site_url.'/my-account/fan-page').'" title="Woocommerce to Facebook Shop" target="_blank"><img src="'.plugins_url( "images/Pro.jpg", __FILE__).'"></a>';?></p>
        <a href="<?php echo $wctofb_site_url;?>/my-account/fan-page?sync=now" target="_blank"><img src="<?php echo plugins_url( "images/button-sync.jpg", __FILE__ );?>" ></a>
	<?php 
	}
	 elseif(esc_attr( get_option('wctofb_apikey_success')) == "3" ){ ?>
		<p><?php echo '<a href="'.esc_url( $wctofb_site_url.'/my-account/fan-page').'" title="Woocommerce to Facebook Shop" target="_blank"><img src="'.plugins_url( "images/Premium.jpg", __FILE__).'"></a>';?></p>
        <a href="<?php echo $wctofb_site_url;?>/my-account/fan-page?sync=now" target="_blank"><img src="<?php echo  plugins_url( "images/button-sync.jpg", __FILE__ );?>" ></a>
	<?php
	 }	
	
	}
	// update tab value
	public static function wctofb_update_settings() {
		global $wpdb;global $wctofb_site_url;
		woocommerce_update_options( self::wctofb_get_settings() );
		$wctofb_api = esc_attr(get_option('wctofb_api'));
		if(!empty($wctofb_api)){
		$jsondata = array("apikey"=>$wctofb_api);
		$sendjson = json_encode($jsondata);
		$url = $wctofb_site_url."/apicalls/wp/plugin_api_verification";
		$apisucces = wp_remote_post( $url, array(
		'headers' => array( 'Content-Type' => 'application/json' ),
		'method' => 'POST',
		'timeout' => 200,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'sslverify'=> true,
		'body' => $sendjson,
		'cookies' => array()) );
		if ( is_wp_error( $apisucces ) ) {
		$error = $apisucces->get_error_message();
		$apimessage = 'Unable to verify the API key. Please try again';
		echo '<div class="notice-error notice is-dismissible"><p>'.$apimessage.'</p></div>';
		}else{
		$apiresponse = wp_remote_retrieve_body($apisucces);
		 $apimessage = json_decode($apiresponse);
		$apimessage = (int)$apimessage->{'message'};
		}
		if ($apimessage == (int)$apimessage)
		{
		update_option('wctofb_apikey_success', $apimessage);
		update_option('wctofb_api', $wctofb_api);
		}
		}else{
		update_option('wctofb_apikey_success','');
		update_option('wctofb_api', '');
		}
		
		$wctofb_apikey_success = esc_attr( get_option('wctofb_apikey_success') );
		?>
		<?php if($wctofb_apikey_success =="1" || $wctofb_apikey_success =="2" || $wctofb_apikey_success =="3"){?>
		<div id="deletesuccess" class="updated notice is-dismissible">
		<p><?php _e( 'Your key has been verified successfully now you can start sending your products from WooCommerce to Facebook Fan Page.', 'WCtoFB' ); ?></p>
		</div>
		<?php }elseif ($wctofb_apikey_success == "4" ){ ?>
				<div class="notice notice-error is-dismissible"><p>
				<?php _e('Invalid API key. Please insert valid Key', 'wctofb' ); ?>
				</p></div>
			<?php }elseif ($wctofb_apikey_success == "5" ){ ?>
				<div class="updated notice is-dismissible"><p>
				<?php _e('API key activate successfully. <a href="' . esc_url( $wctofb_site_url.'/my-account/fan-page' ) . '" title="Woocommerce to Facebook Shop" target="_blank">Click here</a> to complete the process.', 'wctofb' ); ?>
				</p></div>
			<?php }elseif ($wctofb_apikey_success == "" ){ ?>
					<div class="notice notice-error is-dismissible"><p>
					<?php _e( 'Please <a href="' . esc_url( $wctofb_site_url.'/my-account/' ) . '" title="Woocommerce to Facebook Shop" target="_blank">Register here</a> to obtain your API key &amp; enable the Facebook Shop plugin.', 'wctofb' );?>
					</p></div>	
					<?php }  
	}
	// create a tab fields
	public static function wctofb_get_settings($section = null) {
		global $sections;
		$settings = array(
			'section_title' => array(
				'name'     => __( 'Social Commerce for WooCommerce', 'wctofb' ),
				'type'     => 'title',
				'desc'     => '',
				'id'       => 'wc_settings_tab_wctofb_section_title'
			),
			'title' => array(
				'name' => __( 'Insert Api Key', 'wctofb' ),
				'type' => 'password',
				'id'   => 'wctofb_api',
			),
			'section_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_tab_wctofb_section_end'
			)
		);
		return apply_filters( 'wc_settings_tab_wctofb_settings', $settings );
	}
}
WC_Settings_Tab_Wctofb::wctofb_init();
}
/****** plugin css ******/
function load_wctofb_wp_admin_style($hook) {
        wp_enqueue_style( 'wctofb_wp_admin_css', plugins_url('css/wctofb_setting.css', __FILE__) );
}
add_action( 'admin_enqueue_scripts', 'load_wctofb_wp_admin_style' );
/****** Action for json generate and send product on facebook******/
add_action( 'init', function(){
	global $wp_rewrite;
    add_rewrite_endpoint('woocommerce_wctofb_feeds',array(EP_PERMALINK, EP_PAGES),true);
    add_rewrite_endpoint( 'woocommerce_wctofb_feeds', EP_ALL ,true);
    $wp_rewrite->flush_rules();
} );
add_action( 'template_redirect', function(){
	global $wpdb;
    if($getproductfeeds = get_query_var('woocommerce_wctofb_feeds')){
		include('wctofb_woocommerce_feeds.php');
        die();
    }
});
add_filter( 'request', function($vars=array()){
    if(isset ( $vars['woocommerce_wctofb_feeds'] ) && empty ( $vars['woocommerce_wctofb_feeds'] )){
        $vars['woocommerce_wctofb_feeds'] = 'wctofb_feeds';
    }
    return $vars;
});
/****** Action for show sync or unsync columns head in admin ******/
function wctofb_products_extra_columns($columns){
	global $post;
	global $items;
	$poststatus  = get_post_status();
	if($poststatus == "trash"){
	$newcolumns = array();
	}else {
		$newcolumns = array(
		 "cb"       		=> "<input type  = \"checkbox\" />",
		 "product_sync"    => '<span class="wc-sync-unsync"><img width="40px" src="'.plugins_url("images/sync-unsync.png", __FILE__ ).'"></span>',
	);
	}
	$columns = array_merge($newcolumns, $columns);
	return $columns; 
}
if(esc_attr( get_option('wctofb_apikey_success')) =="1" || esc_attr( get_option('wctofb_apikey_success')) =="2" || esc_attr( get_option('wctofb_apikey_success')) =="3"){
add_filter("manage_edit-product_columns", "wctofb_products_extra_columns");
}
/****** Show sync or unsync in front of each product in admin ******/
function wctofb_products_extra_columns_content($column){
	global $post,$items;
	$selected_productid="";
	$product_id = $post->ID;
	$poststatus  = get_post_status();
	if($poststatus != "trash"){
	switch ($column)
	{
		case "product_sync":
		global $wpdb;		
		$table_name = $wpdb->prefix . 'wctofb';
		$selected_products = $wpdb->get_results("SELECT * FROM $table_name where product_id = $product_id");
		foreach($selected_products as $sync_products) {
			$selected_productid = $sync_products->product_id;
			$selected_product_status = $sync_products->product_status;
		}
		if($selected_productid == $items){echo '<img width="55px" src="'.plugins_url( 'images/unsync.png', __FILE__ ).'">'; 
		} else {
			 if($selected_product_status=='unsync'){
			echo '<img width="55px" src="'.plugins_url( 'images/unsync.png', __FILE__ ).'">';	 
				 }
			 elseif($selected_product_status=='sync'){
			 echo '<img width="40px" src="'.plugins_url( 'images/sync.png', __FILE__ ).'">';}
			 elseif($selected_product_status=='delete'){
			 echo '<img width="40px" src="'.plugins_url( 'images/deleted.png', __FILE__ ).'">';}
			 }
		break;	
	}
}
}
if(esc_attr( get_option('wctofb_apikey_success')) =="1" || esc_attr( get_option('wctofb_apikey_success')) =="2" || esc_attr( get_option('wctofb_apikey_success')) =="3"){
add_action("manage_posts_custom_column",  "wctofb_products_extra_columns_content");
}
/****** Sync and Unsync add on product bulk action ******/
if(esc_attr( get_option('wctofb_apikey_success')) =="1" || esc_attr( get_option('wctofb_apikey_success')) =="2" || esc_attr( get_option('wctofb_apikey_success')) =="3"){
	// Sync Products with Bulk Action
	add_action('admin_footer-edit.php', 'wctofb_sync_prodcuts_bulk');
	function wctofb_sync_prodcuts_bulk() {
		global $post_type;
		$poststatus  = get_post_status();
		if($post_type == 'product' && $poststatus != "trash" ) { ?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('<option>').val('sync').text('<?php _e( 'WCtoFB Sync', 'wctofb' ); ?>').appendTo("select[name='action']");
					jQuery('<option>').val('sync').text('<?php _e('WCtoFB Sync', 'wctofb' )?>').appendTo("select[name='action2']");
				});
			</script>
		<?php
		}
	}
// Un-Sync Products with Bulk Action 
	add_action('admin_footer-edit.php', 'wctofb_unsync_prodcuts_bulk');
	function wctofb_unsync_prodcuts_bulk() {
		global $post_type;
		$poststatus  = get_post_status();
		if($post_type == 'product' && $poststatus != "trash") { ?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('<option>').val('unsync').text('<?php _e('WCtoFB Unsync', 'wctofb' )?>').appendTo("select[name='action']");
					jQuery('<option>').val('unsync').text('<?php _e('WCtoFB Unsync', 'wctofb' )?>').appendTo("select[name='action2']");
				});
			</script>
		<?php
		}
	}
}
add_action('load-edit.php', 'wctofb_facebook_bulk_action');
function wctofb_facebook_bulk_action() {
	global $typenow, $pagenow, $bulk_action,$poststatus;
	$post_type = $typenow;
	//Action request		
	if (isset($_REQUEST['action']) && $_REQUEST['action']!=-1) {
		  $bulk_action = esc_attr($_REQUEST['action']);
		
	}elseif(isset($_REQUEST['action2']) && $_REQUEST['action']==-1) {
		  $bulk_action = esc_attr($_REQUEST['action2']);
	} 	
		// remove sync product when add to trash from action 
		if('trash' == $bulk_action) {
			if($post_type == 'product') {
				if(isset($_REQUEST['post'])) {
					$product_id = array_map('intval', $_REQUEST['post']);
					global $wpdb;	
					$items= array();		
					$table_name = $wpdb->prefix . 'wctofb';
					foreach($product_id as $post_id) {
						$items[] = array('product_id'=>$post_id);
						$wpdb->delete( $table_name, array( 'product_id' => esc_sql($post_id) ) );
					}
					$jsonaction ='unsync';
					wctofb_sync_button_action($jsonaction,$items); 
				}
		}
	}
	
	/****** Bulk action on sync / Un Sync plubish products from action ******/
	if($post_type == 'product') {
		if(isset($_REQUEST['post'])) {
			$product_ids = array_map('intval', $_REQUEST['post']);
			global $wpdb;	 global $woocommerce;		
			$table_name = $wpdb->prefix . 'wctofb';
			$i=0;
			$syncarray=$unsyncarray= array();
			if(is_array($product_ids)){
			foreach($product_ids as $product_id) {
				$poststatus = get_post_status($product_id);
				$product = wc_get_product( $product_id );
				if(($product->is_type( 'simple' ) || $product->is_type( 'external' )) && $product->get_regular_price()!='' && $poststatus=='publish' && $bulk_action == 'sync'){
				array_push($syncarray,$product_id);
				}
				elseif($product->is_type( 'variable' ) && $product->get_variation_regular_price('min',true)!='' && $poststatus=='publish' && $bulk_action == 'sync' ){
				array_push($syncarray,$product_id);	
				}
				elseif($product->is_type( 'grouped' ) && $poststatus=='publish' && $bulk_action == 'sync' ){
				array_push($syncarray,$product_id);	
				}
				elseif(($product->is_type( 'simple' ) || $product->is_type( 'external' ) || $product->is_type( 'variable' )|| $product->is_type( 'grouped' )) && $bulk_action == 'unsync'){
					array_push($unsyncarray,$product_id);
				}
			}
			}
			if (!empty($syncarray) && is_array($syncarray)) {
				$jsonaction ='sync';
				$response = wctofb_sync_button_action($jsonaction,$syncarray);
			}
			elseif(!empty($unsyncarray) && is_array($unsyncarray)) {
				$jsonaction ='unsync';
				$response = wctofb_sync_button_action($jsonaction,$unsyncarray);
			}
			if ($bulk_action == 'unsync' || $bulk_action == 'sync'){
				if ( $response == 'success' ) {
				if(!empty($syncarray)){
					foreach($syncarray as $syncsingle){
						$results_check = $wpdb->get_results("SELECT * FROM $table_name WHERE product_id='$syncsingle'", OBJECT );
						if(count($results_check) == 0){
						$wpdb->insert( $table_name, array( 'product_id' => esc_sql($syncsingle),'product_status' => 'sync'));
						}
						else{
						$wpdb->update( $table_name, array( 'product_status' => 'sync'),array( 'product_id' => esc_sql($syncsingle)) );	
						}
					}
				}
				if(!empty($unsyncarray)){
					foreach($unsyncarray as $unsyncsingle){
						$wpdb->update($table_name,array('product_status'=>'unsync'),array('product_id'=>esc_sql($unsyncsingle)));
					}
				}
				 setcookie( "singleproductupdate",'success');
				}else{
				setcookie( "singleproductupdatefail",'fail');	
				}
					$sendback = add_query_arg(
					array('post_type'=>'product',
						'paged'=>intval($_REQUEST['paged']),
						'success' => 1,
						'task' => $bulk_action), $sendback 
					);
					$productids=explode(",",$sendback);    
					$total_productids = count($productids);
					wp_redirect($sendback);
						exit();
				}
		}
	}	
}
/****** Admin Notices for Bulk Action ******/
 add_action('admin_notices', 'wctofb_bulk_action_notices');
function wctofb_bulk_action_notices() { 
	global $post_type, $pagenow, $bulk_action,$fbtask;
		if (isset($_COOKIE['singleproductupdate'])){?>
		<div class="updated notice is-dismissible">
		<p><?php _e( 'You have successfully updated your <strong>Facebook shop</strong>.', 'wctofb' );	?></p> 
		</div>
		<?php 		
		setcookie("singleproductupdate","",time()-36000);
		}
		if (isset($_COOKIE['singleproductupdatefail'])){?>
		<div class="notice notice-error is-dismissible">
		<p><?php _e('Unable to updated on your <strong>Facebook shop</strong>. Please try again.','wctofb');?></p>					
        </div>
		<?php 		
		setcookie("singleproductupdatefail","",time()-36000);
		}
		
} 
/****** Remove product from facebook shop on product move to trash ******/
function wctofb_trash_function() {
	global $post; 
	if($post->post_type== 'product' ) {
		global $wpdb;			
		$table_name = $wpdb->prefix . 'wctofb';
		$productid_trash=$post->ID;  
		$wpdb->delete( $table_name, array( 'product_id' => esc_sql($productid_trash) ) );
		$items[] = array('product_id'=>$productid_trash);
		$jsonaction ='unsync';
		$response = wctofb_sync_button_action($jsonaction,$items);
		if ( $response == 'success' ) {
   		 setcookie( "singleproductupdate",'success');
  		}else{
		setcookie( "singleproductupdatefail",'fail');	
		}
	}
}
add_action('wp_trash_post', 'wctofb_trash_function');
/****** Remove product from facebook shop on product move to draft, pending, private, schedule, publish ******/
function wctofb_func_Save( $post_id ) {
	if(!isset($_POST['order_id'])):
	global $wpdb;			
		$table_name = $wpdb->prefix . 'wctofb';
  if (get_post_type($post_id) == 'product') {
	if(get_post_status($post_id) == "draft" || get_post_status($post_id) == "pending" || get_post_status($post_id) == "private" || get_post_status($post_id) == "future"){
		$productdelete = $wpdb->delete( $table_name, array( 'product_id' => esc_sql($post_id) ));
		if($productdelete==1){
			$jsonaction ='unsync';
			$items[] = array('product_id'=>$post_id);
			$response = wctofb_sync_button_action($jsonaction,$items);
			if ( $response == 'success' ) {
			setcookie( "singleproductupdate",'success');
			}else{
			setcookie( "singleproductupdatefail",'fail');	
			}
		}
	} 
	if(get_post_status($post_id) == "publish"){
		$syncarray = array();
		$product = wc_get_product( $post_id );
		if(($product->is_type( 'simple' ) || $product->is_type( 'external' )) && $product->get_regular_price()!='' ){
		$results_check = $wpdb->get_results( "SELECT  * FROM $table_name WHERE product_id='$post_id'", OBJECT );
		if(count($results_check) == 0){
		$wpdb->insert( $table_name, array( 'product_id' => esc_sql($post_id),'product_status' => 'unsync'));
		}
		$jsonaction ='sync';
		array_push($syncarray,$post_id);	
		$response = wctofb_sync_button_action($jsonaction,$syncarray);
		if ( $response == 'success' ) {
   		 setcookie( "singleproductupdate",'success');
		 $wpdb->update( $table_name,array( 'product_status' => 'sync'),array( 'product_id' => esc_sql($post_id)));	
  		}
		else{
		setcookie( "singleproductupdatefail",'fail');	
		}
		}
		elseif($product->is_type( 'variable' ) && $product->get_variation_regular_price('min',true)!='' ){
		$results_check = $wpdb->get_results( "SELECT  * FROM $table_name WHERE product_id='$post_id'", OBJECT );
		if(count($results_check) == 0){
		$wpdb->insert( $table_name, array( 'product_id' => esc_sql($post_id),'product_status' => 'unsync'));
		}
		$jsonaction ='sync';
		array_push($syncarray,$post_id);	
		$response = wctofb_sync_button_action($jsonaction,$syncarray);
		if ( $response == 'success' ) {
   		 setcookie( "singleproductupdate",'success');
		 $wpdb->update($table_name, array( 'product_status' => 'sync'),array( 'product_id' => esc_sql($post_id)));	
  		}else{
		setcookie( "singleproductupdatefail",'fail');	
		}
		}
		elseif($product->is_type( 'grouped' )){
		$results_check = $wpdb->get_results( "SELECT  * FROM $table_name WHERE product_id='$post_id'", OBJECT );
		if(count($results_check) == 0){
		$wpdb->insert( $table_name, array( 'product_id' => esc_sql($post_id),'product_status' => 'unsync'));
		}
		$jsonaction ='sync';
		array_push($syncarray,$post_id);	
		$response = wctofb_sync_button_action($jsonaction,$syncarray);
		if ( $response == 'success' ) {
   		 setcookie( "singleproductupdate",'success');
		 $wpdb->update( $table_name,array( 'product_status' => 'sync'),array( 'product_id' => esc_sql($post_id)));	
  		}else{
			setcookie( "singleproductupdatefail",'fail');	
		}
		}
		
	} 
  }
  endif;
 }
add_action( 'save_post', 'wctofb_func_Save', 10);
add_action( 'wp_insert_post' , 'wctofb_func_Save' , '99', 2 );
/****** Action for json generate and send product on facebook******/
function wctofb_sync_button_action($jsonaction,$productsarray){	
global $wctofb_site_url;
if($jsonaction=='unsync'){
	$store_url =  get_site_url();
	$jsondata = array("action" => 'unsync',"products"=>$productsarray,"store_url"=>$store_url,"apikey"=>"verified");
}elseif($jsonaction=='sync'){
global $wpdb;
	$content="";
	$cartpage_url =  wc_get_cart_url();
	$store_url =  get_site_url();
	$sendstoreproducts = array(); $store_currency = array();
	$store_currency['currency_symbol'] = get_woocommerce_currency_symbol();
	$store_currency['price_format'] = get_woocommerce_price_format();
	$store_currency['decimal_separator']  = wc_get_price_decimal_separator();
    $store_currency['thousand_separator'] = wc_get_price_thousand_separator();
	$store_currency['decimals'] = wc_get_price_decimals();
	$store_language =  get_locale();
	if($productsarray!=''){
	foreach($productsarray as $singleproductid) {
		$includeids[] =$singleproductid;
	}
	global $post;
		$my_query = query_posts(array('post__in' => $includeids,'post_type'=> 'product', 'post_status' => 'publish','posts_per_page' =>-1));
		while (have_posts()): the_post();
			global $product; $attributes=$allattributes=$allvariations=$product_stock_status=$productrawvariations=$producttype=$childproductsids=$cat_name=$allimages=$product_sku=$product_stock_quantity=$product_stock_manage=$product_stock_backorders=$product_stock_sold_individually=$product_weight=$product_length=$product_height=$product_width=$regularprice=$saleprice=$product_long_description=$product_short_description=$product_detail_link=$product_title=$product_id='';
			//get detail for simple / external products
		if( ($product->is_type( 'simple' ) || $product->is_type('external')) && $product->get_regular_price()!=''){
			if($product->is_type( 'simple' )){$producttype = 'simple';}
			if($product->is_type( 'external' )){$producttype = 'external';}
				if ( method_exists( $product, 'get_stock_status' ) ) {
				$product_stock_status = $product->get_stock_status(); // For version 3.0+
				} else {
				$product_stock_status = $product->stock_status; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_stock_quantity' ) ) {
				$product_stock_quantity= $product->get_stock_quantity();// For version 3.0+
				} else {
				$product_stock_quantity= $product->stock_quantity; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_manage_stock' ) ) {
				$product_stock_manage= $product->get_manage_stock(); // For version 3.0+
				} else {
				$product_stock_manage= $product->manage_stock; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_backorders' ) ) {
				$product_stock_backorders=$product->get_backorders(); // For version 3.0+
				} else {
				$product_stock_backorders = $product->backorders; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_sold_individually' ) ) {
				$product_stock_sold_individually= $product->get_sold_individually(); // For version 3.0+
				} else {
				$product_stock_sold_individually= $product->sold_individually; // Older than version 3.0
				}
				$product_weight= $product->get_weight();
				$product_length = $product->get_length();
				$product_height = $product->get_height();
				$product_width = $product->get_width();
				$product_title = get_the_title();
				$product_sku = $product->get_sku();
				$product_id = get_the_ID();
				$args = array( 'taxonomy' => 'product_cat',);
				$terms = wp_get_post_terms($post->ID,'product_cat', $args);
				$count = count($terms); 
				if ($count > 0) {
				$cat_id=$image_gallery_link='';
				$cat_name=$allimages=array();
				foreach ($terms as $term) {
				$cat_name[] =  $term->name;
				}
				}
				$product_detail_link = get_post_permalink( $product_id );
				$product_short_description = preg_replace("/\[(.*?)\]/i",'',apply_filters( 'the_excerpt', get_the_excerpt()));
				$product_long_description = preg_replace("/\[(.*?)\]/i", '', apply_filters( 'the_content', get_the_content()));
				$featured_image_small = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'medium', false, '' );	
				$featured_image_large = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'full', false, '' );
				if(!empty($featured_image_small[0])){
				$allimages[] = array("srcurl"=>$featured_image_small[0],"isthumb"=>"1","islarge"=>"0","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");				}
				if(!empty($featured_image_large[0])){
				$allimages[] = array("srcurl"=>$featured_image_large[0],"isthumb"=>"0","islarge"=>"1","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");}
				if ( method_exists( $product, 'get_gallery_image_ids' ) ) {
				$attachment_ids = $product->get_gallery_image_ids(); // For version 3.0+
				} elseif ( method_exists( $product, 'get_gallery_attachment_ids' ) ) {
				$attachment_ids = $product->get_gallery_attachment_ids(); // For version 2.6 to 3.0
				}else {
				$attachment_ids = $product->get_gallery_attachment_ids; // Older than version 2.6
				}
				if(!empty($attachment_ids)){
				foreach( $attachment_ids as $attachment_id ) 
				{
				$allimages[] = array("srcurl"=>wp_get_attachment_url( $attachment_id ),"isthumb"=>"0","islarge"=>"0","isgallery"=>"1","isvariation"=>"0",'variation_id'=>"0");
				}
				}
				
				$raw_regularprice=$raw_saleprice=$regularprice=$saleprice='';
				if ( method_exists( $product, 'get_regular_price' ) ) {
				$raw_regularprice = $product->get_regular_price(); // For version 3.0+
				} else {
				$raw_regularprice = $product->regular_price; // Older than version 3.0
				}
				if($raw_regularprice!=''){
				$regularprice = $raw_regularprice;
				} 
				if($product->is_on_sale()){
				if ( method_exists( $product, 'get_sale_price' ) ) {
				$raw_saleprice = $product->get_sale_price(); // For version 3.0+
				} else {
				$raw_saleprice = $product->sale_price; // Older than version 3.0
				}	
				if($raw_saleprice!=''){
				$saleprice = $raw_saleprice;
				}	
				}
			}
			//get detail for grouped products
			elseif( $product->is_type( 'grouped' )){
				$product_single_inventory= array();
				if($product->is_type( 'grouped' )){$producttype = 'grouped';}
				if ( method_exists( $product, 'get_stock_status' ) ) {
				$product_stock_status = $product->get_stock_status(); // For version 3.0+
				} else {
				$product_stock_status = $product->stock_status; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_stock_quantity' ) ) {
				$product_stock_quantity= $product->get_stock_quantity();// For version 3.0+
				} else {
				$product_stock_quantity= $product->stock_quantity; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_manage_stock' ) ) {
				$product_stock_manage= $product->get_manage_stock(); // For version 3.0+
				} else {
				$product_stock_manage= $product->manage_stock; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_backorders' ) ) {
				$product_stock_backorders=$product->get_backorders(); // For version 3.0+
				} else {
				$product_stock_backorders = $product->backorders; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_sold_individually' ) ) {
				$product_stock_sold_individually= $product->get_sold_individually(); // For version 3.0+
				} else {
				$product_stock_sold_individually= $product->sold_individually; // Older than version 3.0
				}
				$product_title = get_the_title();
				$product_sku = $product->get_sku();
				$product_id = get_the_ID();
				$args = array( 'taxonomy' => 'product_cat',);
				$terms = wp_get_post_terms($post->ID,'product_cat', $args);
				$count = count($terms); 
				if ($count > 0) {
				$cat_id=$image_gallery_link='';
				$cat_name=array();
				foreach ($terms as $term) {
				$cat_name[] =  $term->name;
				}
				}
				$allimages=array();
				$product_detail_link = get_post_permalink($product_id);
				$product_short_description = preg_replace("/\[(.*?)\]/i",'',apply_filters( 'the_excerpt', get_the_excerpt()));
				$product_long_description = preg_replace("/\[(.*?)\]/i", '', apply_filters( 'the_content', get_the_content()));
				$featured_image_small = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'medium', false, '' );	
				$featured_image_large = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'full', false, '' );
				if(!empty($featured_image_small[0])){
				$allimages[] = array("srcurl"=>$featured_image_small[0],"isthumb"=>"1","islarge"=>"0","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");				}
				if(!empty($featured_image_large[0])){
				$allimages[] = array("srcurl"=>$featured_image_large[0],"isthumb"=>"0","islarge"=>"1","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");}
				if ( method_exists( $product, 'get_gallery_image_ids' ) ) {
				$attachment_ids = $product->get_gallery_image_ids(); // For version 3.0+
				} elseif ( method_exists( $product, 'get_gallery_attachment_ids' ) ) {
				$attachment_ids = $product->get_gallery_attachment_ids(); // For version 2.6 to 3.0
				}else {
				$attachment_ids = $product->get_gallery_attachment_ids; // Older than version 2.6
				}
				if(!empty($attachment_ids)){
				foreach( $attachment_ids as $attachment_id ) 
				{
				$allimages[] = array("srcurl"=>wp_get_attachment_url( $attachment_id ),"isthumb"=>"0","islarge"=>"0","isgallery"=>"1","isvariation"=>"0",'variation_id'=>"0");
				}
				}
				$raw_regularprice=$raw_saleprice=$regularprice=$saleprice='';
				$child_prices=$childproductsids= array();
			    foreach ( $product->get_children() as $child_id ) {
			   $child_prices[] = get_post_meta( $child_id, '_price', true ); 
			   $childproductsids[]= $child_id;
			   }
			    $regularprice = min(array_filter($child_prices));
			}
			//get detail for variable products
			elseif( $product->is_type( 'variable' ) && $product->get_variation_regular_price('min',true)!=''){
				if($product->is_type( 'variable' )){$producttype = 'variable';}
				$product_title = get_the_title();
				$product_sku = $product->get_sku();
				$product_id = get_the_ID();
				$args = array( 'taxonomy' => 'product_cat',);
				$terms = wp_get_post_terms($post->ID,'product_cat', $args);
				$count = count($terms);
				if ($count > 0) {
				$cat_id=$image_gallery_link=$saleprice=$regularprice="";
				$cat_name=array();
				foreach ($terms as $term) {
				$cat_name[] =  $term->name;
				}
				}
				
				$allimages=$product_single_shipping=array();
				if ( method_exists( $product, 'get_stock_status' ) ) {
				$product_stock_status = $product->get_stock_status(); // For version 3.0+
				} else {
				$product_stock_status = $product->stock_status; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_stock_quantity' ) ) {
				$product_stock_quantity= $product->get_stock_quantity();// For version 3.0+
				} else {
				$product_stock_quantity= $product->stock_quantity; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_manage_stock' ) ) {
				$product_stock_manage= $product->get_manage_stock(); // For version 3.0+
				} else {
				$product_stock_manage= $product->manage_stock; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_backorders' ) ) {
				$product_stock_backorders=$product->get_backorders(); // For version 3.0+
				} else {
				$product_stock_backorders = $product->backorders; // Older than version 3.0
				}
				if ( method_exists( $product, 'get_sold_individually' ) ) {
				$product_stock_sold_individually= $product->get_sold_individually(); // For version 3.0+
				} else {
				$product_stock_sold_individually= $product->sold_individually; // Older than version 3.0
				}
				$product_weight= $product->get_weight();
				$product_length = $product->get_length();
				$product_height = $product->get_height();
				$product_width = $product->get_width();
				$product_detail_link = get_post_permalink( $product_id );
				$product_short_description = preg_replace("/\[(.*?)\]/i",'',apply_filters( 'the_excerpt', get_the_excerpt()));
				$product_long_description = preg_replace("/\[(.*?)\]/i", '', apply_filters( 'the_content', get_the_content()));
				$featured_image_small = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'medium', false, '' );	
				$featured_image_large = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'full', false, '' );
				if(!empty($featured_image_small[0])){
				$allimages[] = array("srcurl"=>$featured_image_small[0],"isthumb"=>"1","islarge"=>"0","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");				}
				if(!empty($featured_image_large[0])){
				$allimages[] = array("srcurl"=>$featured_image_large[0],"isthumb"=>"0","islarge"=>"1","isgallery"=>"0","isvariation"=>"0",'variation_id'=>"0");}
				if ( method_exists( $product, 'get_gallery_image_ids' ) ) {
				$attachment_ids = $product->get_gallery_image_ids(); // For version 3.0+
				} elseif ( method_exists( $product, 'get_gallery_attachment_ids' ) ) {
				$attachment_ids = $product->get_gallery_attachment_ids(); // For version 2.6 to 3.0
				}else {
				$attachment_ids = $product->get_gallery_attachment_ids; // Older than version 2.6
				}
				foreach( $attachment_ids as $attachment_id ) 
				{
				$allimages[] = array("srcurl"=>wp_get_attachment_url( $attachment_id ),"isthumb"=>"0","islarge"=>"0","isgallery"=>"1","isvariation"=>"0",'variation_id'=>"0");
				}
				$regularprice = $product->get_variation_regular_price( 'min', true );
				$product_variations = $product->get_available_variations();
				if(!empty($product_variations)){
				$variation_product_id = $product_variations [0]['variation_id'];
				$variation_product = new WC_Product_Variation( $variation_product_id );
				if($product->is_on_sale()){
					$saleprice= $product->get_variation_sale_price( 'min', true );
				 } 
				$get_available_variations = $product->get_available_variations();
				$productrawvariations='';
				$productrawvariations = array();$productrawvariations['variations']= array(); $custom_single_variation =array();
				foreach($get_available_variations as $get_available_variation){
				$allimages[] = array("srcurl"=>$get_available_variation['image']['url'],"isthumb"=>"0","islarge"=>"0","isgallery"=>"0","isvariation"=>"1",'variation_id'=>$get_available_variation['variation_id']);
				$custom_single_variation['display_price']= $get_available_variation['display_price'];
				$custom_single_variation['display_regular_price']= $get_available_variation['display_regular_price'];
				$custom_single_variation['attributes']= $get_available_variation['attributes'];
				$custom_single_variation['variation_id'] = $get_available_variation['variation_id'];
            	$custom_single_variation['variation_is_active'] = $get_available_variation['variation_is_active'];
            	$custom_single_variation['variation_is_visible'] = $get_available_variation['variation_is_visible'];
				$custom_single_variation['availability_html'] = $get_available_variation['availability_html'];
				$custom_single_variation['backorders_allowed'] = $get_available_variation['backorders_allowed'];
				$custom_single_variation['is_in_stock'] = $get_available_variation['is_in_stock'];
				$custom_single_variation['max_qty'] = $get_available_variation['max_qty'];
            	$custom_single_variation['min_qty'] = $get_available_variation['min_qty'];
				$custom_single_variation['weight'] = $get_available_variation['weight'];
				$custom_single_variation['length'] = $get_available_variation['dimensions']['length'];
				$custom_single_variation['height'] = $get_available_variation['dimensions']['height'];
				$custom_single_variation['width'] = $get_available_variation['dimensions']['width'];
				$custom_single_variation['sku'] = $get_available_variation['sku'];
				$custom_single_variation['stock_quantity'] =  $get_available_variation['max_qty'];
				array_push($productrawvariations['variations'],$custom_single_variation);
				}
				}
				$attributes = $product->get_variation_attributes();
				$productrawvariations['variation_dropdown']= array();
				array_push($productrawvariations['variation_dropdown'],$attributes);
			}
			if(!empty($product_id)){
			$sendstoreproducts[] = array('product_id'=> $product_id,'product_sku'=>htmlspecialchars($product_sku),'product_title'=> htmlspecialchars($product_title),'product_short_description'=> htmlspecialchars($product_short_description),'product_long_description'=> htmlspecialchars($product_long_description),'regularprice'=> $regularprice,'saleprice'=> $saleprice,'product_detail_link'=> $product_detail_link,'allimages'=> $allimages,'productrawvariations'=>$productrawvariations, 'cat_name'=> $cat_name, 'product_stock_status'=>$product_stock_status,'product_stock_manage'=>$product_stock_manage,'product_stock_quantity'=>$product_stock_quantity,'product_stock_backorders'=>$product_stock_backorders,'product_stock_sold_individually'=>$product_stock_sold_individually,'product_weight'=>$product_weight,'product_length'=>$product_length,'product_height'=>$product_height,'product_width'=>$product_width,'producttype'=>$producttype,'groupedproduct_ids'=>$childproductsids);}
		endwhile;
	$jsondata = array("action"=>"Store Feed","products"=>$sendstoreproducts,'store_url'=>$store_url,'cartpage_url'=>$cartpage_url,"store_currency"=>$store_currency,"store_weight_unit"=>esc_attr( get_option('woocommerce_weight_unit' ) ),"store_dimension_unit"=>esc_attr( get_option('woocommerce_dimension_unit')),"store_language"=>$store_language,"apikey"=>"verified");
	}}
  $sendjson = json_encode($jsondata, JSON_NUMERIC_CHECK);
if(!empty($jsondata)){
		$sendjson = json_encode($jsondata, JSON_NUMERIC_CHECK);
		$url_passjson = $wctofb_site_url."/apicalls/wp/bulksave_fanepagestore_products";
		$passstoreproducts = wp_remote_post( $url_passjson, array(
		'headers' => array( 'Content-Type' => 'application/json' ),
		'method' => 'POST',
		'timeout' => 1000,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'sslverify'=> true,
		'body' => $sendjson,
		'cookies' => array())
		);
		if ( is_wp_error( $passstoreproducts ) ) {
		$error = $passstoreproducts->get_error_message();
		$apimessage = 'fail';
		}else{
		$apiresponse = wp_remote_retrieve_body($passstoreproducts);
		$apimessage = json_decode($apiresponse);
		$apimessage = $apimessage->{'action'};
		}
		
	}
	return $apimessage;
}
/****** auto update the api status******/
function wctofb_updatestatus_func( WP_REST_Request $request ) {
global $wctofb_site_url;
$provider_URL= $wctofb_site_url;
	$parameters = $request->get_json_params();
	$wctofb_api = esc_attr(get_option('wctofb_api'));
	$option_name = 'wctofb_apikey_success';
	$option_api_key = 'wctofb_api';
	$apistatus = (int)$request['apistatus'];
	$apikey = $request['apikey'];
	$requesturl = $request['requesturl'];
	if(!empty($parameters['apikey']) && is_int($apistatus) && $provider_URL==$requesturl){
			update_option( $option_name, $apistatus);
			update_option( $option_api_key, $apikey);
			$datasuccess = array("code"=>"valid_param","message"=>"API status update successfully", 'data' => array('status'=>200) );
			return new WP_REST_Response( $datasuccess, 200 );
		}
	else{
	$dataerror = array("code"=>"Invalid_param","message"=>"API status update fail", 'data' => array('status'=>404) );
	 return new WP_REST_Response( $dataerror, 404 );
	 }
}
add_action( 'rest_api_init', 'wctofb_add_api_route_path' );
function wctofb_add_api_route_path() {
  register_rest_route( 'wctofb/v1', '/updatestatus/', array(
    'methods' => 'POST',
    'callback' => 'wctofb_updatestatus_func',
    'args' => array(
	'apikey' => array(
	 'required'=>true,
      ),
      'apistatus' => array(
	 'required'=>true,
	 'sanitize_callback' => 'absint',
        'validate_callback' => function($param, $request, $key) {
          return is_numeric( $param );
        }
      ),
    ),
  ) );
}
/****** auto update product status when product delete on facebook******/
function wctofb_updateproductdeletestatus_func( WP_REST_Request $request ) {
global $wctofb_site_url,$wpdb;
$provider_URL= $wctofb_site_url;
	$parameters = $request->get_json_params();
	$productstatus = (string)$request['productstatus'];
	$productid = (int)$request['productid'];
	$requesturl = $request['requesturl'];
	if(is_string($productstatus) && is_int($productid) && $provider_URL==$requesturl){
		$table_name = $wpdb->prefix . 'wctofb';
		$wpdb->update( $table_name, array( 'product_status' => $productstatus,'sync_status'=>'1'),array( 'product_id' => esc_sql($productid)) );	
			$datasuccess = array("code"=>"valid_param","message"=>"Product status update successfully", 'data' => array('status'=>200) );
			return new WP_REST_Response( $datasuccess, 200 );
		}
	else{
	$dataerror = array("code"=>"Invalid_param","message"=>"Product status update fail", 'data' => array('status'=>404) );
	 return new WP_REST_Response( $dataerror, 404 );
	 }
}
add_action( 'rest_api_init', 'wctofb_updateproductdelete_route_path' );
function wctofb_updateproductdelete_route_path() {
  register_rest_route( 'wctofb/v1', '/updateproductdeletestatus/', array(
    'methods' => 'POST',
    'callback' => 'wctofb_updateproductdeletestatus_func',
    'args' => array(
      'productstatus' => array(
	 'required'=>true,
	    'validate_callback' => function($param, $request, $key) {
          return is_string( $param );
        }
      ),
	  'productid' => array(
	 'required'=>true,
	 'sanitize_callback' => 'absint',
        'validate_callback' => function($param, $request, $key) {
          return is_numeric( $param );
        }
      ),
    ),
  ) );
}
/****** Run a process update plugin data ******/
function wctofb_update_db_check() {
    if ( get_option( 'wctofb_pg_version' ) != wctofb_version) {
		global $wpdb ,$wctofb_site_url;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'wctofb';
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			product_id bigint(250) NOT NULL,
			product_status varchar(250) NOT NULL,
			sync_status int(10) NOT NULL DEFAULT '0',
			activity_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			UNIQUE KEY id (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		update_option('wctofb_pg_version', wctofb_version);
		$wctofb_api = esc_attr(get_option('wctofb_api'));
		if(!empty($wctofb_api)){		
		$jsondata = array("apikey"=>$wctofb_api);
		$sendjson = json_encode($jsondata);
		$url = $wctofb_site_url."/apicalls/wp/plugin_api_verification";
		$apisucces = wp_remote_post( $url, array(
		'headers' => array( 'Content-Type' => 'application/json' ),
		'method' => 'POST',
		'timeout' => 200,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'sslverify'=> true,
		'body' => $sendjson,
		'cookies' => array()) );
		if ( is_wp_error( $apisucces ) ) {
		$error = $apisucces->get_error_message();
		$apimessage = 'Unable to Update API key.';
		}else{
		$apiresponse = wp_remote_retrieve_body($apisucces);
		$apimessage = json_decode($apiresponse);
		$apimessage = (int)$apimessage->{'message'};
		}
		if ($apimessage == (int)$apimessage)
		{
		update_option('wctofb_apikey_success', $apimessage);
		delete_option('wctofb_api_success');
		}
		}
		$checkcolumn = $wpdb->get_results( $wpdb->prepare(
		"SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
		DB_NAME, $table_name, 'product_status') );
		if (empty($checkcolumn)) {
			$wpdb->query("ALTER TABLE $table_name ADD product_status VARCHAR(250) NOT NULL AFTER product_id, ADD sync_status INT(10) NOT NULL DEFAULT '0' AFTER product_status");
			$wpdb->query("UPDATE $table_name SET product_status='sync'");
		}
		wctofbcronstarter_activation();
    }
}
add_action('wp_loaded', 'wctofb_update_db_check');