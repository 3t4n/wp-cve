<?php
/*
Plugin Name: Membee Login
Plugin URI: 
Description: Plugin to add authentication via Membee Member Single Sign-On service.
Version: 2.3.2
Author: achilles_sm
Author URI: https://www.odesk.com/users/~~ea464c4f281cbab8
License: GPL
*/
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include dirname(__FILE__) . '/wordpress-access-control.php';  // abandoned plugin by Brandon Wamboldt
register_activation_hook( __FILE__, 'deactivate_WPAC' );
function deactivate_WPAC(){
  if ( is_plugin_active(  'wordpress-access-control/wordpress-access-control.php' ) and current_user_can( 'activate_plugins' ) ) {
        wp_die('<p><strong>IMPORTANT UPGRADE NOTICE:</strong> The Membee Login plugin (version 2.0.0 or greater) requires that the WordPress Access Control plugin is deactivated and uninstalled.</p><p><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a></p>');
    }
}
$orig_error_display = ini_get('display_errors');                // hiding errors for security
ini_set('display_errors', 0);
$orig_error_reporting = error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);  
$root_path = dirname(__FILE__);
global $membee_error_message, $membee_options; 
$membee_error_message = '';

if (function_exists('json_encode')) {
  require_once($root_path . '/mvc/m.php');                        //including MVC-files
  require_once($root_path . '/mvc/c.php');
  require_once($root_path . '/mvc/v.php');  
  add_action('admin_menu', 'display_membee_login_menu');          //WP admin options menu and page 
  add_action('admin_print_styles', 'admin_header_stylesheet');
  add_filter('allowed_redirect_hosts','allow_redirects');         // allow redirects to membee server
  function membee_init() {
    global $membee_options; 
    $membee_options = process_membee_options();                   //get membee options              
    if ($membee_options['membee_secret']) {
      if (isset($_REQUEST['token'])&&(strlen($_REQUEST['token'])==36)&&(strip_tags($_REQUEST['token'])==$_REQUEST['token'])) {    //if logged in at membee, let's request user details and log in to WP                  
        process_login('https://memberservices.membee.com/feeds/profile/ExchangeTokenForID/', array('APIKEY='.$membee_options['membee_secret'], 'ClientID='.$membee_options['membee_client_id'], 'AppID='.$membee_options['membee_app_id'], 'Token='.$_REQUEST['token']));                         
      }
      if (isset($_REQUEST['redirect_to'])&&isset($_REQUEST['loggedin'])&&($_REQUEST['loggedin'] == true )) {   //makes it compatible with Wordpress Access Control plugin

	$redirect = urldecode($_REQUEST['redirect_to']);
	$find = get_bloginfo('url');
	if (stripos($redirect, $find ,0) === false)
	{  
		wp_safe_redirect(get_bloginfo('url').urldecode($_REQUEST['redirect_to']));		
	}
	else
	{
		wp_safe_redirect(urldecode($_REQUEST['redirect_to']));		
	}
        exit;         
      } 
      wp_register_sidebar_widget('membee_login_widget', 'Membee iFrame Login Widget', 'membee_widget');            
      wp_register_sidebar_widget('membee_login_flyout_widget', 'Membee Login Flyout Widget', 'membee_flyout_widget');
      wp_register_sidebar_widget('membee_reset_widget', 'Membee Reset Widget', 'membee_reset_widget');
      add_shortcode( 'membee_login', 'membee_login_shortcode' );
      add_shortcode( 'membee_reset', 'membee_reset_shortcode' );
    }
  }
  $ua = $_SERVER['HTTP_USER_AGENT'];
  if ((!preg_match('/facebookexternalhit/si',$ua))&&(!preg_match('/googlebot/si',$ua))&&(!preg_match('/gsa-crawler/si',$ua))&&(!preg_match('/LinkedInBot/si',$ua))&&(!preg_match('/feedburner/si',$ua))&&(!preg_match('/google/si',$ua))&&(!preg_match('/slurp/si',$ua))&&(!preg_match('/ask/si',$ua))&&(!preg_match('/teoma/si',$ua))&&(!preg_match('/yandex/si',$ua))&&(!preg_match('/mj12bot/si',$ua))&&(!preg_match('/validator/si',$ua))&&(!preg_match('/DuckDuckBot/si',$ua))&&(!preg_match('/facebot/si',$ua))&&(!preg_match('/ia_archiver/si',$ua))&&(!preg_match('/bingbot/si',$ua))&&(!preg_match('/msnbot/',$ua))&&(!preg_match('/bingbot/',$ua))&&(!preg_match('/msnbot/si',$ua))&&(!preg_match('/mailchimp/si',$ua))&&(!preg_match('/ning/si',$ua))) {
    add_action('init', 'membee_init'); 
  }
  add_filter('logout_url', 'change_logout_url');                  //change logout url in admin bar
  add_action('wp_enqueue_scripts', 'prepare_flyout');             //enqueue required scripts    
} else {
  $membee_error_message = 'JSON support is required for that plugin!';
} 
if ($membee_error_message) {
  error_log ( $membee_error_message );    //sending error messages to your server error log. 
}
ini_set('display_errors', $orig_error_display);                 // error reporting back to original
error_reporting($orig_error_reporting); 
?>