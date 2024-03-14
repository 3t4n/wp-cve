<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Step 2 (from text above). */
add_action( 'admin_menu', 'wcra_add_admin_menu' );

/** Step 1. */
function wcra_add_admin_menu() {

	$page_title = 'WCRA';
	$menu_title = 'Endpoints';
	$capability = 'manage_options';
	$menu_slug = 'wcra_api_endpoints';
	$function = 'wcra_menu_callback';
	$icon_url  = plugins_url(WCRA_PLUGIN_SLUG.'/admin/img/menu_icon.png');
	$position  = 5;
	add_menu_page(  $page_title,  $menu_title,  $capability,  $menu_slug,  $function  ,$icon_url ,$position );
	add_submenu_page( $menu_slug ,  'New Api Secret', 'New Api Secret',  $capability, 'wcra_new_api', 'wcra_menu_new_api_callback' );
	add_submenu_page( $menu_slug ,  'Secret List', 'Secret List',  $capability, 'wcra_api_list', 'wcra_menu_api_list_callback' );
	add_submenu_page( $menu_slug ,  'Settings', 'Settings',  $capability, 'wcra_api_settings', 'wcra_menu_settings_callback' );
	add_submenu_page( $menu_slug ,  'Log', 'Log',  $capability, 'wcra_api_log', 'wcra_menu_logs_callback' );
	add_submenu_page( $menu_slug ,  'Recent Activity', 'Recent Activity',  $capability, 'wcra_api_recent_activity', 'wcra_menu_activity_callback' );
	add_submenu_page( $menu_slug ,  'Walk Through', 'Walk Through',  $capability, 'wcra_api_walk_help', 'wcra_api_walk_help_callback' );
}

/** Step 3. */
function wcra_menu_callback() {
	
	require_once 'wpr_api_endpoints.php';
}

function wcra_menu_new_api_callback(){
	require_once 'api_new.php';
}
function wcra_menu_api_list_callback(){
	require_once 'api_list.php';
}
function wcra_menu_settings_callback(){
	require_once 'wpr_api_settings.php';
}
function wcra_menu_logs_callback(){
	require_once 'api_log_display.php';
  require_once 'api_log.php';
}
function wcra_menu_activity_callback(){
	require_once 'wpr_api_recent_activity.php';
}

function wcra_api_walk_help_callback(){
	require_once 'wcra_api_walk_help.php';
}





 function wcra_authintication($secret){
 	global $wpdb;
 	$get = array();
 	$secret = esc_attr($secret);
 	$_get_root_secret = wcra_get_root_secret();
 	$_get_settings = wcra_get_settings();
 	$wpr_set_auth_check = esc_attr($_get_settings['wpr_set_auth_check']);
 	if($wpr_set_auth_check == 0 ){
 		return array('act' => 'success' , 'msg' => 'Secret by-passed','secret' => $secret );
 	}

 	if(empty($secret)){
 		return array('act' => 'error' , 'msg' => 'invalid Request, Secret Key Required','secret' => $secret );
     }
 	
 	$tab = $wpdb->prefix.WCRA_DB.'api_base';
 	$checkQ = "SELECT * FROM $tab WHERE ApiSecret = '$secret' ";
 	//return $checkQ;
 	$get = $wpdb->get_row($checkQ);
 	if($get){
 		if($get->Status == 0){
 			return array('act' => 'success' , 'msg' => 'Secret Key matched!','secret' => $secret );
 		}else{
 			return array('act' => 'error' , 'msg' => 'Secret key has been blocked','secret' => $secret );
 		}
 		
 	}else if($secret == $_get_root_secret){
 		return array('act' => 'success' , 'msg' => 'Secret by passed by Root' ,'secret' => $secret);
 	}else{
 		return array('act' => 'error' , 'msg' => 'Invalid Secret Key' ,'secret' => $secret);
 	}
 }


function wcra_get_list_api_access(){
	global $wpdb;
 	$tab = $wpdb->prefix.WCRA_DB.'api_base';
 	$checkQ = "SELECT * FROM $tab ORDER BY id DESC";
 	$get = $wpdb->get_results($checkQ);
 	if(count($get)){
 		return $get;
 	}else{
 		return array();
 	}
}

function wcra_get_api_log(){
	global $wpdb;
 	$tab = $wpdb->prefix.WCRA_DB.'api_log';
 	$checkQ = "SELECT * FROM $tab ORDER BY id DESC";
 	$get = $wpdb->get_results($checkQ);
 	if(count($get)){
 		return $get;
 	}else{
 		return array();
 	}
}

function wcra_get_username($secret){
	global $wpdb;
 	$tab = $wpdb->prefix.WCRA_DB.'api_base';
 	$checkQ = "SELECT * FROM $tab WHERE ApiSecret = '$secret' ";
 	$get = $wpdb->get_row($checkQ);
 	if($get){
 		return $get->Fullname;
 	}else{
 		return false;
 	}
}


function wcra_get_settings($key=''){
	$wpr__settings = get_option('wcra_settings_meta');
	if($key){
		return $wpr__settings[$key];
	}else{
		return $wpr__settings;
	}
}

function wcra_get_end_url($base,$param=array()){
	$str = '';
	$_get_settings = wcra_get_settings();
	$my_namespace = $_get_settings['wpr_set_end_slug'];
	$my_version = $_get_settings['wpr_set_ver'];
	$namespace = $my_namespace . '/v'. $my_version;
	$home_url = home_url().'/wp-json';
	$secret_key = $param['secret_key'] != '' ? $param['secret_key'] : wcra_get_root_secret();
	$build = $home_url.'/'.$namespace.'/'.$base.'/?secret_key='.$secret_key;
	if(count($param)){
		unset($param['secret_key']);
		foreach ($param as $key => $value) {
			if($value != ''){
				$str .= '&'.$value.'=';
			}
			
		}
	}
	if($str){
		$build = $build.$str;
	}else{
		$build = $build;
	}
	return $build;
	

}

function wcra_get_root_secret(){
	$_get_settings = wcra_get_settings();
	$root_secret = $_get_settings['root_secret'];
	return $root_secret;
}

function wcra_secret_list(){
	global $wpdb;
	$return = array();
	$_get_root_secret = wcra_get_root_secret();
	$tabn = $wpdb->prefix.WCRA_DB.'api_base';
	$q = "SELECT * FROM $tabn ORDER BY id DESC";
	$GET = $wpdb->get_results($q);
	if(count($GET)){
		foreach ($GET as $key => $value) {
			$return[$value->ApiSecret] = $value->Fullname;
		}
		$return[$_get_root_secret] = 'root';
	}else{
		
		$return[$_get_root_secret] = 'root';
	}
	return $return;
}

function wcra_help_content($callback_func){
	$html = '<code> add_filter("'.$callback_func.'" , "'.$callback_func.'_handler");</code>
			<code>function '.$callback_func.'_handler($param){</code>
			<code>	//$param = All GET/POST values will be received from endpoint</code>
			<code>//do your stuff here</code>
			<code>$response = $param; </code>
			<code>return $response</code>
			<code>}</code>';
	return $html;
}

function wcra_delete_log($days=7){
	global $wpdb;
	$set_border = date('Y-m-d', strtotime("-$days days"));
	$cus_api_log = $wpdb->prefix.WCRA_DB.'api_log';
	$q = "SELECT count(*) from $cus_api_log where STR_TO_DATE(connectedAt, '%Y-%m-%d %H:%i:%s') <= '$set_border 11:59:59' ";
	$get = $wpdb->get_var($q);
	if($get > 0 ){
		$notification = "<strong>$get</strong> Log has been deleted by system CRON job";
		wcra_save_recent_activity(array('txt' => $notification ));
	}
	
	//return $get;
	$q1 = "DELETE from $cus_api_log where  STR_TO_DATE(connectedAt, '%Y-%m-%d %H:%i:%s') <= '$set_border 11:59:59' ";
	$delete = $wpdb->query($q1);
	return $get;
}

function wcra_get_email_by_id($id){
	global $wpdb;
	$api_base = $wpdb->prefix.WCRA_DB.'api_base';
	$q = "SELECT * FROM $api_base WHERE id=".$id;
	$get = $wpdb->get_row($q);
	return $get->Email;
}

