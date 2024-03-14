<?php
session_start();
/**
* Plugin Name: History Log by click5
* Plugin URI: https://www.click5interactive.com/wordpress-history-log-plugin/
* Description: Track user activity and log changes on your website.
* Version: 1.0.13
* Author: click5 Interactive
* Author URI: https://www.click5interactive.com/?utm_source=history-plugin&utm_medium=plugin-list&utm_campaign=wp-plugins
* Text Domain: history-log-by-click5
* Domain Path: /languages
* Tags: history, log, changes, activity, click5
**/
if ( ! function_exists( 'get_plugins' ) )
{
  require_once( ABSPATH . 'wp-includes/pluggable.php' );
}
require_once( ABSPATH . 'wp-includes/user.php');

if (!function_exists('is_plugin_active')) {
  include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

use InstagramFeed\Builder\SBI_Db;
use Automattic\Jetpack\Modules as jetpackModules;


include("click5_grid.php");
include("click5_grid_plugins.php");
include("click5_grid_modules.php");
include("api.php");
define('click5_history_log_VERSION', '1.0.13');
define('click5_history_log_DEV_MODE', true);
define( 'CLICK5_HISTORY_LOG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
global $click5_aioseo_options_old;
global $click5_registered_widget;



function click5_history_log_activate_plugin_name() {
  $role = get_role( 'editor' );
  $role->add_cap( 'manage_options' ); // capability
}
// Register our activation hook
register_activation_hook( __FILE__, 'click5_history_log_activate_plugin_name' );

function click5_history_log_deactivate_plugin_name() {

 $role = get_role( 'editor' );
 $role->remove_cap( 'manage_options' ); // capability
}

function click5_listFolderFiles($dir){
  $fileFolderList = scandir($dir);
  echo '<ul>';
  foreach($fileFolderList as $fileFolder){
      if($fileFolder != '.' && $fileFolder != '..'){
          if(!is_dir($dir.'/'.$fileFolder)){
              echo '<li><a target="_blank" href="'.site_url().'/'.ltrim($dir.'/'.$fileFolder,'./').'">'.$fileFolder.'</a>';
          } else {
              echo '<li>'.$fileFolder;
          }
          if(is_dir($dir.'/'.$fileFolder)) click5_listFolderFiles($dir.'/'.$fileFolder);
              echo '</li>';
          }
  }
  echo '</ul>';
}

register_deactivation_hook( __FILE__, 'click5_history_log_deactivate_plugin_name' );

add_filter( 'wp_redirect',function($location,$status){
	ob_start();

  if(array_key_exists('cookie-filter-by-module',$_SESSION) && array_key_exists('cookie-filter-by-user',$_SESSION) && array_key_exists('cookie-filter-by-month',$_SESSION))
  {
    if((!is_null($_SESSION['cookie-filter-by-module']) && isset($_SESSION['cookie-filter-by-module']) != "all") || (!is_null($_SESSION['cookie-filter-by-user']) && isset($_SESSION['cookie-filter-by-user']) != "all") || (!is_null($_SESSION['cookie-filter-by-month']) && isset($_SESSION['cookie-filter-by-month']) != "0"))
      return $location;
  }
	$params = parse_url($location,PHP_URL_QUERY);
	parse_str($params,$params);
	if(isset($params['page']) && isset($params['paged'])){
		if($params['page'] == "history-log-by-click5/history-log-by-click5.php"){
		  if(isset($_POST['paged']) && !is_null($_POST['paged']) && $_POST['paged'] != "")
			$pageparam = $_POST['paged'];
		  else if(isset($_GET['paged']) && !is_null($_GET['paged']) && $_GET['paged'] != "")
			$pageparam = $_GET['paged'];

      if(isset($_REQUEST['max_page'])){
        if($params['paged'] > $_REQUEST['max_page'])
          $params['paged'] = $_REQUEST['max_page'];
      }
		  $params = "paged=".$params['paged'];
		  $location = str_replace($params,"paged=".$pageparam,$location);
		  //$params = "?".http_build_query($params);
		  //$admin_url = substr(admin_url(),0,-1);
		  //$url = $admin_url.$params;
		  return $location;
		}
	}
	
	return $location;
  },10,2);

function click5_check_nonce_log($nonce){
  return wp_verify_nonce($nonce, 'click5_history_log_nonce');
}

function click5_get_current_user_role($id){
  global $wp_roles;
 return $wp_roles->roles[array_values(get_user_by('ID',$id)->roles)[0]]['name'];
}

add_action( 'init', 'click5_global_user_var' );
function click5_global_user_var()
{ 
  global $wp_registered_widgets;
  global $click5_registered_widget;
  $click5_registered_widget = $wp_registered_widgets;

  global $REQUEST_FROM_INIT;
  $REQUEST_FROM_INIT = $_REQUEST;

  global $min_version_support_plugin_list;
  global $sitemapCurrentUser;
  $sitemapCurrentUser = wp_get_current_user();

  $min_version_support_plugin_list = array(
    "wordpress-seo/wp-seo.php" => "14.9",
    "wordpress-seo-premium/wp-seo-premium.php" => "14.9"
  );

  if(isset($_REQUEST['install-sitemap-by-click5']) && $_REQUEST['install-sitemap-by-click5'] == 'true'){
    $c5SiteMap_Pathpluginurl = WP_PLUGIN_DIR . '/sitemap-by-click5/sitemap-by-click5.php';
    // Activate your plugin
    activate_plugin($c5SiteMap_Pathpluginurl);
    exit(wp_redirect( admin_url( '/admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php&tab=settings' ) ));
  }


  /*global $gCurrentUser;
  if(is_null($gCurrentUser))
    $gCurrentUser = wp_get_current_user()->data->user_login;*/

  if(esc_attr(get_option('click5_history_log_better-search-replace-pro/better-search-replace.php')) == "1"){
    if(isset($_REQUEST['page']))
    {
      if($_REQUEST['page'] == "better-search-replace"){
        global $gCurrentUser;
        global $sitemapCurrentUser;
        $usr = $gCurrentUser;
        if(is_null($gCurrentUser) && !is_null($sitemapCurrentUser->data->user_login))
          $usr = $sitemapCurrentUser->data->user_login;
        else if(is_null($gCurrentUser) && !is_null(wp_get_current_user()->data->user_login))
          $usr = wp_get_current_user()->data->user_login;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if(isset($_REQUEST['import']) && isset($_REQUEST['result'])){
          if($_REQUEST['import'] == "true" && $_REQUEST['result'] == "true"){
            $wpdb->insert($table_name, array('description'=>"<b>Database import</b> has ended",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Better Search Replace Pro", 'user'=>$usr));
  
          }
        }
      } 
    }
  }

  $theme_list = wp_get_themes();
  foreach($theme_list as $theme_item) {
    if (!get_option("version_theme_" . $theme_item->get('TextDomain'))) {
      add_option("version_theme_" . $theme_item->get('TextDomain'), $theme_item->get('Version'));
    }
  }
  if(function_exists('is_plugin_active')) {
    if( is_plugin_active("all-in-one-seo-pack/all_in_one_seo_pack.php") || is_plugin_active("all-in-one-seo-pack-pro/all_in_one_seo_pack.php") ) {
      global $click5_aioseo_options_old;
      if(isset(aioseo()->core->optionsCache))
      {
        $click5_aioseo_options_old = aioseo()->core->optionsCache->getOptions( "aioseo_options" );
      }
    }
}

  if(isset($_POST['save_button'])){ 
    $nonce = sanitize_text_field($_POST['click5_history_log_nonce']); 
    if(click5_check_nonce_log($nonce))
    {
      $confirm = "";
      if(isset($_POST["confirmDelete"]))
        $confirm = sanitize_text_field($_POST["confirmDelete"]);
      if($confirm == 'delete' || $confirm == 'DELETE')
      {
        global $wpdb;
        global $gCurrentUser;
        $gCurrentUser = wp_get_current_user()->user_login;
        $table_name = $wpdb->prefix . "c5_history";
        $sql_clean_log = "DELETE FROM $table_name";
        $wpdb->query($sql_clean_log);
        if(esc_attr(get_option('click5_history_log_history-log-by-click5/history-log-by-click5.php')) == "1") {
          $wpdb->insert($table_name, array('description'=>"<b>History Log</b> data has been purged",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
        }
        $url = home_url().'/wp-admin/admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php';
        header("Location:".$url);
        
        exit();
      }
      else if($confirm == '')
      {
        global $wpdb;
        global $gCurrentUser;
        $gCurrentUser = wp_get_current_user()->user_login;
        $table_name = $wpdb->prefix . "c5_history";
        $input = "";
        if(isset($_POST['click5_history_log_store_time']))
          $input = sanitize_text_field($_POST['click5_history_log_store_time']);

        if(!empty($input))
          update_option("click5_log_store_time", $input);


        $date = new DateTime('now');
        if($input == 'indefinitely' && esc_attr(get_option('click5_history_log_history-log-by-click5/history-log-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>"<b>History Log</b> data storage duration has been changed to <b>indefinitely</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
        }
        if($input == 'day' && esc_attr(get_option('click5_history_log_history-log-by-click5/history-log-by-click5.php')) == "1") {
          $date->modify('+1 day');
          $date = $date->format('Y-m-d h:i A');
          update_option( 'click5_history_log_clear_date', $date);
          $wpdb->insert($table_name, array('description'=>"<b>History Log</b> data storage duration has been changed to <b>1 day</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
        }  
        if($input == 'week' && esc_attr(get_option('click5_history_log_history-log-by-click5/history-log-by-click5.php')) == "1") {
          $date->modify('+7 days');
          $date = $date->format('Y-m-d h:i A');
          update_option( 'click5_history_log_clear_date', $date);
          $wpdb->insert($table_name, array('description'=>"<b>History Log</b> data storage duration has been changed to <b>1 week</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
        } 
        if($input == 'month' && esc_attr(get_option('click5_history_log_history-log-by-click5/history-log-by-click5.php')) == "1") {
          $date->modify('+1 month');
          $date = $date->format('Y-m-d h:i A');
          update_option( 'click5_history_log_clear_date', $date);
          $wpdb->insert($table_name, array('description'=>"<b>History Log</b> data storage duration has been changed to <b>1 month</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
        } 
        if($input == '3_month' && esc_attr(get_option('click5_history_log_history-log-by-click5/history-log-by-click5.php')) == "1") {
          $date->modify('+3 month');
          $date = $date->format('Y-m-d h:i A');
          update_option( 'click5_history_log_clear_date', $date);
          $wpdb->insert($table_name, array('description'=>"<b>History Log</b> data storage duration has been changed to <b>3 months</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
        } 
        if($input == '6_month' && esc_attr(get_option('click5_history_log_history-log-by-click5/history-log-by-click5.php')) == "1") {
          $date->modify('+6 month');
          $date = $date->format('Y-m-d h:i A');
          update_option( 'click5_history_log_clear_date', $date);
          $wpdb->insert($table_name, array('description'=>"<b>History Log</b> data storage duration has been changed to <b>6 months</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
        } 
        if($input == '12_month' && esc_attr(get_option('click5_history_log_history-log-by-click5/history-log-by-click5.php')) == "1") {
          $date->modify('+12 month');
          $date = $date->format('Y-m-d h:i A');
          update_option( 'click5_history_log_clear_date', $date);
          $wpdb->insert($table_name, array('description'=>"<b>History Log</b> data storage duration has been changed to <b>12 months</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
        } 
      }  
    }
  }else if(isset($_REQUEST['save_alerts'])){
    $nonce = sanitize_text_field($_POST['click5_history_log_nonce']); 
    if(click5_check_nonce_log($nonce)){
      $validMails = array();
      $_SESSION['click5_history_log_emails_invalid'] = array();
      $alert_email_data = get_option("click5_history_log_alert_email");
  
      $alert_input = array(
        isset($_POST['click5_history_log_critical_error']) == true ? sanitize_text_field($_POST['click5_history_log_critical_error']) : "",
        isset($_POST['click5_history_log_technical_issue']) == true ? sanitize_text_field($_POST['click5_history_log_technical_issue']) : "",
        //isset($_POST['click5_history_log_alert_email']) == true ? array_filter($_POST['click5_history_log_alert_email']) : array(),
        isset($_POST['click5_history_log_alert_email']) == true ? $_POST['click5_history_log_alert_email'] : "",
        isset($_POST['click5_history_log_email_template']) == true ? $_POST['click5_history_log_email_template'] : "",
        isset($_POST['click5_history_log_404']) == true ? $_POST['click5_history_log_404'] : "",
      );
  
      if($alert_input[0] != esc_attr(get_option("click5_history_log_critical_error")))
        update_option("click5_history_log_critical_error",$alert_input[0]);
  
      if($alert_input[1] != esc_attr(get_option("click5_history_log_technical_issue")))
        update_option("click5_history_log_technical_issue",$alert_input[1]);

      if($alert_input[3] != esc_attr(get_option("click5_history_log_email_template")) && !empty($alert_input[3]))
        update_option("click5_history_log_email_template",$alert_input[3]);

      if($alert_input[4] != esc_attr(get_option("click5_history_log_404")))
        update_option("click5_history_log_404",$alert_input[4]);
  
        /*if(!empty($alert_email_data))
          $alert_email_data = (array)json_decode($alert_email_data);
        if(is_array($alert_email_data)){
          if(count((array_diff($alert_input[2],$alert_email_data))) > 0){
            update_option("click5_history_log_alert_email",json_encode($alert_input[2]));
          }else if(count($alert_input[2]) != $alert_email_data){
            update_option("click5_history_log_alert_email",json_encode($alert_input[2]));
          }
          else{
            update_option("click5_history_log_alert_email",json_encode($alert_input[2]));
          }
        }*/
        if(substr($alert_input[2],-1) == "," || substr($alert_input[2],-1) == ";"){
          $alert_input[2] = substr($alert_input[2],0,-1);
        }
  
        $alert_input[2] = str_replace(' ','',$alert_input[2]);
  
        if(!empty($alert_input[2])){
          $mailList = preg_split('/(,|;)/', $alert_input[2]);
          foreach($mailList as $mail){
            if(!empty($mail)){
              if(is_email($mail)){
                $validMails[] = $mail;
              }else{
                $_SESSION['click5_history_log_emails_invalid'][] = $mail;
              }
            }
          }
        }
  
        if(!empty($validMails) && empty($_SESSION['click5_history_log_emails_invalid']))
        {
          $alert_input[2] = implode(",",$validMails);
        }else{
          if(!empty($alert_input[2])){
            $alert_input[2] = $alert_email_data;
          }else{
            $alert_input[2] = "";
          }
        }
  
  
        if(!empty($alert_email_data)){
          if($alert_email_data != $alert_input[2]){
            update_option("click5_history_log_alert_email",$alert_input[2]);
          }
        }else{
          $userData = wp_get_current_user();
          $userEmail = "";
          if(isset($userData->ID)){
              $userEmail = $userData->data->user_email;
          }
          if(empty($_SESSION['click5_history_log_emails_invalid']) && empty($alert_input[2]))
            update_option("click5_history_log_alert_email",$userEmail);
          else if(!empty($alert_input[2]))
            update_option("click5_history_log_alert_email",$alert_input[2]);
        }
    }
  }

  if(isset($_POST['save_button_op']))
  { 
    $nonce = sanitize_text_field($_POST['click5_history_log_nonce']); 
    if(click5_check_nonce_log($nonce))
    {
      $confirm = sanitize_text_field($_POST["confirmDelete"]);
      if($confirm == 'delete' || $confirm == 'DELETE')
      {
        global $wpdb;
        global $gCurrentUser;
        $gCurrentUser = wp_get_current_user()->user_login;
        $table_name = $wpdb->prefix . "c5_history";
        $sql_clean_log = "DELETE FROM $table_name";
        $wpdb->query($sql_clean_log);
        if(esc_attr(get_option('click5_history_log_history-log-by-click5/history-log-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>"<b>History Log</b> data has been purged",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
        }
        $url = home_url().'/wp-admin/admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php';
        header("Location:".$url);
        
        exit();
      }
    }
  }

  if ( ! function_exists( 'get_plugins' ) ) {      
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
  }  

  global $wpdb;
  global $gCurrentUser;
  global $previous_plugins_list;
  $previous_plugins_list = get_plugins();
  $gCurrentUser = wp_get_current_user()->user_login;
  
  global $gBlockDouble;
  $gBlockDouble = false;
  global $gRepData;
  $gRepData = array(0, false);
  global $gRepEditDeleteData;
  $gRepEditDeleteData = array(0, "");
  global $gRepEditAddData;
  $gRepEditAddData = array(0, "");
  $support_plugin_list = array(
    "advanced-custom-fields/acf.php", 
    "acf-repeater/acf-repeater.php",
    "acf-extended/acf-extended.php", 
    "all-in-one-seo-pack/all_in_one_seo_pack.php",
    "all-in-one-seo-pack-pro/all_in_one_seo_pack.php",
    "classic-editor/classic-editor.php",
    "contact-form-7/wp-contact-form-7.php",
    "disable-comments-by-click5/disable-comments-by-click5.php",
    "history-log-by-click5/history-log-by-click5.php",
    "google-site-kit/google-site-kit.php",
    "seo-by-rank-math/rank-math.php",
    "seo-by-rank-math-pro/rank-math-pro.php",
    "sitemap-by-click5/sitemap-by-click5.php",
    "wordfence/wordfence.php",
    "wordpress-seo/wp-seo.php",
    "wordpress-seo-premium/wp-seo-premium.php",
    "cf7-add-on-by-click5/cf7-addon-by-click5.php",
    "wpf-add-on-by-click5/wpf-addon-by-click5.php",
    "gf-add-on-by-click5/gf-addon-by-click5.php",
    "click5-crm-add-on-to-ninja-forms/ninja-addon-by-click5.php",
    "wpforms-lite/wpforms.php",
    "wpforms/wpforms.php",
    "ninja-forms/ninja-forms.php",
    "advanced-custom-fields-pro/acf.php",
    "better-search-replace/better-search-replace.php",
    "better-search-replace-pro/better-search-replace.php",
    "redirection/redirection.php",
    "health-check/health-check.php",
    "classic-widgets/classic-widgets.php",
    "instagram-feed/instagram-feed.php",
    "wp-google-maps/wpGoogleMaps.php",
    "wp-google-maps-pro/wp-google-maps-pro.php",
    "jetpack/jetpack.php",
    "duplicate-post/duplicate-post.php",
    "all-in-one-wp-migration/all-in-one-wp-migration.php",
    "updraftplus/updraftplus.php",
    "duplicator/duplicator.php",
    "loco-translate/loco.php",
    "polylang/polylang.php",
    "limit-login-attempts-reloaded/limit-login-attempts-reloaded.php",
    "sg-cachepress/sg-cachepress.php",
    "user-role-editor/user-role-editor.php",
    "backwpup/backwpup.php",
    "string-locator/string-locator.php",
    "wp-mail-log/wp-mail-log.php"

  );
  foreach($support_plugin_list as $support_plugin_list_item) {
    $plug_exist = $wpdb->query( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "options WHERE option_name ='%s' LIMIT 1", 'click5_history_log_' . $support_plugin_list_item ));
    if($plug_exist){
      if(is_plugin_active($support_plugin_list_item))
        $plugin_version = get_plugin_data(WP_PLUGIN_DIR . '/' . $support_plugin_list_item)['Version'];
      else
        $plugin_version = null;
      if(!is_null($plugin_version) && isset($min_version_support_plugin_list[$support_plugin_list_item]))
      {
        if(version_compare($plugin_version,$min_version_support_plugin_list[$support_plugin_list_item]) < 1){
          if(isset($min_version_support_plugin_list[$support_plugin_list_item]))
            update_option("click5_history_log_".$support_plugin_list_item, "0");
        }
      }
    }
    else {
      add_option('click5_history_log_' . $support_plugin_list_item, "1");
    }
  }

  $support_module_list = array(
    "404_error" => array("name" => "404 Errors", "default" => "0"),
    "media" => array("name" => "Media", "default" => "1"),
    "menu" => array("name" => "Menu", "default" => "1"),
    "pages" => array("name" => "Pages", "default" => "1"),
    "plugins" => array("name" => "Plugins", "default" => "1"),
    "posts" => array("name" => "Posts", "default" => "1"),
    "settings" => array("name" => "Settings", "default" => "1"),
    "site_health" => array("name" => "Site Health", "default" => "1"),
    "themes" => array("name" => "Themes", "default" => "1"),
    "users" => array("name" => "Users", "default" => "1"),
    "widgets" => array("name" => "Widgets", "default" => "1"),
    "wordpress_core" => array("name" => "WordPress Core", "default" => "1"),
    "wp_engine" => array("name" => "WP Engine", "default" => "1")
  );

  foreach($support_module_list as $optionName => $support_plugin_item) {
    if(get_option('click5_history_log_module_' . $optionName) === false){
      add_option('click5_history_log_module_' . $optionName, $support_plugin_item['default']);
    }
  }

  if(!get_option("click5_history_log_critical_error"))
    add_option("click5_history_log_critical_error",true);
    
  if(!get_option("click5_history_log_technical_issue"))
    add_option("click5_history_log_technical_issue",true);

  if(!get_option("click5_history_log_alert_email"))
    add_option("click5_history_log_alert_email");

  if(!get_option("click5_history_log_email_template"))
    add_option("click5_history_log_email_template","plain");

  if(!get_option("click5_history_log_404"))
    add_option("click5_history_log_404","0");

    

  if(esc_attr(get_option("click5_history_log_health-check/health-check.php")) == "1"){
    if(isset($_REQUEST['health-check-troubleshoot-mode'])){
      global $wpdb;
      global $gCurrentUser;
      if(is_null($gCurrentUser))
        $gCurrentUser = wp_get_current_user()->data->user_login;
      $table_name = $wpdb->prefix . "c5_history";
      if($_REQUEST['health-check-troubleshoot-mode'] == "true" )
        $wpdb->insert($table_name, array('description'=>"<b>Troubleshooting</b> mode has been turned on",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Health Check & Troubleshooting', 'user'=>$gCurrentUser));
      else if($_REQUEST['health-check-troubleshoot-mode'] == "false" )
        $wpdb->insert($table_name, array('description'=>"<b>Troubleshooting</b> mode has been turned off",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Health Check & Troubleshooting', 'user'=>$gCurrentUser));
    }
  
    if(isset($_REQUEST['action'])){
      global $wpdb;
      global $gCurrentUser;
      if(is_null($gCurrentUser))
        $gCurrentUser = wp_get_current_user()->data->user_login;
      $table_name = $wpdb->prefix . "c5_history";
        if($_REQUEST['action'] == "health-check-files-integrity-check"){
          $wpdb->insert($table_name, array('description'=>"<b>Checking the integrity of files</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Health Check & Troubleshooting', 'user'=>$gCurrentUser));
        }else if($_REQUEST['action'] == "health-check-mail-check"){
          $send = false;
          $message = $_POST['email_message'];
          if(empty($message))
            $message = "Healt Check Mail message";
          $send = wp_mail($_POST['email'],"Healt Check Mail subject",$message);
          if($send)
            $wpdb->insert($table_name, array('description'=>"<b>Checking Mail</b> has been completed successfully",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Health Check & Troubleshooting', 'user'=>$gCurrentUser));
          else
            $wpdb->insert($table_name, array('description'=>"<b>Checking Mail</b> has been failed",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Health Check & Troubleshooting', 'user'=>$gCurrentUser));
  
        }else if($_REQUEST['action'] == "health-check-tools-plugin-compat"){
            if($_REQUEST['slug'] == "history-log-by-click5/history-log-by-click5.php")
              $wpdb->insert($table_name, array('description'=>"<b>Checking plugin compatibility</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Health Check & Troubleshooting', 'user'=>$gCurrentUser));
        }
    }
  }

  if(esc_attr(get_option("click5_history_log_loco-translate/loco.php")) == "1"){
    global $wpdb;
    global $gCurrentUser;
    if(is_null($gCurrentUser))
      $gCurrentUser = wp_get_current_user()->data->user_login;
    $table_name = $wpdb->prefix . "c5_history";
    $language = "";
    $langArray = NULL;
    if(file_exists(ABSPATH.'wp-content/plugins/loco-translate/lib/data/languages.php'))
      $langArray = require(ABSPATH.'wp-content/plugins/loco-translate/lib/data/languages.php');
    if(file_exists(ABSPATH.'wp-content/plugins/loco-translate/lib/data/locales.php'))
      $locArray = require(ABSPATH.'wp-content/plugins/loco-translate/lib/data/locales.php');

      if(isset($_REQUEST['action']) && $_REQUEST['action'] == "loco_json"){

        if(isset($_REQUEST['select-locale'])){
          $langSlug = $_REQUEST['select-locale'];
          if(strpos($langSlug,"_") !== false){
            $langSlug = substr($langSlug,0,strpos($langSlug,"_"));
          }else if(strpos($langSlug,"-") !== false){
            $langSlug = substr($langSlug,0,strpos($langSlug,"-"));
          }
            
          if(!empty($langArray[$langSlug])){
            $language = $langArray[$langSlug];
          }else if(!empty($locArray[$langSlug])){
            $language = $locArray[$langSlug][0];
          }
          else{
            $language = strtoupper($_REQUEST['select-locale']);
            
          }
        }else  if(isset($_REQUEST['locale'])){
          $langSlug = $_REQUEST['locale'];
          if(strpos($langSlug,"_") !== false){
            $langSlug = substr($langSlug,0,strpos($langSlug,"_"));
          }else if(strpos($langSlug,"-") !== false){
            $langSlug = substr($langSlug,0,strpos($langSlug,"-"));
          }
  
          if(!empty($langArray[$langSlug])){
            $language = $langArray[$langSlug];
          }else if(!empty($locArray[$langSlug])){
            $language = $locArray[$langSlug][0];
          }else{
            $language = strtoupper($_REQUEST['locale']);
          }
        }

        if(isset($_REQUEST['route']) && $_REQUEST['route'] == "msginit"){
          //Loco NEW/COPY translations
          if(isset($_REQUEST['type'])){
            if($_REQUEST['type'] == "Plugin"){
              if( ! function_exists('get_plugin_data') ){
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
              }

              if(isset($_REQUEST['loco-nonce']) && !empty($_REQUEST['loco-nonce'])){
                $projectName = "";
                if(isset($_REQUEST['bundle']) && !empty($_REQUEST['bundle']))
                  $plugin_data = get_plugin_data(WP_CONTENT_DIR . "/plugins/" . $_REQUEST['bundle']);
                
                if(isset($plugin_data) && !empty($plugin_data['Name']))
                  $projectName = $plugin_data['Name'];
                else{
                  if(isset($_REQUEST['domain']) && !empty($_REQUEST['domain']))
                    $projectName = $_REQUEST['domain'];
                }
                  
                if(!isset($_REQUEST['source']) || empty($_REQUEST['source']))
                  $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been added to <b>".$projectName."</b> plugin",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
                else if(isset($_REQUEST['source']) && !empty($_REQUEST['source']))
                  $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been copied in <b>".$projectName."</b> plugin",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
              }
            }else if($_REQUEST['type'] != "Plugin"){
              if(isset($_REQUEST['loco-nonce']) && !empty($_REQUEST['loco-nonce'])){
                $projectName = "";
                if(isset($_REQUEST['bundle']) && !empty($_REQUEST['bundle'])){
                  if(class_exists("Loco_package_Bundle") && $_REQUEST['type'] == "Core"){ // tutaj poprawić
                    $projectData = Loco_package_Bundle::fromId('core')->getProjectById($_REQUEST['domain']);

                    if($projectData->getName() != NULL || $projectData->getName() != ""){
                      $projectName = $projectData->getName();
                    }else{
                      $projectName = $_REQUEST['domain'];
                    }
                  }else if($_REQUEST['type'] == "Theme"){
                    if(!empty(wp_get_themes()[$_REQUEST['bundle']])){
                      $projectData = wp_get_themes()[$_REQUEST['bundle']];
                      if(!empty($projectData->display("Name"))){
                        $projectName = $projectData->display("Name");
                      }else{
                        $projectName = $_REQUEST['bundle'];
                      }
                    }
                  }else{
                    $projectName = $_REQUEST['domain'];
                  }
                }else{
                  $projectName = $_REQUEST['type'];
                }
                
                  
                if(!isset($_REQUEST['source']) || empty($_REQUEST['source'])){
                  if($_REQUEST['type'] == "Theme")
                    $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been added to <b>".$projectName."</b> theme",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
                  else
                  $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been added to WordPress <b>".$projectName."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
                }
                else if(isset($_REQUEST['source']) && !empty($_REQUEST['source'])){
                  if($_REQUEST['type'] == "Theme")
                    $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been copied in <b>".$projectName."</b> theme",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
                  else
                  $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been copied in WordPress <b>".$projectName."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
                }     
              }
            }
          }
        }else if(isset($_REQUEST['route']) && $_REQUEST['route'] == "save"){
          //Loco EDIT translations
          if(isset($_REQUEST['bundle'])){
            if(strpos($_REQUEST['bundle'],"plugin.") !== false){
              if( ! function_exists('get_plugin_data') ){
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
              }

              if(isset($_REQUEST['loco-nonce']) && !empty($_REQUEST['loco-nonce'])){
                $projectName = "";
                if(!empty($_REQUEST['bundle']))
                  $plugin_data = get_plugin_data(WP_CONTENT_DIR . "/plugins/" . str_replace("plugin.","",$_REQUEST['bundle']));
                
                if(isset($plugin_data) && !empty($plugin_data['Name']))
                  $projectName = $plugin_data['Name'];
                else{
                  if(isset($_REQUEST['domain']) && !empty($_REQUEST['domain']))
                    $projectName = $_REQUEST['domain'];
                }    
                $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been edited in <b>".$projectName."</b> plugin",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
              }
            }else if(strpos($_REQUEST['bundle'],"plugin.") === false){
              if(isset($_REQUEST['loco-nonce']) && !empty($_REQUEST['loco-nonce'])){
                $projectName = "";
                
                if(strpos($_REQUEST['bundle'],"theme.") !== false){
                  $projectData = str_replace("theme.","",$_REQUEST['bundle']);
                  if(!empty(wp_get_themes()[$projectData])){
                    $projectData = wp_get_themes()[$projectData];
                    if(!empty($projectData->display("Name"))){
                      $projectName = $projectData->display("Name");
                    }else{
                      $projectName = $_REQUEST['bundle'];
                    }
                  }
                }else{
                  if(class_exists("Loco_package_Bundle")){
                    $projectData = Loco_package_Bundle::fromId('core')->getProjectById($_REQUEST['domain']);

                    if($projectData->getName() != NULL || $projectData->getName() != ""){
                      $projectName = $projectData->getName();
                    }else{
                      $projectName = $_REQUEST['domain'];
                    }
                  }else{
                    $projectName = $_REQUEST['domain'];
                  }
                } 

                if(strpos($_REQUEST['bundle'],"theme.") !== false)
                  $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been edited in <b>".$projectName."</b> theme",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
                else 
                  $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been edited in WordPress <b>".$projectName."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
              }
            }
          }
        }
      }else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "file-delete"){

        $langSlug = "";
        $language = "";
        if(isset($_REQUEST['path']) && !empty($_REQUEST['path'])){
          $langSlug = $_REQUEST['path'];
          $lastOccurence = strripos($langSlug,"/");
          if($lastOccurence !== false){
            $langSlug = substr($langSlug,$lastOccurence+1);
          }

          if(isset($_REQUEST['domain']))
            $langSlug = str_replace($_REQUEST['domain']."-","",$langSlug);

          $langSlug = str_replace(".po","",$langSlug);

          if(strpos($langSlug,"_") !== false){
            $langSlug = substr($langSlug,0,strpos($langSlug,"_"));
          }else if(strpos($langSlug,"-") !== false){
            $langSlug = substr($langSlug,0,strpos($langSlug,"-"));
          }
            
          if(!empty($langArray[$langSlug])){
            $language = $langArray[$langSlug];
          }else if(!empty($locArray[$langSlug])){
            $language = $locArray[$langSlug][0];
          }else{
            $language = strtoupper($langSlug);
          }

        }

        //Loco DELETE translations
        if(isset($_REQUEST['page'])){
          if($_REQUEST['page'] == "loco-plugin"){
            if( ! function_exists('get_plugin_data') ){
              require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            
            if(isset($_REQUEST['loco-nonce']) && !empty($_REQUEST['loco-nonce'])){
              $projectName = "";
              if(!empty($_REQUEST['bundle']))
                $plugin_data = get_plugin_data(WP_CONTENT_DIR . "/plugins/" . str_replace("plugin.","",$_REQUEST['bundle']));
              
              if(isset($plugin_data) && !empty($plugin_data['Name']))
                $projectName = $plugin_data['Name'];
              else{
                if(isset($_REQUEST['domain']) && !empty($_REQUEST['domain']))
                  $projectName = $_REQUEST['domain'];
              }    
              $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been deleted from <b>".$projectName."</b> plugin",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
            }
          }else if($_REQUEST['page'] != "loco-plugin"){
            if(isset($_REQUEST['loco-nonce']) && !empty($_REQUEST['loco-nonce'])){
              $projectName = "";

              if($_REQUEST['page'] == "loco-theme"){
                if(isset($_REQUEST['domain']) && !empty($_REQUEST['domain'])){
                  if(!empty(wp_get_themes()[$_REQUEST['domain']])){
                    $projectData = wp_get_themes()[$_REQUEST['domain']];
                    if(!empty($projectData->display("Name"))){
                      $projectName = $projectData->display("Name");
                    }else{
                      $projectName = $_REQUEST['bundle'];
                    }
                  }
                }
              }else{
                if(class_exists("Loco_package_Bundle")){
                  $projectData = Loco_package_Bundle::fromId('core')->getProjectById($_REQUEST['domain']);

                  if($projectData->getName() != NULL || $projectData->getName() != ""){
                    $projectName = $projectData->getName();
                  }else{
                    $projectName = $_REQUEST['domain'];
                  }
                }else{
                  $projectName = $_REQUEST['domain'];
                }
              }
              
              if($_REQUEST['page'] == "loco-theme")
                $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been deleted from <b>".$projectName."</b> theme",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
              else
              $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation has been deleted from WordPress <b>".$projectName."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
            }
          }
        }
      }
  }

  /// BackWPup START ///
  
 if(esc_attr(get_option("click5_history_log_backwpup/backwpup.php")) === "1"){
    global $gCurrentUser;
    $usr = $gCurrentUser;
    if(is_null($usr)){
      $usr = wp_get_current_user()->display_name;
    }

    if(is_null($usr))
      $usr = "WordPress Core";

    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(isset($_REQUEST['jobid'])){
      if(isset($_REQUEST['page'])){
        if($_REQUEST['page'] == "backwpupjobs"){
          if(isset($_REQUEST['action'])){
            if($_REQUEST['action'] == "runnow"){
              $jobID = $_REQUEST['jobid'];
              if(class_exists('BackWPup_Option')){
                $jobName = BackWPup_Option::get( $_REQUEST['jobid'], 'name' );
                $wpdb->insert($table_name, array('description'=>"Job <b>".$jobName."</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>$usr));
              }
            }else if($_REQUEST['action'] == "copy"){
              if(class_exists('BackWPup_Option')){
                $jobName = BackWPup_Option::get( $_REQUEST['jobid'], 'name' );
                $wpdb->insert($table_name, array('description'=>"Job <b>".$jobName."</b> has been copied",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>$usr));
              }
            }else if($_REQUEST['action'] == "abort"){
              if(class_exists('BackWPup_Option')){
                $jobName = BackWPup_Option::get( $_REQUEST['jobid'], 'name' );
                $wpdb->insert($table_name, array('description'=>"Job <b>".$jobName."</b> has been aborted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>$usr));
              }
            }
          }
        }
      }else{
        if(isset($_REQUEST['action'])){
          if($_REQUEST['action'] == "runnow"){
            $jobID = $_REQUEST['jobid'];
            if(class_exists('BackWPup_Option')){
              $jobName = BackWPup_Option::get( $_REQUEST['jobid'], 'name' );
              $wpdb->insert($table_name, array('description'=>"Job <b>".$jobName."</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>'WordPress Core'));
            }
          }
        }
      }
    }
  }
  
  /// BackWPup END ///

  /// USER ROLE EDITOR START ///
  if(esc_attr(get_option("click5_history_log_user-role-editor/user-role-editor.php")) === "1"){
    global $gCurrentUser;
    $usr = $gCurrentUser;
    global $wp_roles;
    if(is_null($usr)){
      $usr = wp_get_current_user()->display_name;
    }
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(isset($_REQUEST['new_role']) && isset($_REQUEST['ure_add_role']) && isset($_REQUEST['users'])){
      if(!empty($_REQUEST['users'])){
        if($_REQUEST['ure_add_role_submit'] == "Add"){
         if(!empty($_REQUEST['ure_add_role'])){
            $newRoleName = $wp_roles->roles[$_REQUEST['ure_add_role']]['name'];
            $currentRoleName = click5_get_current_user_role($_REQUEST['users'][0]);
            foreach($_REQUEST['users'] as $user){
              $user = get_user_by('ID',$user);
              $userDisplayName = get_user_by('ID',$_REQUEST['users'][0])->display_name;
              $wpdb->insert($table_name, array('description'=>"New role <b>".$newRoleName."</b> for user <b>".$userDisplayName."</b> ($currentRoleName) has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>$usr));
            }
          }
        }else if($_REQUEST['ure_revoke_role_submit'] == "Revoke"){
          if(!empty($_REQUEST['ure_revoke_role'])){
            $revokeRoleName = $wp_roles->roles[$_REQUEST['ure_revoke_role']]['name'];
            $currentRoleName = click5_get_current_user_role($_REQUEST['users'][0]);
            foreach($_REQUEST['users'] as $user){
              $userDisplayName = get_user_by('ID',$user)->display_name;
              $wpdb->insert($table_name, array('description'=>"Role <b>".$revokeRoleName."</b> for user <b>".$userDisplayName."</b> ($currentRoleName) has been revoked",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>$usr));
            }
          }
        }
      }
    }

    if(isset($_REQUEST['action']) && isset($_REQUEST['sub_action'])){
      if($_REQUEST['action'] == "ure_ajax"){
        if($_REQUEST['sub_action'] == "update_role"){
          if(isset($_REQUEST['values'])){
            if(!empty($_REQUEST['values']) && !empty($_REQUEST['values']['user_id'])){
              $userDisplayName = get_user_by('ID',$_REQUEST['values']['user_id'])->display_name;
              $currentRoleName = click5_get_current_user_role($_REQUEST['values']['user_id']);
              $wpdb->insert($table_name, array('description'=>"<b>".$userDisplayName."</b> ($currentRoleName) user's capabilities have been changed",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>$usr));
            }
          }
        }
      }
    }
  }
  
  /// USER ROLE EDITOR END ///

  /// SITEGROUND OPTIMIZER ///
  if(esc_attr(get_option("click5_history_log_sg-cachepress/sg-cachepress.php")) === "1"){
    global $gCurrentUser;
    $usr = $gCurrentUser;
    if(is_null($usr)){
      $usr = wp_get_current_user()->display_name;
    }

    if(is_null($usr)){
      $usr = "WordPress Core";
    }
    global $wpdb;
    if(isset($_SERVER['REQUEST_URI'])){
      if(strpos($_SERVER['REQUEST_URI'],"siteground-optimizer/v1/purge-cache") !== false){
        $wpdb->insert($table_name, array('description'=>"<b>Cache</b> has been purged",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'SiteGround Optimizer', 'user'=>$usr));
      }else if(strpos($_SERVER['REQUEST_URI'],"siteground-optimizer/v1/run-analysis") !== false){
        $wpdb->insert($table_name, array('description'=>"<b>Analysis</b> has been ran",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'SiteGround Optimizer', 'user'=>$usr));
      }
    }
  }
}

/// DASHBOARD WIDGED SECTION
function click5_history_log_add_dashboard_widget() {
  $version = click5_history_log_DEV_MODE ? time() : click5_history_log_VERSION;
  wp_enqueue_style( 'click5_history_log_css_widget', plugins_url('/css/admin/dashboard_widget.css', __FILE__), array(), $version);
	wp_add_dashboard_widget(
		'click5_history_log_dashboard_widget',
		esc_html__( 'Recent History Log Events', 'history-log-by-click-5' ),
		'click5_history_log_dashboard_widget_render'
	); 
}
add_action( 'wp_dashboard_setup', 'click5_history_log_add_dashboard_widget' );

function click5_history_log_dashboard_widget_render() {
  ?>
    <div id="click5_history_log_dashboard_event_widget">
      <div class="click5_history_events_events">
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        $content = "";
        $request = $wpdb->get_results($wpdb->prepare('SELECT * FROM `'.$table_name.'` WHERE (`user` IS NOT NULL AND (`user` != "" AND `user` != "History Log by click5" AND `description` NOT LIKE "%s")) ORDER BY date DESC LIMIT %d',array('%successfully logged in',5)));
        if(count($request) > 0){
          foreach($request as $event){
            if(strpos($event->description,"(recovery_mode_email)") !== false){
              $content .= '<div class="click5_history_log_event_box"><p>Your Site is Experiencing a Technical Issue (recovery_mode_email)</p>';
            }else  if(strpos($event->description,"(display_default_error_template)") !== false){
              $content .= '<div class="click5_history_log_event_box"><p>Your Site is Experiencing a Technical Issue (display_default_error_template)</p>';
            }else  if(strpos($event->description,"(wp_php_error_message)") !== false){
              $content .= '<div class="click5_history_log_event_box"><p>Your Site is Experiencing a Technical Issue (wp_php_error_message)</p>';
            }else
             $content .= '<div class="click5_history_log_event_box"><p>'.$event->description.'</p>';

             $historyDate = new DateTime(); 
             $historyDate->setTimezone(new DateTimeZone(wp_timezone_string()));
             $historyDate->setTimestamp(strtotime($event->date));
             $historyDate = $historyDate->format(get_option('date_format')." ".get_option('time_format'));
             
             $content .= '<p class="click5_history_log_date"><small>'.$historyDate.'</small></p></div>';
          }
        }else{
          $content = '<div class="click5_history_log_event_box"><p>No events in History Log</p></div>';
        }

        echo $content;
        ?>
      </div>

      <div class="click5_history_events_footer" id="c5_footer">
        <a href="<?php echo esc_url(admin_url('admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php')) ?>">View full history log report</a>
        <?php if((esc_attr(get_option("click5_history_log_technical_issue")) !== "1" && esc_attr(get_option("click5_history_log_critical_error")) !== "1" && esc_attr(get_option("click5_history_log_404")) !== "1") || empty(esc_attr(get_option("click5_history_log_alert_email")))){ ?>
          <br><a style="font-weight: bold" href="<?php echo esc_url(admin_url('admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php&tab=alerts')) ?>">Don’t forget to enable Email Alerts!</a>
        <?php } ?>
      </div>
    </div>
  <?php
}
/// DASHBOARD WIDGET END

add_action("backwpup_cron",function($arg){
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_backwpup/backwpup.php")) === "1"){
    if(class_exists('BackWPup_Option')){
      $jobName = BackWPup_Option::get( $arg, 'name' );
      $wpdb->insert($table_name, array('description'=>"Job <b>".$jobName."</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>'WordPress Core'));
    }
  } 
});

add_action( 'http_api_curl', function( $handle,  $parsed_args, $url){
  $url = parse_url("http://example.com".$_SERVER['REQUEST_URI'],PHP_URL_QUERY);
  $result = "";
  parse_str($url,$result);
  if(!isset($result['page']) || $result['page'] != "history-log-by-click5/history-log-by-click5.php")
    session_write_close();
},10,3);

add_filter( 'recovery_mode_email', function( $email ) {
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_module_wordpress_core")) === "1"){
    $wpdb->insert($table_name, array('description'=>"Your Site is Experiencing a Technical Issue (recovery_mode_email)",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>'WordPress Core'));
    if(esc_attr(get_option("click5_history_log_technical_issue")) == "1"){
      if(get_transient("click5_history_log_mail_transient_technical") === false){
        do_action("click5_history_log_alerts_email");
        set_transient("click5_history_log_mail_transient_technical","1",60);
      }  
    }
  }
  return $email;
} );

add_action("click5_history_log_alerts_email",function(){
  include "mailTemplate.php";
  $_SESSION['background_updates'] = array();
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
    $userEmail = get_option("click5_history_log_alert_email");
    $userName = "";
    $currentDate = new DateTime(); 
    $currentDate->setTimezone(new DateTimeZone(wp_timezone_string()));
    $currentDate->setTimestamp(strtotime("-1 months"));
    $currentDate = $currentDate->format("Y-m-d");
    $dbRequest = $wpdb->get_results($wpdb->prepare('SELECT * FROM `'.$table_name.'` WHERE (`user` IS NOT NULL AND (`user` != "" AND `user` != "History Log by click5" AND `description` NOT LIKE "%s")) AND `date` >= %s ORDER BY date DESC LIMIT %d',array('%successfully logged in',$currentDate,10)));
    $eventsCount = count($dbRequest);
    $mailList = preg_split('/(,|;)/', $userEmail);
    foreach($mailList as $mail){
      $userName = "";
      if(!empty($mail)){
        if(get_user_by('email',$mail) !== false){
          $user = get_user_by('email',$mail);
          if(get_user_meta($user->ID)['first_name'][0] != ""){
              $userName = " ".get_user_meta($user->ID)['first_name'][0];
          }else if(isset($user->display_name) && $user->display_name != ""){
              $userName = " ".$user->display_name;
          }   
        }
       
        $blogName = get_bloginfo('name');
        $blogName = str_replace("&",'&',$blogName);
        $blogName = str_replace("&",'&',$blogName);
        $blogName = wp_specialchars_decode( $blogName, ENT_QUOTES );
        $mailType = "Content-type: text/plain";
        $message = click5_getMailPlainTemplate($userName, $eventsCount,$dbRequest);
        if(esc_attr(get_option("click5_history_log_email_template")) == "html"){
          $message = click5_getMailHtmlTemplate($userName, $eventsCount,$dbRequest);
          $mailType = "Content-type: text/html";
        }
          
        wp_mail($mail,'Alert about '.$blogName.' website issue',$message,array($mailType,'charset=UTF-8'));  
      }   
    }
});

add_filter( 'display_default_error_template', 'click5_error_catch', 10, 3 );
function click5_error_catch()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_module_wordpress_core")) === "1"){
    $wpdb->insert($table_name, array('description'=>"Your Site is Experiencing a Technical Issue (display_default_error_template)",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>'WordPress Core'));
    if(esc_attr(get_option("click5_history_log_technical_issue")) == "1"){
      if(get_transient("click5_history_log_mail_transient_technical") === false){
        do_action("click5_history_log_alerts_email");
        set_transient("click5_history_log_mail_transient_technical","1",60);
      }  
    }
  }
}
add_filter( 'wp_php_error_message', 'click5_error_catch2', 10, 3 );
function click5_error_catch2($message, $error)
{
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $stackTracePos = strpos(strtolower($error['message']),"stack trace");
  $err = substr_replace($error['message'],"",strpos($error['message'],"Stack trace"));
  if($stackTracePos === false)
    $err = $error['message'];
  //$err = substr_replace($error['message'],"",strpos($error['message'],"Stack trace"));
    if(esc_attr(get_option("click5_history_log_module_wordpress_core")) === "1"){
    $wpdb->insert($table_name, array('description'=>"Your Site is Experiencing a Technical Issue (wp_php_error_message)<br>$err",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>'WordPress Core'));
    if(esc_attr(get_option("click5_history_log_technical_issue")) == "1"){
      if(get_transient("click5_history_log_mail_transient_technical") === false){
        do_action("click5_history_log_alerts_email");
        set_transient("click5_history_log_mail_transient_technical","1",60);
      }  
    }
  }
}

add_filter( 'cron_schedules', 'click5_add_every_minutes' );
function click5_add_every_minutes( $schedules ) {
    $schedules['every_interval'] = array(
            'interval' => 1800
    );
    return $schedules;
}

if ( ! wp_next_scheduled( 'click5_add_every_minutes' ) ) {
    wp_schedule_event( time(), 'every_interval', 'click5_add_every_minutes' );
}

add_action( 'click5_add_every_minutes', 'click5_clean_event_func' );
function click5_clean_event_func() {
  if(esc_attr(get_option('click5_log_store_time')) != 'indefinitely') {
    $date_now = new DateTime('now');
    $status_clean = esc_attr(get_option("click5_log_store_time"));
    if($status_clean == 'day') {
      $date_now->modify('-1 day');
      $date_now = $date_now->format('Y-m-d h:i A');
     // update_option( 'click5_history_log_clear_date', $date_now);
    }  
    if($status_clean == 'week') {
      $date_now->modify('-7 days');
      $date_now = $date_now->format('Y-m-d 23:59');
     // update_option( 'click5_history_log_clear_date', $date_now);
    } 
    if($status_clean == 'month') {
      $date_now->modify('-1 month');
      $date_now = $date_now->format('Y-m-d h:i A');
    //  update_option( 'click5_history_log_clear_date', $date_now);
    } 
    if($status_clean == '3_month') {
      $date_now->modify('-3 month');
      $date_now = $date_now->format('Y-m-d h:i A');
     // update_option( 'click5_history_log_clear_date', $date_now);
    } 
    if($status_clean == '6_month') {
      $date_now->modify('-6 month');
      $date_now = $date_now->format('Y-m-d h:i A');
     // update_option( 'click5_history_log_clear_date', $date_now);
    } 
    if($status_clean == '12_month') {
      $date_now->modify('-12 month');
      $date_now = $date_now->format('Y-m-d h:i A');
      //update_option( 'click5_history_log_clear_date', $date_now);
    } 
    
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
      $sql_clean_log = "DELETE FROM $table_name where date <= '$date_now'";
      $res = $wpdb->query($sql_clean_log);
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      if(esc_attr(get_option('click5_history_log_history-log-by-click5/history-log-by-click5.php')) == "1" && $res>0 ) {
        //Daily **History Log** data purge (X records deleted)
        if($res == 1)
        {
          $wpdb->insert($table_name, array('description'=>"Daily <b>History Log</b> data has been purged (".$res." record deleted)",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>'History Log by click5'));
        }
        else
        {
          $wpdb->insert($table_name, array('description'=>"Daily <b>History Log</b> data has been purged (".$res." records deleted)",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>'History Log by click5'));
        }
      }
      /*else
      {
        $date_from = date('Y-m-d 00:00:00');
        $date_to = date('Y-m-d 23:59:59');
        //$query = "SELECT * FROM $table_name where plugin =History Log by click5";
        $sql =" SELECT id FROM ". $table_name . " WHERE description like '%data has been purged (%' AND date >= '". $date_from . "' AND date <= '". $date_to . "' ";
        global $wpdb;
        $query =$wpdb->query($sql);
        if($query==0)
        {
         $wpdb->insert($table_name, array('description'=>"Daily <b>History Log</b> data has been purged (0 records deleted)",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>'History Log by click5'));
        }
      }*/
  }
}

add_action('admin_menu', 'click5_history_log_create_menu');
function click5_history_log_create_menu() {
  $capability  = apply_filters( 'click5_history_log_required_capabilities', 'manage_options' );
  add_menu_page('History Log', 'History Log', $capability , __FILE__, 'click5_history_log_settings_page' , 'dashicons-editor-table');
  add_action( 'admin_init', 'click5_history_log_activation_redirect' );
}

function click5_history_log_activation_redirect( $plugin ) {
  if( $plugin == plugin_basename( __FILE__ ) ) {
      exit( wp_redirect( admin_url( 'admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php' ) ) );
  }
}

add_action( 'activated_plugin', 'click5_history_log_activation_redirect' );

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'click5_history_log_add_plugin_page_settings_link');
function click5_history_log_add_plugin_page_settings_link( $links ) {
  $links[] = '<a href="' .
  admin_url( 'admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php' ).
    '">' . __('Settings') . '</a>';
  return $links;
}

add_filter( 'plugin_row_meta', 'click5_history_log_plugin_meta', 10, 2 );
function click5_history_log_plugin_meta( $links, $file ) { 
	if ( strpos( $file, 'history-log-by-click5.php' ) !== false ) {
    array_splice( $links, 2, 0, array( '<a href="https://www.click5interactive.com/wordpress-history-log-plugin/?utm_source=history-plugin&utm_medium=plugin-list&utm_campaign=wp-plugins" target="_blank" rel="nofollow">About plugin</a>' ) );
	}
	return $links;
}

function click5_history_log_activation(){
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $db_version = '1.0.0';
    $charset_c = $wpdb->get_charset_collate();
    delete_option( 'click5_log_store_time' );
    delete_option( 'click5_history_log_clear_date' );
    add_option( 'click5_log_store_time', '6_month');
    add_option("click5_history_log_critical_error",true);
    add_option("click5_history_log_technical_issue",true);
    add_option("click5_history_log_email_template","plain");
    add_option("click5_history_log_alert_email");
    $date = new DateTime('now');
    $date->modify('+6 month');
    $date = $date->format('Y-m-d h:i A');
    add_option( 'click5_history_log_clear_date', $date);

    $support_plugin_list = array(
    "advanced-custom-fields/acf.php",
    "acf-repeater/acf-repeater.php",
    "acf-extended/acf-extended.php", 
    "all-in-one-seo-pack/all_in_one_seo_pack.php",
    "all-in-one-seo-pack-pro/all_in_one_seo_pack.php",
    "classic-editor/classic-editor.php",
    "contact-form-7/wp-contact-form-7.php",
    "disable-comments-by-click5/disable-comments-by-click5.php",
    "history-log-by-click5/history-log-by-click5.php",
    "google-site-kit/google-site-kit.php",
    "seo-by-rank-math/rank-math.php",
    "seo-by-rank-math-pro/rank-math-pro.php",
    "sitemap-by-click5/sitemap-by-click5.php",
    "wordfence/wordfence.php",
    "wordpress-seo/wp-seo.php",
    "wordpress-seo-premium/wp-seo-premium.php",
    "cf7-add-on-by-click5/cf7-addon-by-click5.php",
    "wpf-add-on-by-click5/wpf-addon-by-click5.php",
    "gf-add-on-by-click5/gf-addon-by-click5.php",
    "click5-crm-add-on-to-ninja-forms/ninja-addon-by-click5.php",
    "wpforms-lite/wpforms.php",
    "wpforms/wpforms.php",
    "ninja-forms/ninja-forms.php",
    "advanced-custom-fields-pro/acf.php",
    "better-search-replace/better-search-replace.php",
    "better-search-replace-pro/better-search-replace.php",
    "redirection/redirection.php",
    "health-check/health-check.php",
    "classic-widgets/classic-widgets.php",
    "instagram-feed/instagram-feed.php",
    "wp-google-maps/wpGoogleMaps.php",
    "wp-google-maps-pro/wp-google-maps-pro.php",
    "jetpack/jetpack.php",
    "duplicate-post/duplicate-post.php",
    "all-in-one-wp-migration/all-in-one-wp-migration.php",
    "updraftplus/updraftplus.php",
    "duplicator/duplicator.php",
    "loco-translate/loco.php",
    "polylang/polylang.php",
    "limit-login-attempts-reloaded/limit-login-attempts-reloaded.php",
    "sg-cachepress/sg-cachepress.php",
    "user-role-editor/user-role-editor.php",
    "backwpup/backwpup.php",
    "string-locator/string-locator.php",
    "wp-mail-log/wp-mail-log.php"
    );
    foreach($support_plugin_list as $support_plugin_list_item) {
      delete_option('click5_history_log_' . $support_plugin_list_item);
      add_option('click5_history_log_' . $support_plugin_list_item, "1");
    }
    
    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {

        $sql = "CREATE TABLE $table_name (
                ID mediumint(9) NOT NULL AUTO_INCREMENT,
                `description` text NOT NULL,
                `user` text NOT NULL,
                `plugin` text NOT NULL,
                `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                PRIMARY KEY  (ID)
        ) $charset_c;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        add_option('my_db_version', $db_version);
    }
}
register_activation_hook( __FILE__, 'click5_history_log_activation' );

add_action('_core_updated_successfully', 'click5_core_updated_successfully');
function click5_core_updated_successfully($data) {
  global $wpdb;
  global $gCurrentUser;
  global $pagenow;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_module_wordpress_core")) === "1"){
    if('update-core.php' == $pagenow) {
      $wpdb->insert($table_name, array('description'=>"<b>WordPress</b> has been updated from version " . $GLOBALS['wp_version'] . " to <b>" . $data . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
    } else {
      $wpdb->insert($table_name, array('description'=>"<b>WordPress</b> has been auto-updated from version " . $GLOBALS['wp_version'] . " to <b>" . $data . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>"WordPress Core"));
    }
  }
}

add_action('after_switch_theme', 'click5_after_switch_theme');
function click5_after_switch_theme($data) {
  global $wpdb;
  global $gCurrentUser;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_module_themes")) === "1")
    $wpdb->insert($table_name, array('description'=>"Theme <b>" . $data . "</b> has been deactivated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Themes', 'user'=>$gCurrentUser));
}

add_action('switch_theme', 'click5_switch_theme');
function click5_switch_theme($data) {
  global $wpdb;
  global $gCurrentUser;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_module_themes")) === "1")
    $wpdb->insert($table_name, array('description'=>"Theme <b>" . $data . "</b> has been activated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Themes', 'user'=>$gCurrentUser));
}

add_action('delete_theme', 'click5_delete_theme');
function click5_delete_theme($data) {
  global $wpdb;
  global $gCurrentUser;
  $theme_data = wp_get_theme($data);
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_module_themes")) === "1")
    $wpdb->insert($table_name, array('description'=>"Theme <b>" . $theme_data->get('Name') . "</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Themes', 'user'=>$gCurrentUser));
}

add_action('wp_create_nav_menu', 'click5_wp_create_nav_menu', 10, 2);
function click5_wp_create_nav_menu($id, $data) {
  global $wpdb;
  global $gCurrentUser;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_module_menu")) === "1")
    $wpdb->insert($table_name, array('description'=>"Menu <b>" . $data['menu-name'] . "</b> has been created",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Menu', 'user'=>$gCurrentUser));
}

add_action('wp_update_nav_menu', 'click5_wp_update_nav_menu', 10, 2);
function click5_wp_update_nav_menu($info, $data = NULL) {
  if($data != null) {
    global $wpdb;
    global $gCurrentUser;
    $table_name = $wpdb->prefix . "c5_history";
    if(esc_attr(get_option("click5_history_log_module_menu")) === "1")
      $wpdb->insert($table_name, array('description'=>"Menu <b>" . $data['menu-name'] . "</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Menu', 'user'=>$gCurrentUser));
  }
}

add_action('delete_nav_menu', 'click5_delete_nav_menu', 10, 3);
function click5_delete_nav_menu($id, $info, $data) {
  global $wpdb;
  global $gCurrentUser;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_module_menu")) === "1")
    $wpdb->insert($table_name, array('description'=>"Menu <b>" . $data->name . "</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Menu', 'user'=>$gCurrentUser));
}

add_action( 'activated_plugin', function($plugin){ 
  $info = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
  $name_plugin = $info['Name'];
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if($name_plugin != "" && $name_plugin != " " && $name_plugin != null) {
    if(esc_attr(get_option("click5_history_log_module_plugins")) === "1")
      $wpdb->insert($table_name, array('description'=>"<b>" . $name_plugin . "</b> plugin has been activated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Plugins', 'user'=>wp_get_current_user()->user_login));
  } 
});

add_action( 'deactivated_plugin', function($plugin){ 
  $info = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
  $name_plugin = $info['Name'];
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history"; 
  if($name_plugin != "" && $name_plugin != " " && $name_plugin != null) { 
    if(esc_attr(get_option("click5_history_log_module_plugins")) === "1")
      $wpdb->insert($table_name, array('description'=>"<b>" . $name_plugin . "</b> plugin has been deactivated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Plugins', 'user'=>wp_get_current_user()->user_login));
  }
});

add_action( 'delete_plugin', function($plugin){ 
  $info = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
  $name_plugin = $info['Name'];
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history"; 
  if($name_plugin != "" && $name_plugin != " " && $name_plugin != null) { 
    if(esc_attr(get_option("click5_history_log_module_plugins")) === "1")
      $wpdb->insert($table_name, array('description'=>"<b>" . $name_plugin . "</b> plugin has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Plugins', 'user'=>wp_get_current_user()->user_login));
  }
});


add_action('upgrader_process_complete', 'click5_after_plugin_install', 10, 2);
function click5_after_plugin_install($plugin_upgrader_instance, $arr_data)
{
  if (isset($arr_data['type']) && 'translation' == $arr_data['type'] && 'update' == $arr_data['action'] && $arr_data['translations'][0]["type"]=="core") { 
    require_once ABSPATH . 'wp-admin/includes/translation-install.php';
    $lang_name = $arr_data['translations'][0]["language"];
    $translations = wp_get_available_translations();  
    foreach($translations as $translation_item) {
      if($translation_item["language"] == $arr_data['translations'][0]["language"]) {
        $lang_name = $translation_item["english_name"];
      }
    }
    global $wpdb;
    global $gCurrentUser;
    $table_name = $wpdb->prefix . "c5_history";
    //$wpdb->insert($table_name, array('description'=>"<b>Translation</b> for <b>" . $lang_name . "</b> language has been updated to version <b>" . $arr_data['translations'][0]["version"] . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
  }
  if (isset($arr_data['type']) && 'theme' == $arr_data['type'] && 'install' == $arr_data['action']) { 
    global $wpdb;
    global $gCurrentUser;
    $table_name = $wpdb->prefix . "c5_history";
    if(esc_attr(get_option("click5_history_log_module_themes")) === "1")
      $wpdb->insert($table_name, array('description'=>"Theme <b>" . $plugin_upgrader_instance->new_theme_data["Name"] . "</b> has been installed",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Themes', 'user'=>$gCurrentUser));
  }
  if (isset($arr_data['type']) && 'theme' == $arr_data['type'] && 'update' == $arr_data['action']) { 
    global $wpdb;
    global $gCurrentUser;
    $theme_data = wp_get_theme($arr_data['themes'][0]);
    $h = $theme_data->get("headers");
    $table_name = $wpdb->prefix . "c5_history";
    if(esc_attr(get_option("click5_history_log_module_themes")) === "1")
      $wpdb->insert($table_name, array('description'=>"Theme <b>" . $theme_data->get('Name') . "</b> has been updated from version " . get_option("version_theme_" . $theme_data->get('TextDomain')) .  " to <b>" . $theme_data->get('Version') . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Themes', 'user'=>$gCurrentUser));
    update_option("version_theme_" . $theme_data->get('TextDomain'), $theme_data->get('Version'));
  }
  if (isset($arr_data['type']) && 'plugin' == $arr_data['type']) {
    if (isset($arr_data['action']) && 'install' == $arr_data['action'] && ! $plugin_upgrader_instance->bulk) {
      $upgrader_skin_options = isset($plugin_upgrader_instance->skin->options) && is_array($plugin_upgrader_instance->skin->options) ? $plugin_upgrader_instance->skin->options : array();
      $upgrader_skin_result  = isset($plugin_upgrader_instance->skin->result) && is_array($plugin_upgrader_instance->skin->result) ? $plugin_upgrader_instance->skin->result : array();
      $upgrader_skin_api     = isset($plugin_upgrader_instance->skin->api) ? $plugin_upgrader_instance->skin->api : (object) array();
      $plugin_slug = isset($upgrader_skin_result['destination_name']) ? $upgrader_skin_result['destination_name'] : '';
      $plug_list = get_plugins();
      foreach($plug_list as $single_plug){
        if($plugin_slug == $single_plug['TextDomain']) {
          global $wpdb;
          $table_name = $wpdb->prefix . "c5_history"; 
          if(esc_attr(get_option("click5_history_log_module_plugins")) === "1")
            $wpdb->insert($table_name, array('description'=>"<b>" . $single_plug['Name'] . "</b> plugin has been installed",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Plugins', 'user'=>wp_get_current_user()->user_login));
        }
      }
    }
    if(isset($arr_data['action']) && 'update' == $arr_data['action']) {
      $upgrader_skin_options = isset($plugin_upgrader_instance->skin->options) && is_array($plugin_upgrader_instance->skin->options) ? $plugin_upgrader_instance->skin->options : array();
      $upgrader_skin_result  = isset($plugin_upgrader_instance->skin->result) && is_array($plugin_upgrader_instance->skin->result) ? $plugin_upgrader_instance->skin->result : array();
      $upgrader_skin_api     = isset($plugin_upgrader_instance->skin->api) ? $plugin_upgrader_instance->skin->api : (object) array();
      $plugin_slug = isset($upgrader_skin_result['destination_name']) ? $upgrader_skin_result['destination_name'] : '';
      $plug_list = get_plugins();
      foreach($plug_list as $single_plug){
        if($plugin_slug == $single_plug['TextDomain']) {
          global $wpdb;
          global $previous_plugins_list;
          foreach($previous_plugins_list as $key => $previous_plugin_item) {
            if(strpos($key, $single_plug['TextDomain']) !== false){ 
              $old_version = $previous_plugin_item['Version'];
            }
          }
          $table_name = $wpdb->prefix . "c5_history"; 
          if(esc_attr(get_option("click5_history_log_module_plugins")) === "1")
            $wpdb->insert($table_name, array('description'=>"<b>" . $single_plug['Name'] . "</b> plugin has been updated from version " . $old_version . " to <b>" . $single_plug['Version'] . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Plugins', 'user'=>wp_get_current_user()->user_login));
        }
        if($plugin_slug == "advanced-custom-fields" && $single_plug['TextDomain'] == "acf") {
          global $wpdb;
          global $previous_plugins_list;
          foreach($previous_plugins_list as $key => $previous_plugin_item) {
            if($key != "acf-repeater/acf-repeater.php" && strpos($key, $single_plug['TextDomain']) !== false){ 
              $old_version = $previous_plugin_item['Version'];
            }
          }
          $table_name = $wpdb->prefix . "c5_history"; 
          if(esc_attr(get_option("click5_history_log_module_plugins")) === "1")
            $wpdb->insert($table_name, array('description'=>"<b>" . $single_plug['Name'] . "</b> plugin has been updated from version " . $old_version . " to <b>" . $single_plug['Version'] . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Plugins', 'user'=>wp_get_current_user()->user_login));
        }
      }
    }
  }
}

add_action( 'register_activation_hook', function($plugin){ 
  global $wpdb;
});

$option = 'auto_update_plugins';

add_filter("update_option_{$option}", function ($val1, $val2, $option) {
  if($option == 'auto_update_plugins') {
    $v1 = count($val1);
    $v2 = count($val2);
    if($v2 > $v1) {
      foreach($val2 as $v) {
        $flag = false;
        foreach($val1 as $y) {
          if($v == $y) {
            $flag = true;
          }
        }
        if(!$flag) {
          $info = get_plugin_data(WP_PLUGIN_DIR . '/' . $v);
          $name_plugin = $info['Name'];
          global $wpdb;
          $table_name = $wpdb->prefix . "c5_history"; 
          if($name_plugin != "" && $name_plugin != " " && $name_plugin != null) { 
            if(esc_attr(get_option("click5_history_log_module_plugins")) === "1")
              $wpdb->insert($table_name, array('description'=>"<b>" . $name_plugin . "</b> plugin auto update has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Plugins', 'user'=>wp_get_current_user()->user_login));      
          }
        }
      }
    }
    if($v1 > $v2) {
      foreach($val1 as $v) {
        $flag = false;
        foreach($val2 as $y) {
          if($v == $y) {
            $flag = true;
          }
        }
        if(!$flag) {
          $info = get_plugin_data(WP_PLUGIN_DIR . '/' . $v);
          $name_plugin = $info['Name'];
          global $wpdb;
          $table_name = $wpdb->prefix . "c5_history"; 
          if($name_plugin != "" && $name_plugin != " " && $name_plugin != null) { 
            if(esc_attr(get_option("click5_history_log_module_plugins")) === "1")
              $wpdb->insert($table_name, array('description'=>"<b>" . $name_plugin . "</b> plugin auto update has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Plugins', 'user'=>wp_get_current_user()->user_login));
          }        
        }
      }
    }
  }
}, 10, 3);


add_action( 'automatic_updates_complete', 'click5_automatic_updates_complete' );
function click5_automatic_updates_complete( $results ) {
    // the list of plugins is contained within the "plugins" attribute
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if($results["translation"][0]->name == "Translations for WordPress") {
      require_once ABSPATH . 'wp-admin/includes/translation-install.php';
      $lang_name = $results["translation"][0]->item->language;
      $translations = wp_get_available_translations();  
      foreach($translations as $translation_item) {
        if($translation_item["language"] ==  $results["translation"][0]->item->language) {
          $lang_name = $translation_item["english_name"];
        }
      }
      //$wpdb->insert($table_name, array('description'=>"<b>Translation</b> for <b>" . $lang_name . "</b> language has been auto-updated to version <b>" .  $results["translation"][0]->item->version . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>'WordPress Core'));
    } 
    foreach ( $results['plugin'] as $plugin ) {
        // make sure the plugin slug matches the one assigned to your own plugin
        if ( ! empty( $plugin->name )) {
            if($plugin->result)
            {
              if(esc_attr(get_option("click5_history_log_module_plugins")) === "1")
                $wpdb->insert($table_name, array('description'=>"<b>" . $plugin->name . "</b> plugin has been auto-updated from version " . $plugin->item->current_version . " to <b>" . $plugin->item->new_version . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Plugins', 'user'=>'WordPress Core'));
            }
        }
    }

    foreach ( $results['theme'] as $theme ) {
      // make sure the plugin slug matches the one assigned to your own plugin
      if ( ! empty( $theme->name )) {
          if($theme->result)
          {
            if(esc_attr(get_option("click5_history_log_module_themes")) === "1")
              $wpdb->insert($table_name, array('description'=>"<b>" . $theme->name . "</b> theme has been auto-updated from version " . $theme->item->current_version . " to <b>" . $theme->item->new_version . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Themes', 'user'=>'WordPress Core'));
          }
      }
   }
}



add_action( 'user_register', 'click5_registration_save', 10, 1 );
function click5_registration_save( $user_id ) {
  $user_data = get_userdata( $user_id );
  //$user_meta = get_usermeta($user_id);
  global $wpdb;
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $tab_name = $wpdb->base_prefix . 'users';
  $table_name = $wpdb->prefix . "c5_history";
  $user_login = "";
  $isAdded = false;
  $userDetails = "";
  $userRole = "";

  $user_login =wp_get_current_user()->user_login;
  if(wp_get_current_user()->user_login!=false)
  {
    $isAdded = true;
    $user_login =wp_get_current_user()->user_login;
  }
  else
  {
    $user_login = $user_data->data->user_login; //'WordPress Core';
  }


  if($first_name != "" && $last_name != "")
  {
    $userDetails = $first_name . " " .$last_name;
  }
  else
  {
    $userDetails =$user_data->data->display_name;
  }

  
  if (isset($user_data->roles)) 
  {
    if(count($user_data->roles)>0)
    {
      $userRole = $user_data->roles[0];
      global $wp_roles;
      $userRole = array_values((array)$wp_roles->roles[$userRole]['name'])[0];
      if(function_exists("ucfirst"))
      {
        $userRole = ucfirst($userRole);
      }
      $userRole = "(". $userRole . ")";
    }
  }

  if($isAdded)
  {
    if(esc_attr(get_option("click5_history_log_module_users")) === "1")
      $wpdb->insert($table_name, array('description'=>"New User <b>" . $userDetails . "</b> " .$userRole. " has been added to this site",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Users', 'user'=>$user_login));
  }
  else
  {
    if(esc_attr(get_option("click5_history_log_module_users")) === "1")
      $wpdb->insert($table_name, array('description'=>"New User <b>" . $userDetails . "</b> " .$userRole. " has registered",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$user_login));
  }
}


add_action( 'publish_page', 'click5_publish_page' );
function click5_publish_page($postid) {
  global $gCurrentUser;
  global $wpdb;
  $post_data = get_post($postid);
  $table_name = $wpdb->prefix . "c5_history";
  if($post_data->post_type == "post") {
    if(esc_attr(get_option("click5_history_log_module_posts")) === "1"){
      if($post_data->post_date == $post_data->post_modified) {
        $wpdb->insert($table_name, array('description'=>"Post <b>" . $post_data->post_title . "</b>  has been published",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
      } else {
        $wpdb->insert($table_name, array('description'=>"Post <b>" . $post_data->post_title . "</b>  has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
      }
    }
  }

  if($post_data->post_type == "page") {
    if(esc_attr(get_option("click5_history_log_module_pages")) === "1"){
      if($post_data->post_date == $post_data->post_modified) {
        $wpdb->insert($table_name, array('description'=>"Page <b>" . $post_data->post_title . "</b>  has been published",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
      } else {
        $wpdb->insert($table_name, array('description'=>"Page <b>" . $post_data->post_title . "</b>  has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
      }
    }
  }
}

add_action( 'publish_post', 'click5_publish_post' );
function click5_publish_post($postid) {
  global $gCurrentUser;
  global $wpdb;
  $post_data = get_post($postid);
  $table_name = $wpdb->prefix . "c5_history";
  if($post_data->post_type == "post") {
    if(esc_attr(get_option("click5_history_log_module_posts")) === "1"){
      if($post_data->post_date == $post_data->post_modified) {
        $wpdb->insert($table_name, array('description'=>"Post <b>" . $post_data->post_title . "</b>  has been published",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
      } else {
        $wpdb->insert($table_name, array('description'=>"Post <b>" . $post_data->post_title . "</b>  has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
      }
    }
  }
  if($post_data->post_type == "page") {
    if(esc_attr(get_option("click5_history_log_module_pages")) === "1"){
      if($post_data->post_date == $post_data->post_modified) {
        $wpdb->insert($table_name, array('description'=>"Page <b>" . $post_data->post_title . "</b>  has been published",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
      } else {
        $wpdb->insert($table_name, array('description'=>"Page <b>" . $post_data->post_title . "</b>  has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
      }
    }
  }
}

add_action( 'updated_post_meta', 'click5_meta_update_acf_repeater', 10, 4 );

function click5_meta_update_acf_repeater( $post, $p_id, $meta, $data ) {
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $valid_data = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
  foreach($valid_data as $valid_item) {
    if($valid_item === $data) {
      $post_data = get_post($p_id);

      $post_singularName = $post_data->post_type;
      $post_to_check = get_post_type_object( $post_data->post_type );
      if(isset($post_to_check->labels))
      {
        if(!is_null($post_to_check->labels) || $post_to_check->labels != "")
        {
          $post_singularName = $post_to_check->labels->singular_name;
          if(isset($post_singularName))
          {
            if(is_null($post_singularName) || $post_singularName == "") 
              $post_singularName = $post_data->post_type;
          }else{
            $post_singularName = $post_data->post_type;
          }
        }
      }

      global $gRepEditDeleteData;
      global $gRepEditAddData;
      $times = "times";
      $repeaters = "repeaters";
      if($data == 1) {
        $repeaters = "repeater";
      }
      if($gRepEditDeleteData[0] > 0 && $gRepEditAddData[0] > 0) {
        if(esc_attr(get_option('click5_history_log_acf-extended/acf-extended.php')) == "1") {  
          $wpdb->insert($table_name, array('description'=>"<b>" . $meta . "</b> repeater has been addeded <b>" . $gRepEditAddData[0] . " more times</b> and deleted <b>" . $gRepEditDeleteData[0] . " times</b> in " . $post_singularName . " " . $post_data->post_title . " and now this " . $post_singularName . " contains " . $data . " repeater",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'ACF Repeater', 'user'=>$gCurrentUser));
        }
        $gRepEditDeleteData = array(0, "");
        $gRepEditAddData = array(0, "");
      } elseif($gRepEditDeleteData[0] > 0) {
        $repeaters_del = "repeaters";
        if($gRepEditDeleteData[0] == 1) {
          $repeaters_del = "repeater";
        }
        if(esc_attr(get_option('click5_history_log_acf-extended/acf-extended.php')) == "1") {  
          $wpdb->insert($table_name, array('description'=> $gRepEditDeleteData[0] ." <b>" . $meta . "</b> " . $repeaters_del . " has been deleted from " . $post_singularName . " <b>" . $post_data->post_title . "</b> and now this " . $post_singularName . " contains " . $data . " " . $repeaters,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'ACF Repeater', 'user'=>$gCurrentUser));
        }
        $gRepEditDeleteData = array(0, "");
      } elseif($gRepEditAddData[0] > 0) {
        if($gRepEditAddData[0] == 1) {
          $times = "time";
        }
        if(esc_attr(get_option('click5_history_log_acf-extended/acf-extended.php')) == "1") {  
          $wpdb->insert($table_name, array('description'=>"<b>" . $meta . "</b> repeater has been addeded " . $gRepEditAddData[0] . " more " . $times . " in " . $post_singularName . " <b>" . $post_data->post_title . "</b> and now this " . $post_singularName . " contains " . $data . " " . $repeaters,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'ACF Repeater', 'user'=>$gCurrentUser));
        }
        $gRepEditAddData = array(0, "");
      } 
    }
  }
  if($data == "") {
    global $gRepEditDeleteData;
    $post_data = get_post($p_id);

    $post_singularName = $post_data->post_type;
    $post_to_check = get_post_type_object( $post_data->post_type );
    if(isset($post_to_check->labels))
    {
      if(!is_null($post_to_check->labels) || $post_to_check->labels != "")
      {
        $post_singularName = $post_to_check->labels->singular_name;
        if(isset($post_singularName))
        {
          if(is_null($post_singularName) || $post_singularName == "") 
            $post_singularName = $post_data->post_type;
        }else{
          $post_singularName = $post_data->post_type;
        }
      }
    }

    if($gRepEditDeleteData[0] > 0) {
      $repeaters_del = "repeaters";
      if($gRepEditDeleteData[0] == 1) {
        $repeaters_del = "repeater";
      }
      if(esc_attr(get_option('click5_history_log_acf-extended/acf-extended.php')) == "1") {  

      }
      $wpdb->insert($table_name, array('description'=>$gRepEditDeleteData[0] ." <b>" . $meta . "</b> " . $repeaters_del . " has been deleted from " . $post_singularName . " <b>" . $post_data->post_title . "</b> and now this " . $post_singularName . " doesn't contain any reapters",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'ACF Repeater', 'user'=>$gCurrentUser));
      $gRepEditDeleteData = array(0, "");
    }
  }
}

add_action( 'deleted_post_meta', 'click5_meta_deleted_acf_repeater', 10, 4 );

function click5_meta_deleted_acf_repeater( $post, $p_id, $meta, $data ) {
  global $gCurrentUser;
  global $wpdb;
  global $gRepEditDeleteData;
  $table_name = $wpdb->prefix . "c5_history";
  $check_data = array('_0_', '_1_', '_2_', '_3_', '_4_', '_5_', '_6_', '_7_', '_8_', '_9_', '_10_');
  foreach($check_data as $data_item) {
    if(strpos($meta, $data_item) !== false && $meta[0] != "_" && $gRepEditDeleteData[1] != $data_item) {
      $counter_delete = $gRepEditDeleteData[0];
      $gRepEditDeleteData = array($counter_delete + 1, $data_item);
    }
  }
}


add_action( 'added_post_meta', 'click5_meta_acf_repeater', 10, 4 );

function click5_meta_acf_repeater( $post, $p_id, $meta, $data ) {
  global $gCurrentUser;
  global $wpdb;
  global $gRepData;
  $table_name = $wpdb->prefix . "c5_history";
  global $gRepEditAddData;
  $check_data = array('_0_', '_1_', '_2_', '_3_', '_4_', '_5_', '_6_', '_7_', '_8_', '_9_', '_10_');
  foreach($check_data as $data_item) {
    if(strpos($meta, $data_item) !== false && $meta[0] != "_" && $gRepEditAddData[1] != $data_item) {
      $counter_add = $gRepEditAddData[0];
      $gRepEditAddData = array($counter_add + 1, $data_item);
    }
  }

  if($gRepData[1] == false && strpos($meta, '_0_') !== false && $meta[0] != "_") {
    $gRepData = array(1, true);
  } else {
    if($gRepData[1] == true) {
      if($gRepData[0] == $data) {
        $post_data = get_post($p_id);

        $post_singularName = $post_data->post_type;
        $post_to_check = get_post_type_object( $post_data->post_type );
        if(isset($post_to_check->labels))
        {
          if(!is_null($post_to_check->labels) || $post_to_check->labels != "")
          {
            $post_singularName = $post_to_check->labels->singular_name;
            if(isset($post_singularName))
            {
              if(is_null($post_singularName) || $post_singularName == "") 
                $post_singularName = $post_data->post_type;
            }else{
              $post_singularName = $post_data->post_type;
            }
          }
        }

        if($post_singularName != "Revision"){
          if($data==1)
          {
            if(esc_attr(get_option('click5_history_log_acf-extended/acf-extended.php')) == "1") { 
              $wpdb->insert($table_name, array('description'=>"<b>" . ucfirst($meta) . "</b> repeater has been used <b>" . $data . " time</b> in " . strtolower($post_singularName) . " <b>" . $post_data->post_title."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'ACF Repeater', 'user'=>$gCurrentUser));
            }
          }
          else
          {
            if(esc_attr(get_option('click5_history_log_acf-extended/acf-extended.php')) == "1") { 
              $wpdb->insert($table_name, array('description'=>"<b>" . ucfirst($meta) . "</b> repeater has been used <b>" . $data . " times</b> in " . strtolower($post_singularName) . " <b>" . $post_data->post_title."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'ACF Repeater', 'user'=>$gCurrentUser));
            }
          }
        }
        
        $gRepData = array(0, false);
      } else if(strpos($meta, '_' . $gRepData[0] . '_') !== false && $meta[0] != "_") {
        $count = $gRepData[0];
        $gRepData = array($count + 1, true);
      }
    }
  }
}


add_action('transition_comment_status', 'click5_approve_comment', 10, 3);
function click5_approve_comment($new_status, $old_status, $comment) {
  if($old_status != $new_status) {
      if($new_status == 'approved' && $old_status != "trash" && $old_status != "spam") {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        $wpdb->insert($table_name, array('description'=>"Comment by" . " <b>" . $comment->comment_author . "</b> has been approved",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Comments', 'user'=>$gCurrentUser));
      }
  }
  if($old_status != $new_status) {
    if($new_status == 'unapproved' && $old_status != "trash" && $old_status != "spam") {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>"Comment by" . " <b>" . $comment->comment_author . "</b> has been unapproved",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Comments', 'user'=>$gCurrentUser));
    }
  }
  if($old_status != $new_status) {
    if(($new_status == 'approved' || $new_status == 'unapproved') && $old_status == "spam") {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>"Comment by" . " <b>" . $comment->comment_author . "</b> has been unmarked as spam",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Comments', 'user'=>$gCurrentUser));
    }
  }
}

add_action( 'spam_comment', 'click5_spam_comment', 10, 2 );
function click5_spam_comment($comment_id, $comment) { 
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $wpdb->insert($table_name, array('description'=>"Comment by" . " <b>" . $comment->comment_author . "</b> has been marked as spam",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Comments', 'user'=>$gCurrentUser));
}

add_action( 'untrashed_comment', 'click5_untrashed_comment', 10, 2 );
function click5_untrashed_comment($comment_id, $comment) { 
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $wpdb->insert($table_name, array('description'=>"Comment by" . " <b>" . $comment->comment_author . "</b> has been restored from Trash",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Comments', 'user'=>$gCurrentUser));
}

add_action( 'trash_comment', 'click5_trash_comment', 10, 2 );
function click5_trash_comment($comment_id, $comment) { 
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $wpdb->insert($table_name, array('description'=>"Comment by" . " <b>" . $comment->comment_author . "</b> has been moved to the Trash",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Comments', 'user'=>$gCurrentUser));
}

add_action( 'delete_comment', 'click5_delete_comment', 10, 2 );
function click5_delete_comment($comment_id, $comment) { 
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $wpdb->insert($table_name, array('description'=>"Comment by" . " <b>" . $comment->comment_author . "</b> has been deleted pemanently",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Comments', 'user'=>$gCurrentUser));
}

add_action( 'add_attachment', 'click5_add_media_post' );
function click5_add_media_post($postid) { 
  global $gCurrentUser;
  global $wpdb;
  $post_data = get_post($postid);
  $file_type = explode("/", $post_data->post_mime_type);
  if(!empty($file_type[1]))
    $file_type = $file_type[1];
  else
    $file_type = "";

  if($file_type == "msword" || str_contains($file_type,"officedocument.wordprocessingml.document")) {
    $file_type = "doc";
  }
  if($file_type == "vnd.ms-excel" || str_contains($file_type,"officedocument.spreadsheetml.sheet")) {
    $file_type = "xls";
  }
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_module_media")) === "1")
    $wpdb->insert($table_name, array('description'=>"Media file" . " <b>" . $post_data->post_title . "." . $file_type . "</b> has been uploaded",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Media', 'user'=>$gCurrentUser));
}

add_action( 'edit_attachment', 'click5_edit_media_post' );
function click5_edit_media_post($postid) { 
  global $gCurrentUser;
  global $wpdb;
  $post_data = get_post($postid);
  $file_type = explode("/", $post_data->post_mime_type)[1];
  if($file_type == "msword") {
    $file_type = "doc";
  }
  if($file_type == "vnd.ms-excel") {
    $file_type = "xls";
  }
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_module_media")) === "1")
    $wpdb->insert($table_name, array('description'=>"Media file" . " <b>" . $post_data->post_title ."</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Media', 'user'=>$gCurrentUser));
}

if(esc_attr(get_option('click5_history_log_ninja-forms/ninja-forms.php')) == "1")
{
    add_filter('wp_ajax_nf_save_form','click5_nf_save_form',1,2);
    function click5_nf_save_form(  )
    {
      global $wpdb;
      global $gCurrentUser;
      $table_name = $wpdb->prefix . "c5_history";
      $nforms = json_decode(sanitize_text_field(str_replace("\\","",$_REQUEST['form'])));
      $title = $nforms->settings->title;
      if(str_contains( $nforms->id, 'tmp' ))
        $wpdb->insert($table_name, array('description'=>"Form <b>" . $title . "</b> has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Ninja Forms', 'user'=>$gCurrentUser));
      else
        $wpdb->insert($table_name, array('description'=>"Form <b>" . $title . "</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Ninja Forms', 'user'=>$gCurrentUser));
    }

    {
      add_filter('ninja_forms_after_form_delete','click5_nf_delete_form',1,2);
      function click5_nf_delete_form(  )
      {
        global $wpdb;
        global $gCurrentUser;
        $table_name = $wpdb->prefix . "c5_history";
        $nforms = Ninja_Forms()->form(intval($_REQUEST['form_id']))->get();
        $title = $nforms->get_settings()['title'];
        $wpdb->insert($table_name, array('description'=>"Form <b>" . $title . "</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Ninja Forms', 'user'=>$gCurrentUser));
      }

      add_filter('ninja_forms_excluded_duplicate_form_settings','click5_nf_nf_duplicate',1,2);
      function click5_nf_nf_duplicate( $blacklist )
      {
        global $wpdb;
        global $gCurrentUser;
        $table_name = $wpdb->prefix . "c5_history";
        $nforms = Ninja_Forms()->form(intval($_REQUEST['clone_id']))->get();
        $title = $nforms->get_settings()['title'];
        $wpdb->insert($table_name, array('description'=>"Form <b>" . $title . "</b> has been duplicated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Ninja Forms', 'user'=>$gCurrentUser));
        return $blacklist;
      }
    }

  add_action( 'init', 'click5_nf_nf_add_new', 10,1 );
  function click5_nf_nf_add_new(  ){
    //setcookie("nf_batch_process",false);
    if(isset($_REQUEST['action'])){
      if($_REQUEST['action'] == 'nf_batch_process')
      setcookie("nf_batch_process",true);
    }

      if(isset($_COOKIE['nf_batch_process'])){
        if($_COOKIE['nf_batch_process'] == true)
        {
        if(isset($_REQUEST['form_id']) && isset($_REQUEST['page']))
        {
          if($_REQUEST['form_id'] != "new" && $_REQUEST['page'] == "ninja-forms")
          {
            //unset($_COOKIE['nf_batch_process']);
            setcookie("nf_batch_process",false);
            global $wpdb;
            global $gCurrentUser;
            $table_name = $wpdb->prefix . "c5_history";
            $nforms = Ninja_Forms()->form(intval($_REQUEST['form_id']))->get();
            $title = $nforms->get_settings()['title'];
            if(!isset($title) || $title == "")
              $title = "Blank";
            $wpdb->insert($table_name, array('description'=>"Form <b>" . $title . "</b> has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Ninja Forms', 'user'=>$gCurrentUser));
          }
        }
      }
      } 
  }
}

add_action('bsr_ajax_process_search_replace','click5_bsr_search_process',10);

function click5_bsr_search_process(){
  if((esc_attr(get_option('click5_history_log_better-search-replace/better-search-replace.php')) == "1" && is_plugin_active('better-search-replace/better-search-replace.php')) || (esc_attr(get_option('click5_history_log_better-search-replace-pro/better-search-replace.php')) == "1" && is_plugin_active('better-search-replace-pro/better-search-replace.php'))){
    global $gCurrentUser;

    global $gCurrentUser;
  if(is_null($gCurrentUser))
    $gCurrentUser = wp_get_current_user()->data->user_login;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    $query_data = parse_url("?".$_REQUEST['bsr_data']);
    $params = "";
    parse_str($query_data['query'],$params);
    $tables = "";

    if(is_array($params))
    {
      if(is_array($params['select_tables'])){
        foreach($params['select_tables'] as $table){
          $tables .=",".$table;
        }

        $tables = substr($tables,1);
      }
    }
    $pro = "";
    if(is_plugin_active('better-search-replace-pro/better-search-replace.php'))
      $pro = "Pro";


    if($params['dry_run'] == "on"){
      if(!is_null($params['completed_pages']) && !is_null($params['total_pages']))
      {
        if($params['completed_pages'] == $params['total_pages'])
          $wpdb->insert($table_name, array('description'=>"Record <b>\"".esc_html($params['search_for'])."\"</b> has been searched in ".$tables,'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Better Search Replace ".$pro, 'user'=>$gCurrentUser));
      }
      
    }else{
      if(!is_null($params['completed_pages']) && !is_null($params['total_pages']))
      {
        if($params['completed_pages'] == $params['total_pages']){
          $bsr_updates = get_transient( 'bsr_results' );
          if($bsr_updates['updates'] > 0)
            $wpdb->insert($table_name, array('description'=>"Record <b>\"".esc_html($params['search_for'])."\"</b> has been replaced with <b>\"".esc_html($params['replace_with'])."\"</b> in ".$tables,'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Better Search Replace ".$pro, 'user'=>$gCurrentUser));
        }
          
      }
    }
  }
}
add_action('bsr_ajax_process_backup','click5_bsr_backup_database',10);
function click5_bsr_backup_database(){
  if(esc_attr(get_option('click5_history_log_better-search-replace-pro/better-search-replace.php')) == "1"){
    global $gCurrentUser;
    global $sitemapCurrentUser;
    $usr = $gCurrentUser;
    if(is_null($gCurrentUser) && !is_null($sitemapCurrentUser->data->user_login))
        $usr = $sitemapCurrentUser->data->user_login;
    else if(is_null($gCurrentUser) && !is_null(wp_get_current_user()->data->user_login))
        $usr = wp_get_current_user()->data->user_login;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    if(isset($_REQUEST['bsr_step'])){
      if($_REQUEST['bsr_step'] == "1"){
        $wpdb->insert($table_name, array('description'=>"<b>Database backup</b> has started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Better Search Replace Pro", 'user'=>$usr));
      }
    }
  }
}

add_action('bsr_ajax_process_import','click5_bsr_import_database',10);
function click5_bsr_import_database(){
  if(esc_attr(get_option('click5_history_log_better-search-replace-pro/better-search-replace.php')) == "1"){
    global $gCurrentUser;
    global $sitemapCurrentUser;
    $usr = $gCurrentUser;
    if(is_null($gCurrentUser) && !is_null($sitemapCurrentUser->data->user_login))
      $usr = $sitemapCurrentUser->data->user_login;
    else if(is_null($gCurrentUser) && !is_null(wp_get_current_user()->data->user_login))
      $usr = wp_get_current_user()->data->user_login;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    if(isset($_REQUEST['bsr_step']) && isset($_REQUEST['bsr_page'])){
      if($_REQUEST['bsr_step'] == "0" && $_REQUEST['bsr_page'] == "0"){
        $wpdb->insert($table_name, array('description'=>"<b>Database import</b> has started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Better Search Replace Pro", 'user'=>$usr));
      }
    }


  }
}

add_action('admin_post_bsr_download_backup','click5_bsr_backup_end',10);
function click5_bsr_backup_end(){
  if(esc_attr(get_option('click5_history_log_better-search-replace-pro/better-search-replace.php')) == "1"){
    global $gCurrentUser;
    global $sitemapCurrentUser;
    $usr = $gCurrentUser;
    if(is_null($gCurrentUser) && !is_null($sitemapCurrentUser->data->user_login))
      $usr = $sitemapCurrentUser->data->user_login;
    else if(is_null($gCurrentUser) && !is_null(wp_get_current_user()->data->user_login))
      $usr = wp_get_current_user()->data->user_login;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $wpdb->insert($table_name, array('description'=>"<b>Database backup</b> has ended",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Better Search Replace Pro", 'user'=>$usr));
  }
}

/*add_action("wp_ajax_sbi_save_settings","click5_sbi_instagram_save_settings");

function click5_sbi_instagram_save_settings(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(esc_attr(get_option("click5_history_log_instagram-feed/instagram-feed.php")) == "1")
      $wpdb->insert($table_name, array('description'=>"<b>Settings</b> has been saved",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));   
}*/

//add_action("sbi_api_connect_response","click5_sbi_api_connect_response",10,2);
add_action("wp_ajax_sbi_feed_saver_manager_delete_source","click5_sbi_delete_source",10,2);
function click5_sbi_delete_source(){

}

add_filter("should_do_source_updates","click5_sbi_new_source",10,1);
global $newAccountConnect;
$newAccountConnect = true;
function click5_sbi_new_source(){
  if(!empty($_REQUEST) && (isset($_REQUEST['sbi_access_token']) && isset($_REQUEST['sbi_id']) && isset($_REQUEST['sbi_account_type'])) ){
    global $gCurrentUser;
    global $newAccountConnect;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if($newAccountConnect){
      if(esc_attr(get_option("click5_history_log_instagram-feed/instagram-feed.php")) == "1")
      $wpdb->insert($table_name, array('description'=>"<b>Instagram account ".$_REQUEST['sbi_username']."</b> has been connected",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
    }
    $newAccountConnect = false;
  }
}

add_action( 'wp_ajax_sbi_feed_saver_manager_delete_source', 'click5_sbi_instagram_delete_source');
function click5_sbi_instagram_delete_source(){
  global $wpdb;
  global $gCurrentUser;
  global $newAccountConnect;
  $table_name = $wpdb->prefix . "c5_history";
  if(!empty($_REQUEST) && isset($_REQUEST['source_id'])){
    $source_id = sanitize_text_field($_REQUEST['source_id']);
    $prep = $wpdb->prepare("SELECT username FROM ".$wpdb->prefix."sbi_sources WHERE id = %s",$source_id);
    $username = $wpdb->get_results($prep);
    if(!empty($username)){
      $username = $username[0]->username;
    }else
      $username = "";

    if(esc_attr(get_option("click5_history_log_instagram-feed/instagram-feed.php")) == "1")
      $wpdb->insert($table_name, array('description'=>"<b>Instagram account ".$username."</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
  }
}



add_action('transition_post_status', 'click5_transition_post_status',  10, 99);
function click5_transition_post_status( $new, $old, $post ) {
  $not_allowed_statuses = array( 
    'nav_menu_item',
    'attachment',
    'revision',
    'post',
    'page',
    'custom_css',
    'wp_block',
    'wp_template',
    'customize_changeset',
    'acf-field',
    'acf-field-group',
    'user_request',
    'oembed_cache',
    'wpcf7_contact_form',
    'acf-field-group'
  );

  if(!in_array( $post->post_type, $not_allowed_statuses, true )) {
    $type_post = ucfirst($post->post_type);
    global $wp_post_types;
    if($type_post == 'Faq') {
      $type_post = 'FAQ';
    }
    if($new == 'draft' && $old == 'draft') {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>$wp_post_types[$post->post_type]->labels->singular_name . " <b>" . $post->post_title . "</b>  has been saved as a draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$wp_post_types[$post->post_type]->label, 'user'=>$gCurrentUser));     
    } elseif ($new == 'publish' && $old == 'draft') {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>$wp_post_types[$post->post_type]->labels->singular_name . " <b>" . $post->post_title . "</b>  has been published",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$wp_post_types[$post->post_type]->label, 'user'=>$gCurrentUser));     
    } elseif ($new == 'publish' && $old == 'auto-draft') {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>$wp_post_types[$post->post_type]->labels->singular_name . " <b>" . $post->post_title . "</b>  has been published",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$wp_post_types[$post->post_type]->label, 'user'=>$gCurrentUser));     
    } elseif ($new == 'trash') {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $otp = "";
      if(str_contains(strtolower($wp_post_types[$post->post_type]->labels->singular_name), "forms" ) || str_contains(strtolower($wp_post_types[$post->post_type]->labels->singular_name), "form" ))
        $otp = "Contact Form";
      else
        $otp = $wp_post_types[$post->post_type]->labels->singular_name;
      $wpdb->insert($table_name, array('description'=>$otp . " <b>" . $post->post_title . "</b>  has been moved to trash",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$wp_post_types[$post->post_type]->label, 'user'=>$gCurrentUser));     
    } elseif ($new != 'trash' && $old == 'trash') {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>$wp_post_types[$post->post_type]->labels->singular_name . " <b>" . $post->post_title . "</b>  has been restored from trash",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$wp_post_types[$post->post_type]->label, 'user'=>$gCurrentUser));     
    } elseif ($new == 'pending' && $old == 'draft') { 
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>$wp_post_types[$post->post_type]->labels->singular_name . " <b>" . $post->post_title . "</b>  status has been changed to pending",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$wp_post_types[$post->post_type]->label, 'user'=>$gCurrentUser));     
    } elseif ($new == 'pending' && $old == 'publish') { 
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>$wp_post_types[$post->post_type]->labels->singular_name . " <b>" . $post->post_title . "</b>  status has been changed to pending",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$wp_post_types[$post->post_type]->label, 'user'=>$gCurrentUser));     
    } elseif ($new == 'draft' && $old == 'pending') { 
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>$wp_post_types[$post->post_type]->labels->singular_name . " <b>" . $post->post_title . "</b> status has been changed to draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$wp_post_types[$post->post_type]->label, 'user'=>$gCurrentUser));     
    } elseif ($new != 'auto-draft' && $new != 'new' && $new != 'inherit' && $old != 'auto-draft' && $post->post_type != "wpforms") {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if(!isset($_REQUEST['pll_action']))
      if($post->post_type === 'acf-taxonomy' || $post->post_type === 'acf-post-type'){
        if(get_transient($post->post_type."-".$post->ID)){
          delete_transient($post->post_type."-".$post->ID);
          $wpdb->insert($table_name, array('description'=>$wp_post_types[$post->post_type]->labels->singular_name . " <b>" . $post->post_title . "</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$wp_post_types[$post->post_type]->label, 'user'=>$gCurrentUser));
        }else{
          set_transient($post->post_type."-".$post->ID,1,6);
        }
      }else{
          $wpdb->insert($table_name, array('description'=>$wp_post_types[$post->post_type]->labels->singular_name . " <b>" . $post->post_title . "</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$wp_post_types[$post->post_type]->label, 'user'=>$gCurrentUser));
      }     
    }
  }
  
  if($new == 'pending' && $old == 'draft') {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if($post->post_type == "post") { 
      if(esc_attr(get_option("click5_history_log_module_posts")) === "1")
        $wpdb->insert($table_name, array('description'=>"Post <b>" . $post->post_title . "</b> status has been changed to pending",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
    }
    if($post->post_type == "page") { 
      if(esc_attr(get_option("click5_history_log_module_pages")) === "1")
        $wpdb->insert($table_name, array('description'=>"Page <b>" . $post->post_title . "</b> status has been changed to pending",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
    }
    if($post->post_type == "acf-field-group" && (esc_attr(get_option('click5_history_log_advanced-custom-fields/acf.php')) == "1" || esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")) { 
      if(esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")
        $plugin = "Advanced Custom Fields Pro";
      else
        $plugin = "Advanced Custom Fields";
      $wpdb->insert($table_name, array('description'=>"Advanced Custom Field <b>" . $post->post_title . "</b> status has been changed to pending",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin, 'user'=>$gCurrentUser));
    }
   }

   if($new == 'pending' && $old == 'publish') {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if($post->post_type == "post") { 
      if(esc_attr(get_option("click5_history_log_module_posts")) === "1")
        $wpdb->insert($table_name, array('description'=>"Post <b>" . $post->post_title . "</b> status has been changed to pending",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
    }

    if($post->post_type == "page") { 
      if(esc_attr(get_option("click5_history_log_module_pages")) === "1")
        $wpdb->insert($table_name, array('description'=>"Page <b>" . $post->post_title . "</b> status has been changed to pending",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
    }
    if($post->post_type == "acf-field-group" && (esc_attr(get_option('click5_history_log_advanced-custom-fields/acf.php')) == "1" || esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")) { 
      if(esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")
        $plugin = "Advanced Custom Fields Pro";
      else
        $plugin = "Advanced Custom Fields";
      $wpdb->insert($table_name, array('description'=>"Advanced Custom Field <b>" . $post->post_title . "</b> status has been changed to pending",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin, 'user'=>$gCurrentUser));
    }
   }
   if($new == 'publish' && $old == 'publish') {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if($post->post_type == "wpcf7_contact_form" && esc_attr(get_option('click5_history_log_contact-form-7/wp-contact-form-7.php')) == "1") { 
      $wpdb->insert($table_name, array('description'=>"Contact Form <b>" . $post->post_title . "</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Contact Form 7', 'user'=>$gCurrentUser));
    }   

    if($post->post_type == "wpforms" && ((esc_attr(get_option('click5_history_log_wpforms-lite/wpforms.php')) == "1" && is_plugin_active('wpforms-lite/wpforms.php')) || (esc_attr(get_option('click5_history_log_wpforms/wpforms.php')) == "1" && is_plugin_active('wpforms/wpforms.php')))) { 
      $pro = "";
      if(is_plugin_active('wpforms/wpforms.php') == "1")
        $pro = "Pro";
      if(!isset($_GET['action']))
      $wpdb->insert($table_name, array('description'=>"Contact Form <b>" . $post->post_title . "</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WPForms '.$pro, 'user'=>$gCurrentUser));
      
      /*add_action( 'wpforms_builder_save_form', function($form_id, $data){
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        $wpdb->insert($table_name, array('description'=>"Contact Form <b>" . $data['settings']['form_title'] . "</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WPForms', 'user'=>$gCurrentUser));
      }, 10, 2 );*/
    
    
    } 

    if($post->post_type == "acf-field-group") { 
      global $gBlockDouble;
      $mod_time = new DateTime($post->post_modified);
      $create_time =  new DateTime($post->post_date);
      $diff_time = $mod_time->getTimestamp() - $create_time->getTimestamp();
      if($diff_time < 4) { 
        if((esc_attr(get_option('click5_history_log_advanced-custom-fields/acf.php')) == "1" || esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")) {
          if(esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")
            $plugin = "Advanced Custom Fields Pro";
          else
            $plugin = "Advanced Custom Fields";
          $wpdb->insert($table_name, array('description'=>"Advanced Custom Field <b>" . $post->post_title . "</b> has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin, 'user'=>$gCurrentUser));
        }
      } else {
        if(!$gBlockDouble) {
          if((esc_attr(get_option('click5_history_log_advanced-custom-fields/acf.php')) == "1" || esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")) {
            if(esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")
              $plugin = "Advanced Custom Fields Pro";
            else
              $plugin = "Advanced Custom Fields"; 
            $wpdb->insert($table_name, array('description'=>"Advanced Custom Field <b>" . $post->post_title . "</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin, 'user'=>$gCurrentUser));
          }
          $gBlockDouble = true;
        } else {
          $gBlockDouble = false;
        }   
      } 
    }  
  }  

  /*if($new == 'inherit'){
    add_action('wpforms_save_form',function($_form_id, $form){
      global $wpdb;
      global $gCurrentUser;
      $table_name = $wpdb->prefix . "c5_history";
      if(esc_attr(get_option('click5_history_log_wpforms-lite/wpforms.php')) == "1") { 
        $wpdb->insert($table_name, array('description'=>"Contact Form <b>" . $form['post_title'] . "</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WPForms', 'user'=>$gCurrentUser));
      } 
    },10,2);
  }*/

  if($new == 'publish' && $old == 'new') {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if($post->post_type == "wpcf7_contact_form" && esc_attr(get_option('click5_history_log_contact-form-7/wp-contact-form-7.php')) == "1") { 
      $wpdb->insert($table_name, array('description'=>"Contact Form <b>" . $post->post_title . "</b> has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Contact Form 7', 'user'=>$gCurrentUser));
    } 

    if($post->post_type == "wpforms" && ((esc_attr(get_option('click5_history_log_wpforms-lite/wpforms.php')) == "1" && is_plugin_active('wpforms-lite/wpforms.php')) || (esc_attr(get_option('click5_history_log_wpforms/wpforms.php')) == "1" && is_plugin_active('wpforms/wpforms.php')))) { 
      $pro = "";
      if(is_plugin_active('wpforms/wpforms.php'))
        $pro = "Pro";
      if(isset($_GET['action']))
      {
        if($_GET['action'] == 'duplicate')
          $wpdb->insert($table_name, array('description'=>"Contact Form <b>" . $post->post_title . "</b> has been duplicated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WPForms '.$pro, 'user'=>$gCurrentUser));
      }else{
        $wpdb->insert($table_name, array('description'=>"Contact Form <b>" . $post->post_title . "</b> has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WPForms '.$pro, 'user'=>$gCurrentUser));
      }
      
    } 

    if($post->post_type == "acf-field-group" && (esc_attr(get_option('click5_history_log_advanced-custom-fields/acf.php')) == "1" || esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")) { 
      if(esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")
        $plugin = "Advanced Custom Fields Pro";

      else
        $plugin = "Advanced Custom Fields";
      $wpdb->insert($table_name, array('description'=>"Advanced Custom Field <b>" . $post->post_title . "</b> has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin, 'user'=>$gCurrentUser));
    } 
  }  

   if($new == 'draft' && $old == 'pending') {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if($post->post_type == "post") { 
      if(esc_attr(get_option("click5_history_log_module_posts")) === "1")
        $wpdb->insert($table_name, array('description'=>"Post <b>" . $post->post_title . "</b> status has been changed to draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
    }
    if($post->post_type == "page") { 
      if(esc_attr(get_option("click5_history_log_module_pages")) === "1")
        $wpdb->insert($table_name, array('description'=>"Page <b>" . $post->post_title . "</b> status has been changed to draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
    }
    if($post->post_type == "acf-field-group" && (esc_attr(get_option('click5_history_log_advanced-custom-fields/acf.php')) == "1" || esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")) { 
      if(esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")
        $plugin = "Advanced Custom Fields Pro";
      else
        $plugin = "Advanced Custom Fields";
      $wpdb->insert($table_name, array('description'=>"Advanced Custom Field <b>" . $post->post_title . "</b> status has been changed to draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin, 'user'=>$gCurrentUser));
    }
   }

   if($new == 'draft' && $old == 'publish') {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if($post->post_type == "post") { 
      if(esc_attr(get_option("click5_history_log_module_posts")) === "1")
        $wpdb->insert($table_name, array('description'=>"Post <b>" . $post->post_title . "</b> status has been changed to draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
    }
    if($post->post_type == "page") { 
      if(esc_attr(get_option("click5_history_log_module_pages")) === "1")
        $wpdb->insert($table_name, array('description'=>"Page <b>" . $post->post_title . "</b> status has been changed to draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
    }
    if($post->post_type == "acf-field-group" && (esc_attr(get_option('click5_history_log_advanced-custom-fields/acf.php')) == "1" || esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")) { 
      if(esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")
        $plugin = "Advanced Custom Fields Pro";
      else
        $plugin = "Advanced Custom Fields";
      $wpdb->insert($table_name, array('description'=>"Advanced Custom Field <b>" . $post->post_title . "</b> status has been changed to draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin, 'user'=>$gCurrentUser));
    }
   }

   if($new == 'publish' && $old == 'draft') {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history"; 
    if($post->post_type == "acf-field-group" && (esc_attr(get_option('click5_history_log_advanced-custom-fields/acf.php')) == "1" || esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")) { 
      if(esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")
        $plugin = "Advanced Custom Fields Pro";
      else
        $plugin = "Advanced Custom Fields";
      $wpdb->insert($table_name, array('description'=>"Advanced Custom Field <b>" . $post->post_title . "</b> has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin, 'user'=>$gCurrentUser));
    } 
  }
   
  if($new == 'draft' && $old == 'draft') {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $post_data = get_post($post->ID);
    if($post->post_type == "post") { 
      if(esc_attr(get_option("click5_history_log_module_posts")) === "1"){
        if($post->post_date == $post->post_modified) {
          $wpdb->insert($table_name, array('description'=>"Post <b>" . $post->post_title . "</b>  has been saved as a draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
        } else {
          $wpdb->insert($table_name, array('description'=>"Post <b>" . $post->post_title . "</b>  has been updated like a draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
        }
      }
    }
    if($post->post_type == "page") { 
      if(esc_attr(get_option("click5_history_log_module_pages")) === "1"){
        if($post->post_date == $post->post_modified) {
          $wpdb->insert($table_name, array('description'=>"Page <b>" . $post->post_title . "</b>  has been saved as a draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
        } else {
          $wpdb->insert($table_name, array('description'=>"Page <b>" . $post->post_title . "</b>  has been updated like a draft",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
        }
      }
    }
  } 
  if($new == 'future') {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $date_format = esc_attr(get_option('date_format'));
    $time_format = esc_attr(get_option('time_format'));
    $date_time_format = $date_format . " " . $time_format;
    if($post->post_type == "post") { 
      if(esc_attr(get_option("click5_history_log_module_posts")) === "1")
        $wpdb->insert($table_name, array('description'=>"Post <b>" . $post->post_title . "</b> has beeen saved as a scheduled ",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
    }
    if($post->post_type == "page") { 
      if(esc_attr(get_option("click5_history_log_module_pages")) === "1")
        $wpdb->insert($table_name, array('description'=>"Page <b>" . $post->post_title . "</b> has beeen saved as a scheduled ",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
    }
   }
}

add_action( 'untrash_post', 'click5_untrash_post' );
function click5_untrash_post($postid) {
  global $gCurrentUser;
  global $wpdb;
  $post_data = get_post($postid);
  $table_name = $wpdb->prefix . "c5_history";
  if($post_data->post_type == "post") {
    if(esc_attr(get_option("click5_history_log_module_posts")) === "1")
      $wpdb->insert($table_name, array('description'=>"Post <b>" . $post_data->post_title . "</b>  has been restored from trash",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
  }
  if($post_data->post_type == "page") {
    if(esc_attr(get_option("click5_history_log_module_pages")) === "1")
      $wpdb->insert($table_name, array('description'=>"Page <b>" . $post_data->post_title . "</b>  has been restored from trash",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
  }
  if($post_data->post_type == "acf-field-group" && (esc_attr(get_option('click5_history_log_advanced-custom-fields/acf.php')) == "1" || esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")) {
    if(esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")
      $plugin = "Advanced Custom Fields Pro";
    else
      $plugin = "Advanced Custom Fields";
    $wpdb->insert($table_name, array('description'=>"Advanced Custom Field <b>" . $post_data->post_title . "</b>  has been restored from trash",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin, 'user'=>$gCurrentUser));
  }
}

add_action( 'wp_trash_post', 'click5_trash_post' );
function click5_trash_post($postid) {
  global $gCurrentUser;
  global $wpdb;
  $post_data = get_post($postid);
  $table_name = $wpdb->prefix . "c5_history";
  if($post_data->post_type == "post") {
    if(esc_attr(get_option("click5_history_log_module_posts")) === "1")
      $wpdb->insert($table_name, array('description'=>"Post <b>" . $post_data->post_title . "</b>  has been moved to trash",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
  }
  if($post_data->post_type == "page") {
    if(esc_attr(get_option("click5_history_log_module_pages")) === "1")
      $wpdb->insert($table_name, array('description'=>"Page <b>" . $post_data->post_title . "</b>  has been moved to trash",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
  }
  if($post_data->post_type == "acf-field-group" && (esc_attr(get_option('click5_history_log_advanced-custom-fields/acf.php')) == "1" || esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")) {
    if(esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")
      $plugin = "Advanced Custom Fields Pro";
    else
      $plugin = "Advanced Custom Fields";
    $wpdb->insert($table_name, array('description'=>"Advanced Custom Field <b>" . $post_data->post_title . "</b>  has been moved to trash",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin, 'user'=>$gCurrentUser));
  }
}

add_action( 'delete_post', 'click5_delete_post' );
function click5_delete_post($postid) {
  global $gCurrentUser;
  global $wpdb;
  $post_data = get_post($postid);
  $file_type = explode("/", $post_data->post_mime_type);
  if(!empty($file_type[1]))
    $file_type = $file_type[1];
  else
    $file_type = "";

  if($file_type == "msword" || str_contains($file_type,"officedocument.wordprocessingml.document")) {
    $file_type = "doc";
  }
  if($file_type == "vnd.ms-excel" || str_contains($file_type,"officedocument.spreadsheetml.sheet")) {
    $file_type = "xls";
  }

  $table_name = $wpdb->prefix . "c5_history";
  if($post_data->post_type == 'attachment') {
    if(esc_attr(get_option("click5_history_log_module_media")) === "1")
      $wpdb->insert($table_name, array('description'=>"Media file" . " <b>" . $post_data->post_title . "." . $file_type . "</b>  has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Media', 'user'=>$gCurrentUser));
  }
  $not_allowed_statuses = array( 
    'nav_menu_item',
    'attachment',
    'revision',
    'post',
    'page',
    'custom_css',
    'wp_block',
    'wp_template',
    'customize_changeset',
    'acf-field',
    'acf-field-group',
    'user_request',
    'oembed_cache',
    'wpcf7_contact_form',
    'acf-field-group'
  );
  if(!in_array( $post_data->post_type, $not_allowed_statuses, true ) && $post_data->post_type != "wpforms") {
    global $wp_post_types;
    $type_post = ucfirst($post_data->post_type);
    if($type_post == 'Faq') {
      $type_post = 'FAQ';
    }
    $wpdb->insert($table_name, array('description'=>$wp_post_types[$post_data->post_type]->labels->singular_name . " <b>" . $post_data->post_title . "</b>  has been deleted pemanently",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$wp_post_types[$post_data->post_type]->label, 'user'=>$gCurrentUser));
  }
  if($post_data->post_type == "post") {
    if(esc_attr(get_option("click5_history_log_module_posts")) === "1")
      $wpdb->insert($table_name, array('description'=>"Post <b>" . $post_data->post_title . "</b>  has been deleted pemanently",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>$gCurrentUser));
  }
  if($post_data->post_type == "page") {
    if(esc_attr(get_option("click5_history_log_module_pages")) === "1")
      $wpdb->insert($table_name, array('description'=>"Page <b>" . $post_data->post_title . "</b>  has been deleted pemanently",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>$gCurrentUser));
  }
  if($post_data->post_type == "wpcf7_contact_form" && esc_attr(get_option('click5_history_log_contact-form-7/wp-contact-form-7.php')) == "1") {
    $wpdb->insert($table_name, array('description'=>"Contact Form <b>" . $post_data->post_title . "</b>  has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Contact Form 7', 'user'=>$gCurrentUser));
  }

  if($post_data->post_type == "wpforms" && ((esc_attr(get_option('click5_history_log_wpforms-lite/wpforms.php')) == "1" && is_plugin_active('wpforms-lite/wpforms.php')) || (esc_attr(get_option('click5_history_log_wpforms/wpforms.php')) == "1" && is_plugin_active('wpforms/wpforms.php')))) { 
    $pro = "";
    if(is_plugin_active('wpforms/wpforms.php'))
      $pro = "Pro";
    $wpdb->insert($table_name, array('description'=>"Contact Form <b>" . $post_data->post_title . "</b>  has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WPForms '.$pro, 'user'=>$gCurrentUser));
  }

  if($post_data->post_type == "acf-field-group" && (esc_attr(get_option('click5_history_log_advanced-custom-fields/acf.php')) == "1" || esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")) {
    if(esc_attr(get_option('click5_history_log_advanced-custom-fields-pro/acf.php')) == "1")
      $plugin = "Advanced Custom Fields Pro";
    else
      $plugin = "Advanced Custom Fields";
    $wpdb->insert($table_name, array('description'=>"Advanced Custom Field <b>" . $post_data->post_title . "</b> has been deleted pemanently",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin, 'user'=>$gCurrentUser));
  }
}

add_action( 'delete_user', 'click5_delete_user' );
function click5_delete_user( $user_id ) {
    $user_data = get_userdata( $user_id );
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $currentRoleName = click5_get_current_user_role($user_id);
    if(esc_attr(get_option("click5_history_log_module_users")) === "1")
      $wpdb->insert($table_name, array('description'=>"User <b>" . $user_data->data->display_name . "</b> ($currentRoleName)  has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Users', 'user'=>wp_get_current_user()->user_login));
}

add_action( 'set_user_role', function( $user_id, $role, $old_roles ) 
{
  global $wp_roles;
  $user_data = get_userdata( $user_id );
  global $wpdb;
  $role_displayName = array_values((array)$wp_roles->roles[$role]['name'])[0];
  $table_name = $wpdb->prefix . "c5_history";
  $currentRoleName = click5_get_current_user_role($user_id);
  if(esc_attr(get_option("click5_history_log_user-role-editor/user-role-editor.php")) === "1" && is_plugin_active("user-role-editor/user-role-editor.php")){
    $wpdb->insert($table_name, array('description'=>"User <b>" . $user_data->data->display_name . "</b> ($currentRoleName) role has been changed to <b>" .  ucfirst($role_displayName) . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>wp_get_current_user()->user_login));
  }else{
    if(esc_attr(get_option("click5_history_log_module_users")) === "1")
      $wpdb->insert($table_name, array('description'=>"User <b>" . $user_data->data->display_name . "</b> ($currentRoleName) role has been changed to <b>" .  ucfirst($role_displayName) . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Users', 'user'=>wp_get_current_user()->user_login));
  }

}, 10, 3 );

function click5_update__profile_fields($user_id) {
  $user_data = get_userdata( $user_id );
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $currentRoleName = click5_get_current_user_role($user_id);
  if(esc_attr(get_option("click5_history_log_module_users")) === "1")
    $wpdb->insert($table_name, array('description'=>"User <b>" . $user_data->data->display_name . "</b> ($currentRoleName)  has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Users', 'user'=>wp_get_current_user()->user_login));
}
add_action('edit_user_profile_update', 'click5_update__profile_fields');

add_action('create_category', 'click5_add_new_category', 10, 2);
function click5_add_new_category($category_id, $category_term_id){
  global $gCurrentUser;
  global $wpdb;
  $new_cat = get_category($category_id);
  $table_name = $wpdb->prefix . "c5_history";
  if(!isset($_REQUEST['pll_action']))
    $wpdb->insert($table_name, array('description'=>"<b>" . $new_cat->name . "</b>  category has been created",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts / Categories', 'user'=>$gCurrentUser));
}

add_action('delete_category', 'click5_delete_category_fn',  10, 4 );
function click5_delete_category_fn($tt_id, $id, $data, $data2){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(!isset($_REQUEST['pll_action']))
    $wpdb->insert($table_name, array('description'=>"<b>" . $data->name . "</b>  category has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts / Categories', 'user'=>$gCurrentUser));
}

add_action ( 'edited_category', 'click5_edited_category_fn');
function click5_edited_category_fn( $category_id )
{
  global $gCurrentUser;
  global $wpdb;
  $new_cat = get_category($category_id);
  $table_name = $wpdb->prefix . "c5_history";
  if(!isset($_REQUEST['pll_action']))
    $wpdb->insert($table_name, array('description'=>"<b>" . $new_cat->name . "</b>  category has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts / Categories', 'user'=>$gCurrentUser));
}

add_action('create_post_tag', 'click5_add_new_tag', 10, 2);

function click5_add_new_tag($tag_id, $tag_term_id){
  global $gCurrentUser;
  global $wpdb;
  $new_tag = get_tag($tag_id);
  $table_name = $wpdb->prefix . "c5_history";
  $wpdb->insert($table_name, array('description'=>"<b>" . $new_tag->name . "</b> tag has been created",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts / Tags', 'user'=>$gCurrentUser));
}

add_action('delete_post_tag', 'click5_delete_tag_fn',  10, 4 );
function click5_delete_tag_fn($tt_id, $id, $data, $data2){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $wpdb->insert($table_name, array('description'=>"<b>" . $data->name . "</b> tag has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts / Tags', 'user'=>$gCurrentUser));
}

add_action ( 'edit_post_tag', 'click5_edited_tag_fn');
function click5_edited_tag_fn( $tag_id )
{
  global $gCurrentUser;
  global $wpdb;
  $edit_tag = get_tag($tag_id);
  $table_name = $wpdb->prefix . "c5_history";
  $wpdb->insert($table_name, array('description'=>"<b>" . $edit_tag->name . "</b>  tag has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts / Tags', 'user'=>$gCurrentUser));
}

add_action('edit_term', 'click5_edit_term', 10, 3);
function click5_edit_term($term_id, $term_term_id, $term_data){
  if($term_data != "category" && $term_data != "post_tag" && !isset($_REQUEST['pll_action'])) {
    global $gCurrentUser;
    global $wpdb;
    global $wp_post_types;
    $term_string = explode("-", $term_data);
    $new_term = get_term($term_id, $term_data);
    $table_name = $wpdb->prefix . "c5_history";
    if(property_exists($new_term, 'taxonomy')) {   
      $taxonomy_data = get_taxonomy($new_term->taxonomy);
      $plugin_name = 'Taxonomies / ' . $taxonomy_data->label;
    } else {
      $plugin_name = ucfirst($term_string[0]) . '/ Categories';
    }

    if($new_term->taxonomy != "nav_menu" && strpos($new_term->name,"pll_") === false) { 
      if(property_exists($new_term, 'taxonomy')) {
        $wpdb->insert($table_name, array('description'=>"<b>" . $new_term->name . "</b> taxonomy has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
      }
      else
      {
        $wpdb->insert($table_name, array('description'=>"<b>" . $new_term->name . "</b> category has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
      }
    }
  }
}

add_action('create_term', 'click5_add_new_term', 10, 3);
function click5_add_new_term($term_id, $term_term_id, $term_data){
  if($term_data != "category" && $term_data != "post_tag" && !isset($_REQUEST['pll_action'])) {
    global $gCurrentUser;
    global $wpdb;
    $term_string = explode("-", $term_data);
    $new_term = get_term($term_id, $term_data);
    if(property_exists($new_term, 'taxonomy')) {   
      $taxonomy_data = get_taxonomy($new_term->taxonomy);    
      $plugin_name = 'Taxonomies / ' . $taxonomy_data->label;
    } else {
      $plugin_name = ucfirst($term_string[0]) . '/ Categories';
    }
    $table_name = $wpdb->prefix . "c5_history";

    if($new_term->taxonomy != "nav_menu" && strpos($new_term->name,"pll_") === false) {
      if(property_exists($new_term, 'taxonomy')) { 
        $wpdb->insert($table_name, array('description'=>"<b>" . $new_term->name . "</b> taxonomy has been created",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
      }
      else
      {
        $wpdb->insert($table_name, array('description'=>"<b>" . $new_term->name . "</b> category has been created",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
      }
    }
  }
}

add_action('delete_term', 'click5_delete_term', 10, 4);
function click5_delete_term($term_id, $term_term_id, $tr, $term_data){
  if($tr != "category" && $tr != "post_tag" && !isset($_REQUEST['pll_action'])) {
    global $gCurrentUser;
    global $wpdb;
    global $wp_post_types;
    global $wp_post_types;
    $term_string = explode("-", $tr);
    $table_name = $wpdb->prefix . "c5_history";
    if(property_exists($term_data, 'taxonomy')) {    
      $taxonomy_data = get_taxonomy($term_data->taxonomy);
      $plugin_name = 'Taxonomies / ' . $taxonomy_data->label;
    } else {
      $plugin_name = ucfirst($term_string[0]) . ' / Categories';
    }
    if($term_data->taxonomy != "nav_menu" && strpos($term_data->name,"pll_") === false) {
      if(property_exists($term_data, 'taxonomy')) {  
        $wpdb->insert($table_name, array('description'=>"<b>" . $term_data->name . "</b> taxonomy has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
      } else {
        $wpdb->insert($table_name, array('description'=>"<b>" . $term_data->name . "</b> category has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
      }
    }
  }
}

add_filter("rest_request_before_callbacks","c5_history_log_rest_callback_get_old",10,3);

function c5_history_log_rest_callback_get_old($response, $handler, $request){
  if(esc_attr(get_option('click5_history_log_redirection/redirection.php')) == "1"){
    global $old_rest_callback;
    if($request->get_method() == "GET" && (strpos($request->get_route(),"/google-site-kit/") < 0 && strpos($request->get_route(),"/google-site-kit/") === false))
      return $response;
      
    if(strpos($request->get_route(),"/group/") > 0)
    {
      if(isset($request->get_params()['bulk']) && $request->get_params()['bulk'] == "delete"){
        $old_rest_callback = array();
        foreach($request->get_params()['items'] as $item){
          if(!class_exists("Red_Group")) return;
          $old_rest_callback[] = Red_Group::get($item)->to_json();
        }
      }else{
        if(isset($request->get_params()['id']) && $request->get_params()['id'] != 0){
          if(!class_exists("Red_Group")) return;
            $old_rest_callback = Red_Group::get($request->get_params()['id'])->to_json();
        }
        
      }
    }
  }

  global $googleSiteKit_data;
  if(strpos($request->get_route(),"/google-site-kit/") > -1 && strpos($request->get_route(),"/google-site-kit/") !== false){
      $googleSiteKit_data = $request->get_params();
  }

  if(strpos($request->get_route(),"/wpgmza/v1/maps") !== false){
    global $wpGoMaps_data;
    if(function_exists('wpgmza_get_map_data'))
      $wpGoMaps_data = wpgmza_get_map_data(str_replace("id=","",$request->get_body()));
  }
    return $response;
}

add_filter("rest_request_after_callbacks","c5_history_log_rest_callback",10,3);
function c5_history_log_rest_callback($response, $handler, $request){
  if(esc_attr(get_option('click5_history_log_redirection/redirection.php')) == "1")
  {
    if($request->get_method() == "GET" && (strpos($request->get_route(),"/google-site-kit/") < 0 && strpos($request->get_route(),"/google-site-kit/") === false))
    return $response;

  global $redirection_module_names;
  global $old_rest_callback;
  $redirection_module_names = array(
      "",
      "wordPress",
      "Apache",
      "Nginx"
  );

  $JSON_data = $request->get_params();
  global $wpdb;
  global $gCurrentUser;
  global $old_rest_callback;
  $usr = $gCurrentUser;
  $table_name = $wpdb->prefix . "c5_history";
  if(is_null($gCurrentUser))
    $usr = "WordPress Core";

  if(strpos($request->get_route(),"/group/") > 0 || strpos($request->get_route(),"/group") > 0){
    if(isset($JSON_data['bulk'])){
      
      if($JSON_data['bulk'] == "delete"){
        foreach($old_rest_callback as $item){
          $wpdb->insert($table_name, array('description'=>'Group <b>'.$item['name'].'</b> has been deleted','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
        }
      }else if($JSON_data['bulk'] == "disable"){
        foreach($JSON_data['items'] as $item){
          if(!class_exists("Red_Group")) return;
          $item = Red_Group::get($item)->to_json();
          $wpdb->insert($table_name, array('description'=>'Group <b>'.$item['name'].'</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
        }
      }else if($JSON_data['bulk'] == "enable"){
        foreach($JSON_data['items'] as $item){
          if(!class_exists("Red_Group")) return;
          $item = Red_Group::get($item)->to_json();
          $wpdb->insert($table_name, array('description'=>'Group <b>'.$item['name'].'</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
        }
      }
  
    }else{
      if(isset($JSON_data['id']) && $JSON_data['id'] == 0){
        //$wpdb->insert($table_name, array('description'=>'Group <b>'.$JSON_data['name'].'</b> in module '.$redirection_module_names[$JSON_data['moduleId']].' has been created','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
        if(isset($JSON_data['name']))
          $wpdb->insert($table_name, array('description'=>'Group <b>'.$JSON_data['name'].'</b> has been created','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
      }else{
        $name_change = NULL;
        $module_change = NULL;
        if($old_rest_callback['name'] != $JSON_data['name'])
          $name_change = "Name: <b>".$old_rest_callback['name']."</b>";
    
        if($old_rest_callback['module_id'] != $JSON_data['moduleId'])
          $module_change = "Module: <b>".$redirection_module_names[$old_rest_callback['module_id']].'</b>';
        
          if(!is_null($name_change)  && !is_null($module_change))
            $name_change .= " and ";
        //$wpdb->insert($table_name, array('description'=>'Group <b>'.$old_rest_callback['name'].'</b> has been changed to '.$name_change.$module_change,'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
        $wpdb->insert($table_name, array('description'=>'Group <b>'.$old_rest_callback['name'].'</b> has been edited','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
      }
    }
  }else if(strpos($request->get_route(),"/redirect/") > 0){
    if(isset($JSON_data['bulk'])){

      if($JSON_data['bulk'] == "disable"){
        foreach($JSON_data['items'] as $item){
          if(!class_exists("Red_Item")) return;
          $item = Red_Item::get_by_id($item)->to_json();
          $wpdb->insert($table_name, array('description'=>'Redirect '.$item['action_code'].' from <b>'.site_url($item['url']).'</b> to <b> '.$item['action_data']['url'].' </b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
        }
      }else if($JSON_data['bulk'] == "enable"){
        foreach($JSON_data['items'] as $item){
          if(!class_exists("Red_Item")) return;
          $item = Red_Item::get_by_id($item)->to_json();
          $wpdb->insert($table_name, array('description'=>'Redirect '.$item['action_code'].' from <b>'.site_url($item['url']).'</b> to <b> '.$item['action_data']['url'].' </b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
        }
      }
    }
  }
  }

  if(strpos($request->get_route(),"/google-site-kit/") > -1 && strpos($request->get_route(),"/google-site-kit/") !== false){
    if(isset($response->status)){
      if($response->status == 200){
        $google_data = $response->get_data();
        $google_request = $request->get_params();
        if(isset($request['data'])){
  
        }      global $googleSiteKit_data;
        //"/google-site-kit/v1/core/modules/data/activation"
        if($request->get_route() == "/google-site-kit/v1/core/modules/data/activation")
        {
          $google_slug_list = array(
            'analytics'         => "Analytics",
            'adsense'           => "AdSense",
            'searchconsole'     => "Search Console",
            'pagespeedinsights' => "PageSpeed Insights",
            'optimize'          => "Optimize",
            'tagmanager'        => "Tag Manager"
  
          );
          if(isset($google_request['JSON']))
          $google_request = $google_request['JSON'];
          else{
            if(is_array($google_request['data']))
              $google_request = $google_request['data'];
          }
          
  
          if(isset($google_request['slug']) && isset($google_request['active'])){
            if($google_request['active'] == true)
            {
              $google_slug = $google_request['slug'];
              $google_slug = str_replace("-","",$google_slug);
              $google_slug = str_replace("_","",$google_slug);
              $google_slug = strtolower($google_slug);
              if(isset($google_slug_list[$google_slug]))
                $google_slug = $google_slug_list[$google_slug];
              else
                $google_slug = ucwords($google_slug);
  
              if(esc_attr(get_option("click5_history_log_google-site-kit/google-site-kit.php")) == "1")
                $wpdb->insert($table_name, array('description'=>'<b>'.$google_slug.'</b> has been activated','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Site Kit by Google", 'user'=>$usr));
            }
          }
        }/*else if(str_contains($request->get_route(),"/data/settings")){
          if(isset($google_request['slug'])){
            if(!empty($_SESSION['google_module_slug']) && $google_request['slug'] == $_SESSION['google_module_slug'])
              return;
            if($google_request['slug'] != "search-console" && is_null($_SESSION['google_module_slug']))
              $_SESSION['google_module_slug'] = $google_request['slug'];
          }
        }else if($request->get_route() == "/google-site-kit/v1/core/site/data/connection"){
          if(isset($google_data['connected'])){
            if($google_data['connected']){
              $google_slug = $_SESSION['google_module_slug'];
              $google_slug = str_replace("-","",$google_slug);
              $google_slug = str_replace("_","",$google_slug);
              $google_slug = strtolower($google_slug);
              if(isset($google_slug_list[$google_slug]))
                $google_slug = $google_slug_list[$google_slug];
              else
                $google_slug = ucwords($google_slug);
  
              $wpdb->insert($table_name, array('description'=>'<b>'.$google_slug.'</b> has been connected','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Site Kit by Google", 'user'=>$usr));
              $_SESSION['google_module_slug'] = NULL;
            }
          }
        }*/
      }
    }
  }

  if(strpos($request->get_route(),"/wpgmza/v1/maps") !== false){
    if(esc_attr(get_option("click5_history_log_wp-google-maps-pro/wp-google-maps-pro.php")) == "1"){
      global $wpGoMaps_data;
      if($request->get_method() == "DELETE"){
        global $wpdb;
        global $gCurrentUser;
        $table_name = $wpdb->prefix . "c5_history";
        if(isset($wpGoMaps_data->map_title))
          $wpdb->insert($table_name, array('description'=>'<b>'.$wpGoMaps_data->map_title.'</b> has been deleted','date'=>date('Y-m-d H:i:s'), 'plugin'=>"WP Google Maps - Pro Add-on", 'user'=>$usr));
      }else if($request->get_method() == "POST"){
        $parsed_args = "";
        parse_str($request->get_body(),$parsed_args);
        if(isset($parsed_args['action']) && $parsed_args['action'] == "duplicate"){
          if(function_exists('wpgmza_get_map_data')){
            $title = wpgmza_get_map_data($parsed_args['id'])->map_title;
            $wpdb->insert($table_name, array('description'=>'<b>'.$title.'</b> has been duplicated','date'=>date('Y-m-d H:i:s'), 'plugin'=>"WP Google Maps - Pro Add-on", 'user'=>$usr));
          }
        }
      }
    }
  }

  return $response;
}

add_action("sheduled_email",function(){
  global $wpdb;
  global $gCurrentUser;
  $table_name = $wpdb->prefix . "c5_history";
  if(get_transient("click5_history_log_mail_transient_critical") === false){
    do_action("click5_history_log_alerts_email");
    set_transient("click5_history_log_mail_transient_critical","1",70);
  }  
});




add_filter( 'redirection_create_redirect', 'click5_redirection_create_redirect', 10, 3 );
function click5_redirection_create_redirect($data)
{
  if(esc_attr(get_option('click5_history_log_redirection/redirection.php')) == "1"){
  global $wpdb;
  global $gCurrentUser;
  $usr = $gCurrentUser;
  $table_name = $wpdb->prefix . "c5_history";
  if(is_null($gCurrentUser))
    $usr = "WordPress Core";


  $wpdb->insert($table_name, array('description'=>'Redirect '.$data['action_code'].' from <b>'.site_url($data['url']).'</b> to <b>'.$data['action_data'].'</b> has been created','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
}


  return $data;
}
add_filter( 'redirection_redirect_deleted', 'click5_redirection_redirect_deleted', 10, 3 );
function click5_redirection_redirect_deleted($data)
{
  if(esc_attr(get_option('click5_history_log_redirection/redirection.php')) == "1"){
  global $wpdb;
  global $gCurrentUser;
  $usr = $gCurrentUser;
  $table_name = $wpdb->prefix . "c5_history";
  if(is_null($gCurrentUser))
    $usr = "WordPress Core";
  if(is_a($data,"Red_Item")){
    $data = $data->to_json();
    $wpdb->insert($table_name, array('description'=>'Redirect '.$data['action_code'].' from <b>'.site_url($data['url']).'</b> to <b>'.$data['action_data']['url'].'</b> has been deleted','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
  }
  } 
  return $data;
}
add_filter( 'redirection_redirect_updated', 'click5_redirection_redirect_updated', 10, 3 );
function click5_redirection_redirect_updated($data)
{
  if(esc_attr(get_option('click5_history_log_redirection/redirection.php')) == "1"){
  global $wpdb;
  global $gCurrentUser;
  $usr = $gCurrentUser;

  $table_name = $wpdb->prefix . "c5_history";
  if(is_null($gCurrentUser))
    $usr = "WordPress Core";

  if(is_a($data,"Red_Item")){
    $data =  $data->to_json();
    $wpdb->insert($table_name, array('description'=>'Redirect '.$data['action_code'].' from <b>'.site_url($data['url']).'</b> to <b>'.$data['action_data']['url'].'</b> has been edited','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Redirection", 'user'=>$usr));
  }
}
  return $data;
}

$_SESSION['background_updates'] = array();

add_filter( 'site_status_test_result', "click5_site_status_result",10,1 );
function click5_site_status_result($test_result){
  session_write_close();
  global $gCurrentUser;
  global $wpdb;
  $usr = $gCurrentUser;
  $sendLogEmail = false;
  $table_name = $wpdb->prefix . "c5_history";
  $tests_count = count(WP_Site_Health::get_tests()['direct']);

  if(get_transient('healt_check_tests_count') === false){
    set_transient('healt_check_tests_count', 1);
  }
  else{
    set_transient('healt_check_tests_count', intval(get_transient('healt_check_tests_count')) + 1);
  }

  if(get_transient('healt_check_tests_critical_count') === false){
    if(esc_attr(get_option("click5_history_log_module_site_health")) === "1")
      set_transient('healt_check_tests_critical_count', 0);
  }
    

  if(is_null($gCurrentUser) && !is_null(wp_get_current_user()->data->user_login))
      $usr = wp_get_current_user()->data->user_login;

  if(empty($usr))
    $usr = "WordPress Core";

  if(isset($test_result['status']) && $test_result['status'] == "critical"){
    $label = $test_result['label'];
    if(function_exists("str_replace")){
      if(strpos($label,".",-1) !== false)
        $label = substr_replace($label,"",strpos($label,".",-1),1);
    }
    set_transient('healt_check_tests_critical_count', intval(get_transient('healt_check_tests_critical_count')) + 1);
    if(in_array($label,$_SESSION['background_updates']) !== true){
      if(esc_attr(get_option("click5_history_log_module_site_health")) === "1")
        $wpdb->insert($table_name, array('description'=>'Critical error: <b>'.$label."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Site Health", 'user'=>$usr));
    }
    //wp_schedule_single_event(time() + 30, "sheduled_email");
    $_SESSION['background_updates'][] = $label;
  }
    if($tests_count <= intval(get_transient('healt_check_tests_count'))){
      $asyncTests = array(
        'dotorg_communication',
        'background_updates',
        'loopback_requests',
        'https_status',
        'authorization_header',
      );
      foreach(WP_Site_Health::get_tests()['async'] as $test => $data){
        $wpsitehealth = new WP_Site_Health();
        if(array_search($test,$asyncTests) !== false){
          $testData = $wpsitehealth->{"get_test_".$test}();
          if($testData['status'] == "critical"){
            set_transient('healt_check_tests_critical_count', intval(get_transient('healt_check_tests_critical_count')) + 1);
            $label = $testData['label'];
            if(in_array($label,$_SESSION['background_updates']) !== true){
              if(esc_attr(get_option("click5_history_log_module_site_health")) === "1")
                $wpdb->insert($table_name, array('description'=>'Critical error: <b>'.$label."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Site Health", 'user'=>$usr));
            }
            $_SESSION['background_updates'][] = $label;
          }
        }
      }
      if(intval(get_transient('healt_check_tests_critical_count')) > 0){
        if(esc_attr(get_option("click5_history_log_module_site_health")) === "1")
          $sendLogEmail = true;
      }
        
      delete_transient('healt_check_tests_count');
      delete_transient('healt_check_tests_critical_count');
    }

    /*if(empty($test_result)){
      if(get_transient('healt_check_tests_critical_count') > 0)
        $sendLogEmail = true;
      delete_transient('healt_check_tests_count');
      delete_transient('healt_check_tests_critical_count');
    }*/

    if(esc_attr(get_option("click5_history_log_critical_error")) == "1" && $sendLogEmail){
      //do_action("click5_history_log_alerts_email");
      wp_schedule_single_event(time() + 30, "sheduled_email");
      $sendLogEmail = false;
      return $test_result;
    }

  return $test_result;
}



add_filter("widget_update_callback","click5_update_widget",10,4);
function click5_update_widget( $instance,  $new_instance,  $old_instance,  $widget){
  global $gCurrentUser;
  global $wpdb;
  if(!isset($new_instance['content']) || empty($new_instance['content']))
    return $instance;
  $name = $new_instance['content'];
  $start_index = strpos($name,"wp:")+3;
  $end_index = strpos($name," ",$start_index);
  $len = $end_index - $start_index;
  $name = substr($name,$start_index,$len);

  $table_name = $wpdb->prefix . "c5_history";
  $plugin_name = "Widgets";
  if(is_plugin_active("classic-widgets/classic-widgets.php") && esc_attr(get_option("click5_history_log_classic-widgets/classic-widgets.php")) == "1")
    $plugin_name = "Classic Widgets";
  else if(current_theme_supports('widgets-block-editor') === false)
    $plugin_name = "Legacy Widgets";
  else{
    if(esc_attr(get_option("click5_history_log_module_widgets")) === "1"){
      return $instance;
    }
  }
  if(count($old_instance) != 0){
    $widgetname = $widget->name;
    if(!is_plugin_active("classic-widgets/classic-widgets.php") && current_theme_supports('widgets-block-editor') !== false){
      if(!empty(ucfirst($name)))
        $widgetname = ucfirst($name);
      if($new_instance != $old_instance)
      {
        if(isset($_REQUEST[str_replace("_","-",$widget->widget_options['classname'])])){
          
          $wpdb->insert($table_name, array('description'=>'Widget <b>'.$widgetname."</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
        }
      }
    }else
    {
      $wpdb->insert($table_name, array('description'=>'Widget <b>'.$widgetname."</b> has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
    }
  }/*else{
    if(!is_plugin_active("classic-widgets/classic-widgets.php")){
      $widgetname = ucfirst($name);
      $wpdb->insert($table_name, array('description'=>'Widget <b>'.$widgetname."</b> has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
    }
  }*/
  return $instance;
}

add_action("wp_ajax_sbi_feed_saver_manager_builder_update","click5_sbi_new_feed");
function click5_sbi_new_feed(){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $feed_name = "";
  if(!empty($_REQUEST)){
    if(isset($_REQUEST['feed_name']) && !empty($_REQUEST['feed_name']))
      $feed_name = $_REQUEST['feed_name'];
    if(esc_attr(get_option("click5_history_log_instagram-feed/instagram-feed.php")) == "1"){
      if(isset($_REQUEST['update_feed'])){
        $wpdb->insert($table_name, array('description'=>'Feed <b>'.$feed_name.'</b> has been updated','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
      }else if(isset($_REQUEST['new_insert']) && $_REQUEST['new_insert'] == "true"){
        $wpdb->insert($table_name, array('description'=>'New <b>feed '.$feed_name.'</b> has been created','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
      }
    }
  }
}

add_action("wp_ajax_sbi_feed_saver_manager_duplicate_feed",function (){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $feed_name = "";
    if(is_plugin_active('instagram-feed/instagram-feed.php')){
      if(class_exists("InstagramFeed\Builder\SBI_Db") || class_exists("SBI_Db")){
        $sbidb = new SBI_Db;
        if(isset($_REQUEST['feed_id'])){
          $feed_name = $sbidb->feeds_query(array('id'=>$_REQUEST['feed_id']))[0]['feed_name'];
        }
        if(esc_attr(get_option("click5_history_log_instagram-feed/instagram-feed.php")) == "1")
          $wpdb->insert($table_name, array('description'=>'Feed <b>'.$feed_name.'</b> has been duplicated','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
      }
    }
});

add_action( 'dynamic_sidebar_params', function($params){
  if(!is_plugin_active("classic-widgets/classic-widgets.php")){
    $widget_block = get_option("widget_block");
    $widget_content = "";
    if(!empty($widget_block[$params[1]['number']]))
      $widget_content = $widget_block[$params[1]['number']];
    $sidebar_name = $params[0]['name'];
    global $wp_registered_widgets;
    global $click5_registered_widget;
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $name = "";
    if(is_array($widget_content))
      $name = $widget_content['content'];

    if(!empty($name)){
        $start_index = strpos($name,"wp:")+3;
        $end_index = strpos($name," ",$start_index);
        $len = $end_index - $start_index;
        $name = substr($name,$start_index,$len);
        $name = ucfirst($name);
    }
    
    if(empty($_SESSION['deletedWidget']))
      $_SESSION['deletedWidget'] = array();
    if(count($click5_registered_widget) < count($wp_registered_widgets)){
      $_SESSION['deletedWidget'] = get_option("widget_block");
      $diff = array_diff(array_keys($wp_registered_widgets),array_keys($click5_registered_widget));
      foreach($diff as $key => $value){
        if($params[0]['widget_id'] == $value){
          $wpdb->insert($table_name, array('description'=>'New Widget<b> '.$name.' </b> has been added to '.$sidebar_name,'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Widgets", 'user'=>$gCurrentUser));
        }
      }
    }else if(count($click5_registered_widget) == count($wp_registered_widgets)){
      if(count($_SESSION['deletedWidget']) == count($widget_block) || empty($_SESSION['deletedWidget']) || count($_SESSION['deletedWidget']) < count($widget_block))
        $_SESSION['deletedWidget'] = get_option("widget_block");
      if(count($_SESSION['deletedWidget']) > count($widget_block)){
        $widget_id = array_values(array_diff(array_keys($_SESSION['deletedWidget']),array_keys($widget_block)))[0];
        $name = $_SESSION['deletedWidget'][$widget_id]['content'];
        $start_index = strpos($name,"wp:")+3;
        $end_index = strpos($name," ",$start_index);
        $len = $end_index - $start_index;
        $name = substr($name,$start_index,$len);
        $name = ucfirst($name);
        $wpdb->insert($table_name, array('description'=>'Widget<b> '.$name.' </b> has been deleted from '.$sidebar_name,'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Widgets", 'user'=>$gCurrentUser));
        $_SESSION['deletedWidget'] = get_option("widget_block");
      }
    }
  }
  return $params;
});

add_action("wp_ajax_sbi_feed_saver_manager_delete_feeds",function(){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $feed_name = "";
  if(is_plugin_active('instagram-feed/instagram-feed.php')){
    if(class_exists("InstagramFeed\Builder\SBI_Db") || class_exists("SBI_Db")){
      $sbidb = new SBI_Db;
      if(esc_attr(get_option("click5_history_log_instagram-feed/instagram-feed.php")) == "1"){
        if(isset($_REQUEST['feeds_ids']) && is_array($_REQUEST['feeds_ids'])){
          foreach($_REQUEST['feeds_ids'] as $feed_id){
            $feed_name = $sbidb->feeds_query(array('id'=>$feed_id))[0]['feed_name'];
            $wpdb->insert($table_name, array('description'=>'Feed <b>'.$feed_name.'</b> has been deleted','date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
          }
        }
      }
    }
  }
});

add_action("admin_post_wpgmza_save_map","click5_wpgmza_save_map");
function click5_wpgmza_save_map(){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_wp-google-maps/wpGoogleMaps.php")) == "1" || (esc_attr(get_option("click5_history_log_wp-google-maps-pro/wp-google-maps-pro.php")) == "1" && is_plugin_active("wp-google-maps-pro/wp-google-maps-pro.php"))){
    $plugin_name = "WP Go Maps";
    if(esc_attr(get_option("click5_history_log_wp-google-maps-pro/wp-google-maps-pro.php")) == "1" && is_plugin_active("wp-google-maps-pro/wp-google-maps-pro.php"))
      $plugin_name = "WP Google Maps - Pro Add-on";
    $map_title = $_REQUEST['map_title'];
    $wpdb->insert($table_name, array('description'=>"<b>".$map_title."</b> has been edited",'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
  }
}

add_action("wp_ajax_wpgmza_maps_settings_danger_zone_delete_data ",function($wp_rest_server ){

});


add_filter("wpgmza_create_WPGMZA\Map",function($filter_args){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_wp-google-maps-pro/wp-google-maps-pro.php")) == "1"){
    if(is_array($filter_args)){
      $wpdb->insert($table_name, array('description'=>"<b>".$filter_args['map_title']."</b> has been created",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WP Google Maps - Pro Add-on', 'user'=>$gCurrentUser));
    }
  }
  return $filter_args;
});

setcookie("widget_change",true);
$_SESSION['widgetAdded'] = false;

global $JETPACK_MODULE_NAMES;
$JETPACK_MODULE_NAMES = array(
  "photon-cdn"            => "Speed up static file load times",
  //"photon"                => "Site accelerator",
  "tiled-gallery"         => "Speed up image load times",
  "lazy-images"           => "Lazy Loading for images",
  "carousel"              => "Display images in a full-screen carousel gallery",
  "copy-post"             => "Copy entire posts and pages",
  "markdown"              => "Write posts or pages in plain-text Markdown syntax",
  "latex"                 => "Use the LaTeX markup language",
  "shortcodes"            => "Compose using shortcodes",
  "custom-css"            => "Custom CSS",
  "widgets"               => "Make extra widgets available",
  "widget-visibility"     => "Widget visibility controls",
  "sharedaddy"            => "Add sharing buttons",
  "gravatar-hovercards"   => "Pop-up business cards",
  "sitemaps"              => "Generate XML sitemaps",
  "verification-tools"    => "Verify site ownership with third party services",
  "waf"                   => "Web Application Firewall",
  "likes"                 => "Like buttons",
  "comment-likes"         => "Comment likes",
  "related-posts"         => "Show related content after posts",
  "shortlinks"            => "Generate shortened URLs",
  "comments"              => "Comments",
  "seo-tools"             => "Customize your SEO settings"
  

);

add_action("jetpack_deactivate_module",function($module, $success){
  if(esc_attr(get_option("click5_history_log_jetpack/jetpack.php")) == "1"){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    global $JETPACK_MODULE_NAMES;
    if(class_exists("Modules") || class_exists("Automattic\Jetpack\Modules"))
      $modul = new jetpackModules;
    if(!empty($JETPACK_MODULE_NAMES[$module])){
      $moduleName = $JETPACK_MODULE_NAMES[$module];
      $wpdb->insert($table_name, array('description'=>"<b>".$moduleName."</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
      if(class_exists("Modules") || class_exists("Automattic\Jetpack\Modules")){
        if($module == "photon-cdn"){
          if($modul->is_active("tiled-gallery") === false){
            $wpdb->insert($table_name, array('description'=>"<b>Site accelerator</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
          }
        }else if($module == "tiled-gallery"){
          if($modul->is_active("photon-cdn") === false){
            $wpdb->insert($table_name, array('description'=>"<b>Site accelerator</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
          }
        }
      }
    }
  }
},10,2);

add_action("jetpack_activate_module",function($module, $success){
  if(esc_attr(get_option("click5_history_log_jetpack/jetpack.php")) == "1"){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    global $JETPACK_MODULE_NAMES;
    if(class_exists("Modules") || class_exists("Automattic\Jetpack\Modules"))
      $modul = new jetpackModules;
    if(!empty($JETPACK_MODULE_NAMES[$module])){
      $moduleName = $JETPACK_MODULE_NAMES[$module];
      $wpdb->insert($table_name, array('description'=>"<b>".$moduleName."</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
      if(class_exists("Modules") || class_exists("Automattic\Jetpack\Modules")){
        if($module == "photon-cdn"){
          if($modul->is_active("tiled-gallery") === false){
            $wpdb->insert($table_name, array('description'=>"<b>Site accelerator</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
          }
        }else if($module == "tiled-gallery"){
          if($modul->is_active("photon-cdn") === false){
            $wpdb->insert($table_name, array('description'=>"<b>Site accelerator</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
          }
        }
      }
    }
  }
},10,2);

add_action("dp_duplicate_post","click5_dp_duplicate_post",10,3);
function click5_dp_duplicate_post($new_post_id, $post, $status){
  if(esc_attr(get_option("click5_history_log_duplicate-post/duplicate-post.php")) == "1"){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $postTitle = "";
    if(isset($post->post_title))
      $postTitle = $post->post_title;
    if($status == "draft") 
      $wpdb->insert($table_name, array('description'=>"New Draft has been created for <b>".$postTitle."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
    else
      $wpdb->insert($table_name, array('description'=>"Post <b>".$postTitle."</b> has been cloned",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
  
  }
 }

add_action("dp_duplicate_page","click5_dp_duplicate_page",10,3);
function click5_dp_duplicate_page($new_post_id, $post, $status){
  if(esc_attr(get_option("click5_history_log_duplicate-post/duplicate-post.php")) == "1"){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $postTitle = "";
    if(isset($post->post_title))
      $postTitle = $post->post_title;
    if($status == "draft") 
      $wpdb->insert($table_name, array('description'=>"New Draft has been created for <b>".$postTitle."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
    else
      $wpdb->insert($table_name, array('description'=>"Page <b>".$postTitle."</b> has been cloned",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
  }
}

add_action("duplicate_post_after_rewriting","click5_dp_republish_post",10,2);
function click5_dp_republish_post($copy_id, $original_id){
  if(esc_attr(get_option("click5_history_log_duplicate-post/duplicate-post.php")) == "1"){
    $original_post = get_post($original_id);
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $original_postTitle = "";

    if(isset($original_post->post_title))
      $original_postTitle = $original_post->post_title;

    $wpdb->insert($table_name, array('description'=>"<b>{$original_postTitle}</b> has been republished",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
  }
}

if(esc_attr(get_option("click5_history_log_all-in-one-wp-migration/all-in-one-wp-migration.php")) == "1"){
  add_action("wp_ajax_ai1wm_export",function($params){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $pageWhere = "Export";
    $pageAction = "ended";
    $currUrl = $_SERVER['HTTP_REFERER'];
    parse_str(parse_url($currUrl)['query'],$currUrl);
    if(isset($currUrl['page'])){
      if($currUrl['page'] == "ai1wm_backups"){
        $pageWhere = "Backup";
        $pageAction = "created";
      }
        
    }
    if(!isset($_REQUEST['priority'])){
      $wpdb->insert($table_name, array('description'=>"<b>".$pageWhere."</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'All-in-One WP Migration', 'user'=>$gCurrentUser));
    }else{  
      if($_REQUEST['priority'] === 300 || $_REQUEST['priority'] === "300"){
        if(isset($_REQUEST['archive'])){
          $wpdb->insert($table_name, array('description'=>$pageWhere." <b>".$_REQUEST['archive']."</b> has been ".$pageAction,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'All-in-One WP Migration', 'user'=>$gCurrentUser));
        }else{
          $wpdb->insert($table_name, array('description'=>"<b>".$pageWhere."</b> has been ".$pageAction,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'All-in-One WP Migration', 'user'=>$gCurrentUser));
        }
      }
    }
  });

  add_action("wp_ajax_ai1wm_import",function($params){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    if(!isset($_REQUEST['priority'])){
      $wpdb->insert($table_name, array('description'=>"<b>Import</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'All-in-One WP Migration', 'user'=>$gCurrentUser));
    }else{  
      if($_REQUEST['priority'] === 300 || $_REQUEST['priority'] === "300"){
        if(isset($_REQUEST['archive'])){
          $wpdb->insert($table_name, array('description'=>"Import <b>".$_REQUEST['archive']."</b> has been ended",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'All-in-One WP Migration', 'user'=>$gCurrentUser));
        }else{
          $wpdb->insert($table_name, array('description'=>"<b>Import</b> has been ended",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'All-in-One WP Migration', 'user'=>$gCurrentUser));
        }
      }
    }

  },10,1);

  add_action("wp_ajax_ai1wm_backups",function($params){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(isset($_REQUEST['archive'])){
      $wpdb->insert($table_name, array('description'=>"Backup <b>".$_REQUEST['archive']."</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'All-in-One WP Migration', 'user'=>$gCurrentUser));
    }
  });

  add_action("wp_ajax_ai1wm_add_backup_label",function($params){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(isset($_REQUEST['archive'])){
      $wpdb->insert($table_name, array('description'=>"Label <b>".$_REQUEST['label']."</b> has been added to <b>".$_REQUEST['archive']."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'All-in-One WP Migration', 'user'=>$gCurrentUser));
    }  
  });

  add_action("wp_ajax_ai1wm_backup_download_file",function($params){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $fileName = str_replace('\\\\', '\\',$_REQUEST['file_name']);
    if(isset($_REQUEST['archive'])){
      $wpdb->insert($table_name, array('description'=>"File <b>".$fileName."</b> has been downloaded from <b>".$_REQUEST['archive']."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'All-in-One WP Migration', 'user'=>$gCurrentUser));
    }  
  });

  /* 
      //All-In-One WP Migration backup download
    jQuery(".ai1wm-backup-dots-menu a").click((event) => {
      if(event.currentTarget.download != undefined){
        let fileName = event.currentTarget.download;
        postRequestJSON(c5resturl.wpjson + 'click5_history_log/API/ai1wpm_backup_download', {download: event.currentTarget.download}, (data) => {
          console.log(data);
        });
      }
    });*/
}

if(esc_attr(get_option("click5_history_log_updraftplus/updraftplus.php")) == "1"){
  add_action("wp_ajax_updraft_download_backup",function(){
    $backupDescription = array(
      'db'      => 'Database',
      'plugins' => 'Plugins',
      'themes'  => 'Themes',
      'uploads' => 'Uploads',
      'others'  => 'Others',
    );
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $currDate = new DateTime(); 
    $currDate->setTimezone(new DateTimeZone(wp_timezone_string()));
    $currDate->setTimestamp("1659442134");
    $currDate = $currDate->format('M d, Y H:i');

    $whatToBackup = "";
    
    if(!isset($_REQUEST['subaction'])){
      if(isset($_REQUEST['type'])){
        if(!empty($backupDescription[$_REQUEST['type']])){
          $whatToBackup = $backupDescription[$_REQUEST['type']];
        }
      }
      $wpdb->insert($table_name, array('description'=>"Backup <b>".$whatToBackup."</b> from <b>".$currDate."</b> has been downloaded",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"UpdraftPlus - Backup/Restore", 'user'=>$gCurrentUser));
    }
  });

  add_action("wp_ajax_updraft_savesettings",function(){
    $settingsList = "";
    parse_str($_REQUEST['settings'],$settingsList);
  });

  add_action("updraft_backupnow_backup_all",function(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $wpdb->insert($table_name, array('description'=>"<b>Full backup</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"UpdraftPlus - Backup/Restore", 'user'=>$gCurrentUser));
  });


  add_action("updraft_backupnow_backup",function(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $wpdb->insert($table_name, array('description'=>"<b>File backup</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"UpdraftPlus - Backup/Restore", 'user'=>$gCurrentUser));
  });

  add_action("updraft_backupnow_backup_database",function(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $wpdb->insert($table_name, array('description'=>"<b>Database backup</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"UpdraftPlus - Backup/Restore", 'user'=>$gCurrentUser));
  },4,1);

  ///
  add_action("updraft_backup_all",function(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $wpdb->insert($table_name, array('description'=>"<b>Full backup</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"UpdraftPlus - Backup/Restore", 'user'=>'UpdraftPlus - Backup/Restore'));
  });


  add_action("updraft_backup",function(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $wpdb->insert($table_name, array('description'=>"<b>File backup</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"UpdraftPlus - Backup/Restore", 'user'=>'UpdraftPlus - Backup/Restore'));
  });

  add_action("updraft_backup_database",function(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $wpdb->insert($table_name, array('description'=>"<b>Database backup</b> has been started",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"UpdraftPlus - Backup/Restore", 'user'=>'UpdraftPlus - Backup/Restore'));
  });

  add_filter("updraftplus_logline",function($line, $nonce, $level, $uniq_id, $destination){
    
    return $line;
  },10,5);

}

if(esc_attr(get_option("click5_history_log_duplicator/duplicator.php	")) == "1"){
  add_action("duplicator_lite_build_database_completed",function($package){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $packageName = $package->Name;
    if($package->Archive->ExportOnlyDB != 1){
      $wpdb->insert($table_name, array('description'=>"Package <b>".$packageName."</b> for site has been created",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Duplicator', 'user'=>$gCurrentUser));
    }

    $wpdb->insert($table_name, array('description'=>"Package <b>".$packageName."</b> for database has been created",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Duplicator', 'user'=>$gCurrentUser));
  },10,1);
  
  add_action("wp_ajax_duplicator_download_installer",function(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
  
    if(isset($_REQUEST['action'])){
      if($_REQUEST['action'] == "duplicator_download_installer"){
        if(isset($_REQUEST['id'])){
          if(class_exists("DUP_Package")){
            $package_id = $_REQUEST['id'];
            $packageData = DUP_Package::getByID($package_id);
            $wpdb->insert($table_name, array('description'=>"Installer for package <b>".$packageData->Name."</b> has been downloaded",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Duplicator', 'user'=>$gCurrentUser));
          }
        }
      }
    }
  
  },2,1);
  
  
  add_action("wp_ajax_duplicator_package_delete",function(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    
    if(class_exists("DUP_Package")){
      $package_ids = $_REQUEST['package_ids'];
      if(is_array($package_ids)){
        foreach($package_ids as $package){
          $packageData = DUP_Package::getByID($package);
          $wpdb->insert($table_name, array('description'=>"Package <b>".$packageData->Name."</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Duplicator', 'user'=>$gCurrentUser));
        }
      }
    }
  },2,1);
  
  add_action("wp_ajax_DUP_CTRL_Tools_runScanValidator",function(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $wpdb->insert($table_name, array('description'=>"<b>Scan Integrity Validation</b> has been run",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Duplicator', 'user'=>$gCurrentUser));
  },2,1);
  
  add_action("admin_init",function(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
  
    if(isset($_REQUEST['page'])){
      if($_REQUEST['page'] == "duplicator-tools"){
        if(isset($_REQUEST['action'])){
          if($_REQUEST['action'] == "installer"){
            $wpdb->insert($table_name, array('description'=>"<b>Installation Files</b> has been removed",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Duplicator', 'user'=>$gCurrentUser));
          }else if($_REQUEST['action'] == "tmp-cache"){
            $wpdb->insert($table_name, array('description'=>"<b>Build Cache</b> has been cleaned",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Duplicator', 'user'=>$gCurrentUser));
          }
        }
      }
    }
  });
}

/*add_action("loco_file_written",function($path){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  global $REQUEST_FROM_INIT;
  $project = $_REQUEST['domain'];
  if(class_exists("Loco_package_Bundle")){
    $project = Loco_package_Bundle::fromId($_REQUEST['bundle'])->getProjectById($_REQUEST['domain']);
    if(!empty($project->getName())){
      $project = $project->getName();
    }else{
      $project = $_REQUEST['domain'];
    }
  }
    
  $langArray = require(ABSPATH.'wp-content/plugins/loco-translate/lib/data/languages.php');
  $language = "";

  if(is_array($langArray)){

    if(empty($language)){
      if(isset($_REQUEST['select-locale'])){
        if(strpos($_REQUEST['select-locale'],"_") !== false)
          $language = $langArray[substr($_REQUEST['select-locale'],0,strpos($_REQUEST['select-locale'],"_"))];
        else
          $language = $langArray[$_REQUEST['select-locale']];
      }
    }     
  }

  if(file_exists(str_replace(".po",".mo",$path)) !== true && strpos($path,".po") !== false){
    if(isset($_REQUEST['route'])){
      if(isset($_REQUEST['source']) && !empty($_REQUEST['source'])){
        if($_REQUEST['route'] == "msginit"){
          $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation in ".$project." domain has been copied",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
        }
      }else if(isset($_REQUEST['source']) && empty($_REQUEST['source'])){
          $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation in ".$project." domain has been created",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
      }
    }
  }
});*/

/*add_filter( 'loco_ajax_init', function($ctrl){
  global $loco_delete_data;
  if(isset($_REQUEST['auth']) && $_REQUEST['auth'] == "delete"){
    $loco_delete_data = $_REQUEST;
    if(!isset($_SESSION['loco_deleteData']) || empty($_SESSION['loco_deleteData']))
      $_SESSION['loco_deleteData'] = $loco_delete_data;
  }

},2,1);*/

/*add_action("loco_admin_init",function($ctrl){
 if(isset($_REQUEST['route']) && $_REQUEST['route'] != "xgettext"){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  global $REQUEST_FROM_INIT;
  global $loco_delete_data;
  $projectName = "";
  $domainName = "";
  if(isset($_REQUEST['domain'])){
    $projectName = $_REQUEST['domain'];
    $domainName = $_REQUEST['domain'];

    if($ctrl->getBundle() != NULL){
      $projectName = $ctrl->getBundle()->getProjectByID($_REQUEST['domain']);
      if(empty($projectName))
        $projectName = $_REQUEST['domain'];
      else
        $projectName = $projectName->getName();
    }
  }

  $domainName = str_replace("default.","",$domainName);
  $languageSlug = "";
  if(!empty($domainName)  || $domainName != ""){
    $position = strpos($_REQUEST['path'],$domainName);
    if(!empty($position) || $position != " " || $position != ""){
      $languageSlug = substr($_REQUEST['path'],$position);
      $languageSlug = str_replace($domainName."-","",$languageSlug);
      $languageSlug = str_replace("","",$languageSlug);
      if(strpos($languageSlug,"_") !== false){
        $languageSlug = substr($_REQUEST['path'],0,strpos($languageSlug,"_"));
      }
    }
  }else{
    if(isset($_REQUEST['path'])){
      $lastOccurrence = strripos($_REQUEST['path'],"/");
      $languageSlug = substr($_REQUEST['path'],$lastOccurrence+1);
      if(strpos($languageSlug,"_") !== false){
        $languageSlug = substr($_REQUEST['path'],0,strpos($languageSlug,"_"));
      }

    }
  }

  if(!empty($languageSlug) || $languageSlug != ""){
    if(strpos($languageSlug,"/") === false && strpos($languageSlug,"-") === false && strpos($languageSlug,".po") !== false)
      $_SESSION['loco_languageSlug'] = $languageSlug;
  }   
    

  if(!empty($projectName) || $projectName != "")
    $_SESSION['loco_projectName'] = $projectName;

    $langArray = require(ABSPATH.'wp-content/plugins/loco-translate/lib/data/languages.php');
    $language = "";

    if(is_array($langArray)){
      $languageSlug = $_SESSION['loco_languageSlug'];

      if(!empty($languageSlug)){

        if(!empty($langArray[$languageSlug]))
          $language = $langArray[$languageSlug];
        else
          $language = $languageSlug;

        if(isset($_SESSION['loco_deleteData']) && !empty($_SESSION['loco_deleteData'])){
          $loco_delete_data = $_SESSION['loco_deleteData'];
        }

        $projectName = $_SESSION['loco_projectName'];

        if(isset($loco_delete_data['action']) && $loco_delete_data['auth'] == "delete" && (isset($_REQUEST['action']) && $_REQUEST['action'] != "file-delete")){
          if(!file_exists(WP_CONTENT_DIR."/".$loco_delete_data['path'])){
            $wpdb->insert($table_name, array('description'=>"<b>".$language."</b> translation in ".$project." domain has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
            $_SESSION['loco_deleteData'] = NULL;
            unset($_SESSION['loco_deleteData']);
            $_SESSION['loco_projectName'] = NULL;
            unset($_SESSION['loco_projectName']);
            $_SESSION['loco_languageSlug'] = NULL;
            unset($_SESSION['loco_languageSlug']);
          }
        }
      }     
    }
 }
});*/


function click5_get_user_by($user,$by){
  $userData = get_user_by($by,$user);
  if($userData){
    if(!empty($userData->display_name))
      return $userData->display_name;
    else
      return $userData;
  }
    return $user;
}

if(esc_attr(get_option("click5_history_log_polylang/polylang.php")) == "1"){
  add_action("pll_add_language",function($args){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    if($args['pll_action'] == "add"){
      $language = $args['name'];
      if(empty($language)){
        if(!empty($args['locale'])){
          $language = strtoupper($args['locale']);
        }else if(!empty($args['slug'])){
          $language = strtoupper($args['slug']);
        }
      }

      $wpdb->insert($table_name, array('description'=>'<b>'.$language.'</b> language has been added','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
    }
    
 
  });
  
  add_action("pll_update_language",function($args){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    if($args['pll_action'] == "update"){
      $language = $args['name'];
      if(empty($language)){
        if(!empty($args['locale'])){
          $language = strtoupper($args['locale']);
        }else if(!empty($args['slug'])){
          $language = strtoupper($args['slug']);
        }
      }

      $wpdb->insert($table_name, array('description'=>'<b>'.$language.'</b> language has been updated','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
    }  
  });
  
  add_action( 'delete_term', function( $term,  $tt_id,  $taxonomy,  $deleted_term,  $object_ids){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    if($taxonomy == "language" && isset($_REQUEST['pll_action'])){
      $language = $deleted_term->name;
      $wpdb->insert($table_name, array('description'=>'<b>'.$language.'</b> language has been deleted','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
    }

  },10,5);

  add_action( 'pll_save_strings_translations' , function($arg){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(isset($_REQUEST['pll_action']) && $_REQUEST['pll_action'] == "string-translation"){
      $wpdb->insert($table_name, array('description'=>"<b>Strings translations</b> has been changed",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
    }
  });

  add_action("pll_save_post",function($post_id, $post, $translations){
    $languages_list = array(
      "af" => "Afrikaans",
      "sq" => "Albanian",
      "am" => "Amharic",
      "ar" => "Arabic",
      "an" => "Aragonese",
      "hy" => "Armenian",
      "ast" => "Asturian",
      "az" => "Azerbaijani",
      "eu" => "Basque",
      "be" => "Belarusian",
      "bn" => "Bengali",
      "bs" => "Bosnian",
      "br" => "Breton",
      "bg" => "Bulgarian",
      "ca" => "Catalan",
      "ckb" => "Central Kurdish",
      "zh" => "Chinese",
      "zh-HK" => "Chinese (Hong Kong)",
      "zh-CN" => "Chinese (Simplified)",
      "zh-TW" => "Chinese (Traditional)",
      "co" => "Corsican",
      "hr" => "Croatian",
      "cs" => "Czech",
      "da" => "Danish",
      "nl" => "Dutch",
      "en" => "English",
      "en-AU" => "English (Australia)",
      "en-CA" => "English (Canada)",
      "en-IN" => "English (India)",
      "en-NZ" => "English (New Zealand)",
      "en-ZA" => "English (South Africa)",
      "en-GB" => "English (United Kingdom)",
      "en-US" => "English (United States)",
      "eo" => "Esperanto",
      "et" => "Estonian",
      "fo" => "Faroese",
      "fil" => "Filipino",
      "fi" => "Finnish",
      "fr" => "French",
      "fr-CA" => "French (Canada)",
      "fr-FR" => "French (France)",
      "fr-CH" => "French (Switzerland)",
      "gl" => "Galician",
      "ka" => "Georgian",
      "de" => "German",
      "de-AT" => "German (Austria)",
      "de-DE" => "German (Germany)",
      "de-LI" => "German (Liechtenstein)",
      "de-CH" => "German (Switzerland)",
      "el" => "Greek",
      "gn" => "Guarani",
      "gu" => "Gujarati",
      "ha" => "Hausa",
      "haw" => "Hawaiian",
      "he" => "Hebrew",
      "hi" => "Hindi",
      "hu" => "Hungarian",
      "is" => "Icelandic",
      "id" => "Indonesian",
      "ia" => "Interlingua",
      "ga" => "Irish",
      "it" => "Italian",
      "it-IT" => "Italian (Italy)",
      "it-CH" => "Italian (Switzerland)",
      "ja" => "Japanese",
      "kn" => "Kannada",
      "kk" => "Kazakh",
      "km" => "Khmer",
      "ko" => "Korean",
      "ku" => "Kurdish",
      "ky" => "Kyrgyz",
      "lo" => "Lao",
      "la" => "Latin",
      "lv" => "Latvian",
      "ln" => "Lingala",
      "lt" => "Lithuanian",
      "mk" => "Macedonian",
      "ms" => "Malay",
      "ml" => "Malayalam",
      "mt" => "Maltese",
      "mr" => "Marathi",
      "mn" => "Mongolian",
      "ne" => "Nepali",
      "no" => "Norwegian",
      "nb" => "Norwegian Bokmål",
      "nn" => "Norwegian Nynorsk",
      "oc" => "Occitan",
      "or" => "Oriya",
      "om" => "Oromo",
      "ps" => "Pashto",
      "fa" => "Persian",
      "pl" => "Polish",
      "pt" => "Portuguese",
      "pt-BR" => "Portuguese (Brazil)",
      "pt-PT" => "Portuguese (Portugal)",
      "pa" => "Punjabi",
      "qu" => "Quechua",
      "ro" => "Romanian",
      "mo" => "Romanian (Moldova)",
      "rm" => "Romansh",
      "ru" => "Russian",
      "gd" => "Scottish Gaelic",
      "sr" => "Serbian",
      "sh" => "Serbo",
      "sn" => "Shona",
      "sd" => "Sindhi",
      "si" => "Sinhala",
      "sk" => "Slovak",
      "sl" => "Slovenian",
      "so" => "Somali",
      "st" => "Southern Sotho",
      "es" => "Spanish",
      "es-AR" => "Spanish (Argentina)",
      "es-419" => "Spanish (Latin America)",
      "es-MX" => "Spanish (Mexico)",
      "es-ES" => "Spanish (Spain)",
      "es-US" => "Spanish (United States)",
      "su" => "Sundanese",
      "sw" => "Swahili",
      "sv" => "Swedish",
      "tg" => "Tajik",
      "ta" => "Tamil",
      "tt" => "Tatar",
      "te" => "Telugu",
      "th" => "Thai",
      "ti" => "Tigrinya",
      "to" => "Tongan",
      "tr" => "Turkish",
      "tk" => "Turkmen",
      "tw" => "Twi",
      "uk" => "Ukrainian",
      "ur" => "Urdu",
      "ug" => "Uyghur",
      "uz" => "Uzbek",
      "vi" => "Vietnamese",
      "wa" => "Walloon",
      "cy" => "Welsh",
      "fy" => "Western Frisian",
      "xh" => "Xhosa",
      "yi" => "Yiddish",
      "yo" => "Yoruba",
      "zu" => "Zulu"
  );

  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $language = "";
  $postData = null;

  if(!empty($post->post_date) && !empty($post->post_modified)){
    if($post->post_date === $post->post_modified && $post->post_status != "auto-draft")
      set_transient("click5_history_log_polylang_post_translation_created",true);
  }

  if(isset($_SERVER['HTTP_REFERER'])){
    if(strpos($_SERVER['HTTP_REFERER'],"from_post") !== false){
      $parsedURL = parse_url($_SERVER["HTTP_REFERER"]);
      $urlQueryData = "";
      parse_str(parse_url($_SERVER["HTTP_REFERER"])['query'],$urlQueryData);

      if(isset($urlQueryData['from_post']))
        add_metadata("post",$post_id,"from_post",$urlQueryData['from_post'],true);
      if(isset($urlQueryData['new_lang']))
        add_metadata("post",$post_id, "ppl_language", $urlQueryData['new_lang'], true);
    }
  }

  if(isset($_REQUEST['post']) && isset($_REQUEST['post_tr_lang'])){
    $defaultLang = get_option("polylang")['default_lang'];
    $corePostID = $translations[$defaultLang];
    if(!empty($corePostID)){
      $postData = get_post($corePostID);
    }else{
      $postData = get_post(get_metadata("post",$post_id,"from_post")[0]);
    }
  }else if(is_plugin_active("classic-editor/classic-editor.php") && isset($_REQUEST['action']) && $_REQUEST['action'] == "editpost"){
    $defaultLang = get_option("polylang")['default_lang'];
    $corePostID = $translations[$defaultLang];
    if(!empty($corePostID)){
      $postData = get_post($corePostID);
    }else{
      $postData = get_post(get_metadata("post",$post_id,"from_post")[0]);
    }
  }
  
  if($post->post_status != "auto-draft" && !is_null($postData)){
    $type = $postData->post_type;
    $language = array_search($post_id,$translations);
    $postTitle = $postData->post_title;
    $isCorePost = false;

    if(isset($languages_list[$language]))
      $language = $languages_list[$language];
    else
      $language = strtoupper($language);

    if($postData->ID == $post->ID)
      $isCorePost = true;

    if(!$isCorePost){
      if(!empty($post->post_date) && (get_transient("click5_history_log_polylang_post_translation_created") === true || get_transient("click5_history_log_polylang_post_translation_created") == "1")){
        $wpdb->insert($table_name, array('description'=>'<b>'.$language.'</b> translation has been created for '.$type." <b>".$postTitle."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
        delete_transient("click5_history_log_polylang_post_translation_created");
      }
      else if(!empty($post->post_date) && $_REQUEST['action'] == "editpost"){
        $wpdb->insert($table_name, array('description'=>'<b>'.$language.'</b> translation for '.$type." <b>".$postTitle."</b> has been modified",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
        delete_transient("click5_history_log_polylang_post_translation_created");
      }
    }
  }

  },10,3);

  add_action( "edited_post_translations", function( $term_id,  $tt_id){
    $languages_list = array(
      "af" => "Afrikaans",
      "sq" => "Albanian",
      "am" => "Amharic",
      "ar" => "Arabic",
      "an" => "Aragonese",
      "hy" => "Armenian",
      "ast" => "Asturian",
      "az" => "Azerbaijani",
      "eu" => "Basque",
      "be" => "Belarusian",
      "bn" => "Bengali",
      "bs" => "Bosnian",
      "br" => "Breton",
      "bg" => "Bulgarian",
      "ca" => "Catalan",
      "ckb" => "Central Kurdish",
      "zh" => "Chinese",
      "zh-HK" => "Chinese (Hong Kong)",
      "zh-CN" => "Chinese (Simplified)",
      "zh-TW" => "Chinese (Traditional)",
      "co" => "Corsican",
      "hr" => "Croatian",
      "cs" => "Czech",
      "da" => "Danish",
      "nl" => "Dutch",
      "en" => "English",
      "en-AU" => "English (Australia)",
      "en-CA" => "English (Canada)",
      "en-IN" => "English (India)",
      "en-NZ" => "English (New Zealand)",
      "en-ZA" => "English (South Africa)",
      "en-GB" => "English (United Kingdom)",
      "en-US" => "English (United States)",
      "eo" => "Esperanto",
      "et" => "Estonian",
      "fo" => "Faroese",
      "fil" => "Filipino",
      "fi" => "Finnish",
      "fr" => "French",
      "fr-CA" => "French (Canada)",
      "fr-FR" => "French (France)",
      "fr-CH" => "French (Switzerland)",
      "gl" => "Galician",
      "ka" => "Georgian",
      "de" => "German",
      "de-AT" => "German (Austria)",
      "de-DE" => "German (Germany)",
      "de-LI" => "German (Liechtenstein)",
      "de-CH" => "German (Switzerland)",
      "el" => "Greek",
      "gn" => "Guarani",
      "gu" => "Gujarati",
      "ha" => "Hausa",
      "haw" => "Hawaiian",
      "he" => "Hebrew",
      "hi" => "Hindi",
      "hu" => "Hungarian",
      "is" => "Icelandic",
      "id" => "Indonesian",
      "ia" => "Interlingua",
      "ga" => "Irish",
      "it" => "Italian",
      "it-IT" => "Italian (Italy)",
      "it-CH" => "Italian (Switzerland)",
      "ja" => "Japanese",
      "kn" => "Kannada",
      "kk" => "Kazakh",
      "km" => "Khmer",
      "ko" => "Korean",
      "ku" => "Kurdish",
      "ky" => "Kyrgyz",
      "lo" => "Lao",
      "la" => "Latin",
      "lv" => "Latvian",
      "ln" => "Lingala",
      "lt" => "Lithuanian",
      "mk" => "Macedonian",
      "ms" => "Malay",
      "ml" => "Malayalam",
      "mt" => "Maltese",
      "mr" => "Marathi",
      "mn" => "Mongolian",
      "ne" => "Nepali",
      "no" => "Norwegian",
      "nb" => "Norwegian Bokmål",
      "nn" => "Norwegian Nynorsk",
      "oc" => "Occitan",
      "or" => "Oriya",
      "om" => "Oromo",
      "ps" => "Pashto",
      "fa" => "Persian",
      "pl" => "Polish",
      "pt" => "Portuguese",
      "pt-BR" => "Portuguese (Brazil)",
      "pt-PT" => "Portuguese (Portugal)",
      "pa" => "Punjabi",
      "qu" => "Quechua",
      "ro" => "Romanian",
      "mo" => "Romanian (Moldova)",
      "rm" => "Romansh",
      "ru" => "Russian",
      "gd" => "Scottish Gaelic",
      "sr" => "Serbian",
      "sh" => "Serbo",
      "sn" => "Shona",
      "sd" => "Sindhi",
      "si" => "Sinhala",
      "sk" => "Slovak",
      "sl" => "Slovenian",
      "so" => "Somali",
      "st" => "Southern Sotho",
      "es" => "Spanish",
      "es-AR" => "Spanish (Argentina)",
      "es-419" => "Spanish (Latin America)",
      "es-MX" => "Spanish (Mexico)",
      "es-ES" => "Spanish (Spain)",
      "es-US" => "Spanish (United States)",
      "su" => "Sundanese",
      "sw" => "Swahili",
      "sv" => "Swedish",
      "tg" => "Tajik",
      "ta" => "Tamil",
      "tt" => "Tatar",
      "te" => "Telugu",
      "th" => "Thai",
      "ti" => "Tigrinya",
      "to" => "Tongan",
      "tr" => "Turkish",
      "tk" => "Turkmen",
      "tw" => "Twi",
      "uk" => "Ukrainian",
      "ur" => "Urdu",
      "ug" => "Uyghur",
      "uz" => "Uzbek",
      "vi" => "Vietnamese",
      "wa" => "Walloon",
      "cy" => "Welsh",
      "fy" => "Western Frisian",
      "xh" => "Xhosa",
      "yi" => "Yiddish",
      "yo" => "Yoruba",
      "zu" => "Zulu"
  );

    global $gCurrentUser;
    global $wpdb;
    $defaultLang = get_option("polylang")['default_lang'];
    $table_name = $wpdb->prefix . "c5_history";
    $currentLanguage = $GLOBALS['polylang']->curlang->slug;
    $language = "";

    
    if(isset($languages_list[$currentLanguage]))
      $language = $languages_list[$currentLanguage];
    else
      $language = strtoupper($currentLanguage);

    if($_REQUEST['action'] == "delete"){
      $postData = get_post(get_metadata("post",$_REQUEST['post'],"from_post")[0]);
      $postTitle = $postData->post_title;
      $type = $postData->post_type;
      $wpdb->insert($table_name, array('description'=>'<b>'.$language.'</b> translation for '.$type." <b>".$postTitle."</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
    }

  },10,2 );
}

add_action('wp_ajax_limit-login-unlock',function(){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(isset($_REQUEST['action']) && $_REQUEST['action'] == "limit-login-unlock"){
    if(isset($_REQUEST['ip'])){
      $wpdb->insert($table_name, array('description'=>"Login for user by IP <b>".$_REQUEST['ip']."</b> has been unlocked",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Limit Login', 'user'=>$gCurrentUser));
    }
  }
});

///BACK WP UP

if(esc_attr(get_option("click5_history_log_backwpup/backwpup.php")) === "1"){
  add_action('admin_post_backwpup',function(){
    if(isset($_REQUEST['page']) && $_REQUEST['page'] === "backwpupsettings") return;
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(strpos($_REQUEST['_wp_http_referer'],"_wpnonce") !== false || strpos($_REQUEST['_wp_http_referer'],"jobid") !== false){
      if(!empty($_REQUEST['_wpnonce']) && isset($_REQUEST['name'])){
        if(!empty($_REQUEST['name']))
        $wpdb->insert($table_name, array('description'=>"Job <b>".$_REQUEST['name']."</b> has been edited",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>$gCurrentUser));
      }
    }else{
      $wpdb->insert($table_name, array('description'=>"New job <b>".$_REQUEST['name']."</b> has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>$gCurrentUser));
    }
  });

  add_filter('backwpup_admin_pages',function($hooks){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(isset($_REQUEST['page'])){
      if($_REQUEST['page'] == "backwpupbackups"){
        if(class_exists('BackWPup_Option')){
          if(isset($_REQUEST['action'])){
            if($_REQUEST['action'] == "delete" && !empty($_REQUEST['backupfiles'])){
              foreach($_REQUEST['backupfiles'] as $backup){
                $lastOccurence = strripos($backup,"/");
                $bckpName = substr($backup,$lastOccurence+1);
                $wpdb->insert($table_name, array('description'=>"Backup <b>".$bckpName."</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>$gCurrentUser));
              }
            }
          }
        }
      }else{
        if(class_exists('BackWPup_Option')){
          if(isset($_REQUEST['action'])){
            if($_REQUEST['action'] == "delete"){
              foreach($_REQUEST['jobs'] as $jobID){
                $jobName = BackWPup_Option::get( $jobID, 'name' );
                $wpdb->insert($table_name, array('description'=>"Job <b>".$jobName."</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>$gCurrentUser));
              }
            }
          }
        }
      }
    }
  });

  add_action("wp_ajax_download_backup_file",function(){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(class_exists('BackWPup_Option')){
      if(isset($_REQUEST['file'])){
        $lastOccurence = strripos($_REQUEST['file'],"/");
        $bckpName = substr($_REQUEST['file'],$lastOccurence+1);
        if(get_transient($bckpName) === false){
          $wpdb->insert($table_name, array('description'=>"Backup <b>".$bckpName."</b> has been downloaded",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>$gCurrentUser));
          set_transient($bckpName,1,10);
        }    
      }
    }
  });
}

///BACK WP UP END


function click5_parse_user_agent( $u_agent = null ) {

  define("OS",'os');
  define("BROWSER",'browser');
  define("BROWSER_VERSION",'version');
  
  if( $u_agent === null && isset($_SERVER['HTTP_USER_AGENT']) ) {
    $u_agent = (string)$_SERVER['HTTP_USER_AGENT'];
  }

  $os = null;
  $browser  = null;
  $version  = null;

  if( !$u_agent ) {
    return array( OS => $os, BROWSER => $browser );
  }

  if( preg_match('/\((.*?)\)/m', $u_agent, $parent_matches) ) {
    preg_match_all(<<<'REGEX'
/(?P<os>BB\d+;|Android|Adr|Symbian|CrOS|Tizen|iPhone|iPad|iPod|Linux|(Open|Net|Free)BSD|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|X11|(New\ )?Nintendo\ (WiiU?|3?DS|Switch)|Xbox(\ One)?)
(?:\ [^;]*)?
(?:;|$)/imx
REGEX
      , $parent_matches[1], $result);

    $avaible = array( 'Xbox One', 'Xbox', 'Windows Phone', 'Tizen', 'Android', 'FreeBSD', 'NetBSD', 'OpenBSD', 'CrOS', 'X11' );

    $result[OS] = array_unique($result[OS]);
    if( count($result[OS]) > 1 ) {
      if( $keys = array_intersect($avaible, $result[OS]) ) {
        $os = reset($keys);
      } else {
        $os = $result[OS][0];
      }
    } elseif( isset($result[OS][0]) ) {
      $os = $result[OS][0];
    }
  }

  $osList = array(
    'linux-gnu'  => "Linux",
    'X11'  => "Linux",
    'CrOS'  => "Chrome OS",
    'Adr'  => "Android",
  );

  if(isset($osList[$os]))
    $os = $osList[$os];

  preg_match_all(<<<'REGEX'
%(?P<browser>Camino|Kindle(\ Fire)?|Firefox|Iceweasel|IceCat|Safari|MSIE|Trident|AppleWebKit|
TizenBrowser|(?:Headless)?Chrome|YaBrowser|Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|Edg|CriOS|UCBrowser|Puffin|OculusBrowser|SamsungBrowser|
Baiduspider|Applebot|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|
Valve\ Steam\ Tenfoot|
NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
(?:\)?;?)
(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix
REGEX
    , $u_agent, $result);

  // If nothing matched, return null (to avoid undefined index errors)
  if( !isset($result[BROWSER][0]) || !isset($result[BROWSER_VERSION][0]) ) {
    if( preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?%ix', $u_agent, $result) ) {
      return array( OS => $os ?: null, BROWSER => $result[BROWSER]);
    }

    return array( OS => $os, BROWSER => $browser );
  }

  if( preg_match('/rv:(?P<version>[0-9A-Z.]+)/i', $u_agent, $rv_result) ) {
    $rv_result = $rv_result[BROWSER_VERSION];
  }

  $browser = $result[BROWSER][0];
  $version = $result[BROWSER_VERSION][0];

  $lBrowser = array_map('strtolower', $result[BROWSER]);

  $find = function ( $search, &$key = null, &$value = null ) use ( $lBrowser ) {
    $search = (array)$search;

    foreach( $search as $val ) {
      $xkey = array_search(strtolower($val), $lBrowser);
      if( $xkey !== false ) {
        $value = $val;
        $key   = $xkey;

        return true;
      }
    }

    return false;
  };

  $findT = function ( array $search, &$key = null, &$value = null ) use ( $find ) {
    $value2 = null;
    if( $find(array_keys($search), $key, $value2) ) {
      $value = $search[$value2];

      return true;
    }

    return false;
  };

  $key = 0;
  $val = '';
  if( $findT(array( 'OPR' => 'Opera', 'UCBrowser' => 'UC Browser', 'YaBrowser' => 'Yandex', 'Iceweasel' => 'Firefox', 'Icecat' => 'Firefox', 'CriOS' => 'Chrome', 'Edg' => 'Edge' ), $key, $browser) ) {
    $version = $result[BROWSER_VERSION][$key];
  } elseif( $find('Playstation Vita', $key, $os) ) {
    $os = 'PlayStation Vita';
    $browser  = 'Browser';
  } elseif( $find(array( 'Kindle Fire', 'Silk' ), $key, $val) ) {
    $browser  = $val == 'Silk' ? 'Silk' : 'Kindle';
    $os = 'Kindle Fire';
    if( !($version = $result[BROWSER_VERSION][$key]) || !is_numeric($version[0]) ) {
      $version = $result[BROWSER_VERSION][array_search('Version', $result[BROWSER])];
    }
  } elseif( $find('NintendoBrowser', $key) || $os == 'Nintendo 3DS' ) {
    $browser = 'NintendoBrowser';
    $version = $result[BROWSER_VERSION][$key];
  } elseif( $find('Kindle', $key, $os) ) {
    $browser = $result[BROWSER][$key];
    $version = $result[BROWSER_VERSION][$key];
  } elseif( $find('Opera', $key, $browser) ) {
    $find('Version', $key);
    $version = $result[BROWSER_VERSION][$key];
  } elseif( $find('Puffin', $key, $browser) ) {
    $version = $result[BROWSER_VERSION][$key];
    if( strlen($version) > 3 ) {
      $part = substr($version, -2);
      if( ctype_upper($part) ) {
        $version = substr($version, 0, -2);

        $flags = array( 'IP' => 'iPhone', 'IT' => 'iPad', 'AP' => 'Android', 'AT' => 'Android', 'WP' => 'Windows Phone', 'WT' => 'Windows' );
        if( isset($flags[$part]) ) {
          $os = $flags[$part];
        }
      }
    }
  } elseif( $find(array( 'Applebot', 'IEMobile', 'Edge', 'Midori', 'Vivaldi', 'OculusBrowser', 'SamsungBrowser', 'Valve Steam Tenfoot', 'Chrome', 'HeadlessChrome' ), $key, $browser) ) {
    $version = $result[BROWSER_VERSION][$key];
  } elseif( $rv_result && $find('Trident') ) {
    $browser = 'MSIE';
    $version = $rv_result;
  } elseif( $browser == 'AppleWebKit' ) {
    if( $os == 'Android' ) {
      $browser = 'Android Browser';
    } elseif( strpos($os, 'BB') === 0 ) {
      $browser  = 'BlackBerry Browser';
      $os = 'BlackBerry';
    } elseif( $os == 'BlackBerry' || $os == 'PlayBook' ) {
      $browser = 'BlackBerry Browser';
    } else {
      $find('Safari', $key, $browser) || $find('TizenBrowser', $key, $browser);
    }

    $find('Version', $key);
    $version = $result[BROWSER_VERSION][$key];
  } elseif( $pKey = preg_grep('/playstation \d/i', $result[BROWSER]) ) {
    $pKey = reset($pKey);

    $os = 'PlayStation ' . preg_replace('/\D/', '', $pKey);
    $browser  = 'NetFront';
  }

  return array( OS => $os ?: null, BROWSER => $browser ?: null);
}

add_action( 'template_redirect', function(){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $excludedBrowser = array(
    "googlebot",
    "bingbot",
    "go-http-client",
    "yandexbot",
    'semrushbot',
    "ccbot",
    "barkrowler",
    "mj12bot",
    "applebot",
    "mediapartners-google",
    'dataforseobot',
    'grapeshotcrawler',
    'ioncrawl',
    'criteobot',
    'seokicks',
    'seznambot',
    'netestate',
    'netestate ne crawler'

  );
  $userAgent = click5_parse_user_agent();
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $siteUrl = $protocol.$_SERVER['HTTP_HOST'];
  $referer = "n/a";
  if(isset($_SERVER['REDIRECT_URL'])){
    $siteUrl .= $_SERVER['REDIRECT_URL'];
  }
  else if(isset($_SERVER['REQUEST_URI'])){
    $siteUrl .= $_SERVER['REQUEST_URI'];
  }

  if(in_array(strtolower($userAgent['browser']),$excludedBrowser) || 
      strpos($siteUrl,"/wp-content") !== false || 
      strpos($siteUrl,"/wp-admin") !== false ||
      strpos($siteUrl,"/wp-includes") !== false){
    return;
  }
  if(strlen($siteUrl) > 200)
    $siteUrl = substr($siteUrl,0,200)."[...]";

  if(is_404()){
    if(isset($_SERVER['HTTP_REFERER'])){
      $referer = $_SERVER['HTTP_REFERER'];
    }

    if(strpos($referer,"sucuri.net") !== false && $referer != "n\a"){
      return;
    }

      if(strlen($referer) > 200)
        $referer = substr($referer,0,200)."[...]";
      global $wp;
      $current_url = home_url( add_query_arg( array(), $wp->request ) );
      if(esc_attr(get_option("click5_history_log_module_404_error")) === "1"){
        if(!empty($userAgent['os']) && !empty($userAgent['browser']) && $referer != "n/a" && strpos($referer,"xmlrpc.php") === false){
          $wpdb->insert($table_name, array('description'=>"<b>404 Path: </b>".$siteUrl." <br><b>From: </b>".$referer."</b><br><b>User Device: </b>".$userAgent['os']." ".$userAgent['browser'],'date'=>date('Y-m-d H:i:s'), 'plugin'=>'404 Errors', 'user'=>"WordPress Core"));

          if(esc_attr(get_option("click5_history_log_404")) == "1"){
            if(get_transient("click5_history_log_mail_transient_404") === false){
              do_action("click5_history_log_alerts_email");
              set_transient("click5_history_log_mail_transient_404","1",70);
            }
          }
        }
      }
  }
});


add_action("string_locator_post_save_action",function($save_result, $params){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  // file filepatch has been edited
  if(esc_attr(get_option("click5_history_log_string-locator/string-locator.php")) === "1"){
    if(isset($save_result['notices'][0]['type'])){
      if($save_result['notices'][0]['type'] === "success"){
        if(isset($params['string-locator-path'])){
          $path = $params['string-locator-path'];
          $path = str_replace("\\\\","\\",$path);
          $path = str_replace(str_replace("/","\\",ABSPATH),"",$path);
          $wpdb->insert($table_name, array('description'=>"File <b>$path</b> has been edited",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'String Locator', 'user'=>$gCurrentUser));
        }
      }
    }
  }
  },10,2);

add_filter("string_locator_directory_iterator_short_circuit",function($request){
return $request;
});

add_action("string_locator_directory_iterator_short_circuit",function($short_circuit, $request){
global $gCurrentUser;
global $wpdb;
$table_name = $wpdb->prefix . "c5_history";
if(esc_attr(get_option("click5_history_log_string-locator/string-locator.php")) === "1"){
  if(is_a($request,"WP_REST_Request")){
    if(isset($request->get_params()['data']) && !empty($request->get_params()['data'])){
      $data = json_decode($request->get_params()['data']);
      if(isset($data->directory) && !empty($data->directory)){
        $dir = $data->directory;
        if(strpos($dir,"t-") === 0){
          $content = str_replace("t-","",$dir);
          $search = empty($data->search) === true ? "empty" : $data->search;
          $searchIn = "All themes";
          $suffix = "";
          if($content !== "-"){
            $searchIn = wp_get_theme($content)->get("Name");
            $suffix = "theme";
          }
          $wpdb->insert($table_name, array('description'=>"String <b>$search</b> has been searched in <b>$searchIn</b> $suffix",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'String Locator', 'user'=>$gCurrentUser));
        }else if(strpos($dir,"p-") === 0){
          $content = str_replace("p-","",$dir);
          $search = empty($data->search) === true ? "empty" : $data->search;
          $searchIn = "All plugins";
          $plugins = get_plugins();
          $suffix = "";
          if($content !== "-"){
            foreach($plugins as $name => $plugin_content){
              if($name === $content){
                $searchIn = $plugin_content['Name'];
                $suffix = "plugin";
              }
            }
          }
          $wpdb->insert($table_name, array('description'=>"String <b>$search</b> has been searched in <b>$searchIn</b> $suffix",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'String Locator', 'user'=>$gCurrentUser));
        }else{
          $search = empty($data->search) === true ? "empty" : $data->search;
          switch($dir){
            case "core":
              $searchIn = "The whole WordPress directory";
              break;
            case "wp-content":
              $searchIn = "Everything under wp-content";
              break;
            case "sql":
              $searchIn = "All database tables";
              break;
          }

          $wpdb->insert($table_name, array('description'=>"String <b>$search</b> has been searched in <b>$searchIn</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'String Locator', 'user'=>$gCurrentUser));
        }
      }
    }
  }
}
},10,2);

//WP Mail Log - LOGS START //
if(esc_attr(get_option("click5_history_log_wp-mail-log/wp-mail-log.php")) == "1" && is_plugin_active('wp-mail-log/wp-mail-log.php')){
  add_filter('wp_mail',function($args){
    global $wpdb;
    global $gCurrentUser;
    $usr = $gCurrentUser;
    if(is_null($usr) || empty($usr))
      $usr = 'WordPress Core';

    $table_name = $wpdb->prefix . "c5_history";
    $fromMail = '';
    if(empty($args['headers'])){
      $fromMail .= "WordPress Core";
    }else{
      $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
      $mailTemp = null;
      $arg_headers =  $args['headers'];
      if(is_array($arg_headers) || is_object($arg_headers)){
        $arg_headers['headers'] = implode(";",$arg_headers);
      }
      if(isset($arg_headers['headers'])){
        preg_match_all($pattern, $arg_headers['headers'], $mailTemp);
      }else{
        preg_match_all($pattern, $arg_headers, $mailTemp);
      }
      
      if(isset($arg_headers['headers'])){
        if(!empty($mailTemp)){
          if(strpos($arg_headers['headers'],'Reply-To') !== false){
            $fromMail .= $mailTemp[0][1];
          }else{
            $fromMail .= isset($mailTemp[0][0]) ? $mailTemp[0][0] : "WordPress Core";
          }
        }else{
          $fromMail .= "WordPress Core";
        }
      }else{
        if(!empty($mailTemp)){
          if(strpos($arg_headers,'Reply-To') !== false){
            $fromMail .= $mailTemp[0][1];
          }else{
            $fromMail .= isset($mailTemp[0][0]) ? $mailTemp[0][0] : "WordPress Core";
          }
        }else{
          $fromMail .= "WordPress Core";
        }
      }
    }

    if(empty($fromMail))
      $fromMail = 'WordPress Core';

    $wpdb->insert($table_name, array('description'=>'New Email from <b>'.$fromMail.'</b> has been logged','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WP Mail Log', 'user'=>$usr));
    return $args;
  });
  
  add_filter("rest_request_after_callbacks",function($response, $handler, $request){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $fromMail = "";
    if(strpos($request->get_route(),'/wml_logs/send_mail') !== false){
      if($request->get_params()['type'] === "resend"){
        $q = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wml_entries WHERE `id` = ".$request->get_params()['id']);
        $result = $wpdb->get_results($q)[0];
        $headers = explode("\n",$result->headers);
        $mailTemp = array();
        /*foreach($headers as $header){
          if(strpos($header,'Reply-To:') !== false){
            $fromMail = str_replace("Reply-To:",'',$header);
            $fromMail = str_replace(' ','',$fromMail);
            break;
          }
        }*/
        $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
          preg_match_all($pattern, $result->headers, $mailTemp);
          if(!empty($mailTemp)){
            $mailTemp = $mailTemp[0];
            if(is_array($mailTemp) && !empty($mailTemp)){
              if(count($mailTemp) > 1)
                $fromMail .= "<b>".$mailTemp[1]."</b>";
              else
                $fromMail .= "<b>".$mailTemp[0]."</b>";
            }
              
          }

        if(empty($fromMail) && strpos($result->headers,'Reply-To:') === false && strpos($result->headers,'From:') === false)
          $fromMail .= "<b>".get_bloginfo('admin_email')."</b>";
       $wpdb->insert($table_name, array('description'=>'Email from <b>'.$fromMail.'</b> (ID: '.$request->get_params()['id'].') has been resent','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WP Mail Log', 'user'=>$gCurrentUser));
      }else if($request->get_params()['type'] === "forward"){
        $q = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wml_entries WHERE `id` = ".$request->get_params()['id']);
        $result = $wpdb->get_results($q)[0];
        $headers = explode("\n",$result->headers);
        $mailTemp = array();
        /*foreach($headers as $header){
          if(strpos($header,'Reply-To:') !== false){
            $fromMail = str_replace("Reply-To:",'',$header);
            $fromMail = str_replace(' ','',$fromMail);
            break;
          }
        }*/
        $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
        preg_match_all($pattern, $result->headers, $mailTemp);
        if(!empty($mailTemp)){
          $mailTemp = $mailTemp[0];
          if(is_array($mailTemp) && !empty($mailTemp)){
            if(count($mailTemp) > 1)
              $fromMail .= "<b>".$mailTemp[1]."</b>";
            else
              $fromMail .= "<b>".$mailTemp[0]."</b>";
          }
            
        }

        if(empty($fromMail) && strpos($result->headers,'Reply-To:') === false && strpos($result->headers,'From:') === false)
          $fromMail .= "<b>".get_bloginfo('admin_email')."</b>";

        $wpdb->insert($table_name, array('description'=>'Email from <b>'.$fromMail.'</b> (ID: '.$request->get_params()['id'].') has been forwarded to <b>'.$request->get_params()['to_email'].'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WP Mail Log', 'user'=>$gCurrentUser));
      }
        
      }
    return $response;
  },10,3);

  add_filter("rest_request_before_callbacks",function($response, $handler, $request){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $fromMail = "";
    if(strpos($request->get_route(),'/wml_logs/delete') !== false){
      $deletedMails = $request->get_params();
      $mails = '';
      $count = 'Email';
      $id_text = 'ID:';
      $multi = "has";
      if(is_array($deletedMails)){
        foreach($deletedMails as $mail){
          $mails .= $mail.",";
        }

        $ids = substr($mails,0,-1);
        if(count($deletedMails) > 1){
          $count = "Emails";
          $multi = "have";
        }
         
        else{
          $mails = str_replace(',','',$mails);
        }
        $q = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wml_entries WHERE `id` IN (".$ids.")");
        $results = $wpdb->get_results($q);
        $fromMail = '';
        $mailTemp = array();
        foreach($results as $result){
          $headers = explode("\n",$result->headers);
          /*foreach($headers as $header){
            if(strpos($header,'Reply-To:') !== false){
              $header = str_replace("Reply-To:",'',$header);
              $header = str_replace(' ','',$header);
              $fromMail .= "<b>".$header."</b>(".$id_text." ".$result->id."), ";
              break;
            }else  if(strpos($header,'From:') !== false){
              $fromMail .= "<b>".$header."</b>(".$id_text." ".$result->id."), ";
              break;
          }*/
          $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
          preg_match_all($pattern, $result->headers, $mailTemp);
          if(!empty($mailTemp)){
            $mailTemp = $mailTemp[0];
            if(is_array($mailTemp) && !empty($mailTemp)){
              if(count($mailTemp) > 1)
                $fromMail .= "<b>".$mailTemp[1]."</b>(".$id_text." ".$result->id."), ";
              else
                $fromMail .= "<b>".$mailTemp[0]."</b>(".$id_text." ".$result->id."), ";
            }
              
          }

          if(strpos($result->headers,'Reply-To:') === false && strpos($result->headers,'From:') === false)
            $fromMail .= "<b>".get_bloginfo('admin_email')."</b>(".$id_text." ".$result->id."), ";
            
        }
        $fromMail = substr($fromMail,0,-2);
          
        if(!empty($mails)){
          $wpdb->insert($table_name, array('description'=>$count.' from '.$fromMail.' '.$multi.' been deleted','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WP Mail Log', 'user'=>$gCurrentUser));
        }
      }
    }
  return $response;
  },10,3);
}


//WP Mail Log  - LOGS END //

add_action( 'update_option', function($option, $old_value, $value){
  if(strpos($option, 'home') !== false && $old_value != $value) {  
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(!is_array($value)){
      if(esc_attr(get_option("click5_history_log_module_settings")) === "1")
        $wpdb->insert($table_name, array('description'=>'Site address (URL) email address has been changed to <b>' .  $value . '</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
    }
  }

  if(esc_attr(get_option('click5_history_log_history-log-by-click5/history-log-by-click5.php')) == "1"){
    if(strpos($option, 'click5_history_log_critical_error') !== false){
      global $gCurrentUser;
      if(is_null($gCurrentUser))
        $gCurrentUser = $GLOBALS['current_user']->data->user_login;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value != $old_value){
        $mode = "";
        if(intval($value))
          $mode = "enabled";
        else
          $mode = "disabled";

        $wpdb->insert($table_name, array('description'=>'<b>Email alerts</b> about <b>Critical Errors</b> have been '.$mode,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
      }
        
    }

    if(strpos($option, 'click5_history_log_email_template') !== false){
      global $gCurrentUser;
      if(is_null($gCurrentUser))
        $gCurrentUser = $GLOBALS['current_user']->data->user_login;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value != $old_value){
        if($value == "plain")
          $wpdb->insert($table_name, array('description'=>'<b>Email alert format</b> has been changed to <b>Plain Text</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
        else if($value == "html")
        $wpdb->insert($table_name, array('description'=>'<b>Email alert format</b> has been changed to <b>HTML</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
      }
    }

    if(strpos($option, 'click5_history_log_technical_issue') !== false){
      global $gCurrentUser;
      if(is_null($gCurrentUser))
        $gCurrentUser = $GLOBALS['current_user']->data->user_login;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value != $old_value){
        $mode = "";
        if(intval($value))
          $mode = "enabled";
        else
          $mode = "disabled";

        $wpdb->insert($table_name, array('description'=>'<b>Email alerts</b> about <b>Technical Issues</b> have been '.$mode,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
      }
    }

    if(strpos($option, 'click5_history_log_alert_email') !== false){
      global $gCurrentUser;
      if(is_null($gCurrentUser))
        $gCurrentUser = $GLOBALS['current_user']->data->user_login;
      global $wpdb;
      $address = "address";
      $p = "has";
      $table_name = $wpdb->prefix . "c5_history";
      if($value != $old_value){
        $val = $value;
        if(empty($val))
          $val = "empty";
        if(count(explode(",",$value)) > 1){
          $address = "addresses";
          $p = "have";
        }
          

        $wpdb->insert($table_name, array('description'=>'<b>Alerts email '.$address.'</b> '.$p.' been changed to <b>'.$val.'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
      }
    }

    if(strpos($option, 'click5_history_log_404') !== false){
      global $gCurrentUser;
      if(is_null($gCurrentUser))
        $gCurrentUser = $GLOBALS['current_user']->data->user_login;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value != $old_value){
        $mode = "disabled";
        if($value === 1 || $value === "1"){
          $mode = "enabled";
        }
        $wpdb->insert($table_name, array('description'=>'<b>Email alerts</b> about <b>404 Errors</b> alert have been '.$mode,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$gCurrentUser));
      }
    }
  }

  if(strpos($option,'sb') !== false){
    $settings_description = array(
    'sb_instagram_at'                   => '',
    'sb_instagram_user_id'              => '',
    'sb_instagram_preserve_settings'    => 'Preserve settings if plugin is removed',
    'sb_instagram_ajax_theme'           => 'AJAX theme loading fix',
    'sb_instagram_disable_resize'       => 'Optimize Images',
    'sb_instagram_cache_time'           => 'Caching time',
    'sb_instagram_cache_time_unit'      => 'Caching time',
    'sbi_caching_type'                  => 'Caching time',
    'sbi_cache_cron_interval'           => 'Caching time',
    'sbi_cache_cron_time'               => 'Caching time',
    'sbi_cache_cron_am_pm'              => 'Caching time',
    'sb_instagram_width'                => '', 
    'sb_instagram_width_unit'           => '',
    'sb_instagram_feed_width_resp'      => '',
    'sb_instagram_height'               => '',
    'sb_instagram_num'                  => '', 
    'sb_instagram_height_unit'          => '', 
    'sb_instagram_cols'                 => '', 
    'sb_instagram_disable_mobile'       => '', 
    'sb_instagram_image_padding'        => '', 
    'sb_instagram_image_padding_unit'   => '',
    'sb_instagram_sort'                 => '', 
    'sb_instagram_background'           => '', 
    'sb_instagram_show_btn'             => '', 
    'sb_instagram_btn_background'       => '', 
    'sb_instagram_btn_text_color'       => '', 
    'sb_instagram_btn_text'             => '', 
    'sb_instagram_image_res'            => '', 
    'sb_instagram_show_header'          => '', 
    'sb_instagram_header_size'          => '', 
    'sb_instagram_header_color'         => '', 
    'sb_instagram_show_follow_btn'      => '', 
    'sb_instagram_folow_btn_background' => '', 
    'sb_instagram_follow_btn_text_color'=> '', 
    'sb_instagram_follow_btn_text'      => '', 
    'sb_instagram_custom_css'           => 'Custom CSS', 
    'sb_instagram_custom_js'            => 'Custom JS', 
    'sb_instagram_cron'                 => '', 
    'sb_instagram_backup'               => '', 
    'sb_ajax_initial'                   => 'Load Initial Posts with AJAX', 
    'enqueue_css_in_shortcode'          => 'Enqueue CSS only on pages with the Feed', 
    'sb_instagram_disable_mob_swipe'    => '', 
    'sb_instagram_disable_awesome'      => '', 
    'gdpr'                              => 'GDPR', 
    'enqueue_js_in_head'                => 'Enqueue JavaScript in head', 
    'disable_js_image_loading'          => 'JavaScript Image Loading', 
    'disable_admin_notice'              => 'Admin Error Notice', 
    'enable_email_report'               => 'Feed Issue Email Reports',
    'sbi_usage_tracking'                => 'Usage Tracking'
    );
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    if(strpos($option,'instagram_settings') !== false){

      foreach($value as $name => $setting_val){
        if($value[$name] != $old_value[$name]){
          $setting_name = $name;
          $setting_name = str_replace("sb_instagram_","",$setting_name);
          $setting_name = str_replace("_"," ",$setting_name);
          $setting_name = ucwords($setting_name);
          if(!empty($settings_description[$name])){
            $setting_name = $settings_description[$name];
          }
          if(($value[$name] === true || $value[$name] == "1")){
            if(strpos($name,'disable') !== false){
              $wpdb->insert($table_name, array('description'=>"Setting <b>".$setting_name."</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
            }else{
              $wpdb->insert($table_name, array('description'=>"Setting <b>".$setting_name."</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
            }

          }else if(($value[$name] === false || $value[$name] == "") && ($setting_name != "Custom CSS" && $setting_name != "Custom JS")){
            $setting_name = null;
            if(!empty($settings_description[$name])){
              $setting_name = $settings_description[$name];
            }
            if(strpos($name,'disable') !== false){
              $wpdb->insert($table_name, array('description'=>"Setting <b>".$setting_name."</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
            }else{
              $wpdb->insert($table_name, array('description'=>"Setting <b>".$setting_name."</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
            }
          }else if(is_string($value[$name])){
              $setting_name = null;
              if(!empty($settings_description[$name])){
                $setting_name = $settings_description[$name];
              }
              if($name == "gdpr"){
                $wpdb->insert($table_name, array('description'=>"Setting <b>".$setting_name."</b> has been changed to ".ucfirst($value[$name]),'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
              }else if(!empty($setting_name)){
                $wpdb->insert($table_name, array('description'=>"Setting <b>".$setting_name."</b> has been changed",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
              }
          }
        }
      }
    }else if(strpos($option,'sbi_usage_tracking') !== false){
      $setting_name = $settings_description['sbi_usage_tracking'];
      if($value['enabled'] === true || $value['enabled'] == "1"){
        $wpdb->insert($table_name, array('description'=>"Setting <b>".$setting_name."</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
      }else if($value['enabled'] === false || $value['enabled'] == ""){
        $wpdb->insert($table_name, array('description'=>"Setting <b>".$setting_name."</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>"Smash Balloon Instagram Feed", 'user'=>$gCurrentUser));
      }
    }
  }

  if(strpos($option,'widget_block') !== false){
    if(!is_plugin_active("classic-widgets/classic-widgets.php")){
      if(count($value) > count($old_value)){
        $name = array_values($value)[count($value)-2];
        $start_index = strpos($name['content'],"wp:")+3;
        $end_index = strpos($name['content']," ",$start_index);
        $len = $end_index - $start_index;
        $name = substr($name['content'],$start_index,$len);
        global $widgetName;
        $widgetName = $name;
      }else if(count($value) < count($old_value)){
        $diff = array_values(array_diff(array_keys($old_value),array_keys($value)));
        $name = $old_value[$diff[0]];
        $start_index = strpos($name['content'],"wp:")+3;
        $end_index = strpos($name['content']," ",$start_index);
        $len = $end_index - $start_index;
        $name = substr($name['content'],$start_index,$len);
        global $widgetName;
        $widgetName = $name;
      }
    }
  }

  if(strpos($option, 'sidebars_widgets') !== false){
    global $gCurrentUser;
    global $wpdb;
    global $wp_registered_sidebars;
    global $wp_registered_widgets;
    global $widgetName;
    $sidebar_name = "Sidebar";
    $widget_name = "Widget";
    $table_name = $wpdb->prefix . "c5_history";
    $plugin_name = "Widgets";
    if(is_plugin_active("classic-widgets/classic-widgets.php") && esc_attr(get_option("click5_history_log_classic-widgets/classic-widgets.php")) == "1")
      $plugin_name = "Classic Widgets";
    else if(current_theme_supports('widgets-block-editor') === false)
      $plugin_name = "Legacy Widgets";
    else{
      if(esc_attr(get_option("click5_history_log_module_widgets")) === "1"){
        return;
      }
    }
    foreach($value as $name => $content){
      if(!is_plugin_active("classic-widgets/classic-widgets.php") && current_theme_supports('widgets-block-editor') !== false)
        break;
      if(dynamic_sidebar($name) || (!empty($wp_registered_sidebars[$name]) && is_array($wp_registered_sidebars[$name]))){
        $sidebar_name = $wp_registered_sidebars[$name];
        if(!empty($sidebar_name) && isset($sidebar_name['name']))
          $sidebar_name = $sidebar_name['name'];
  
        if(count(array_diff($value[$name],$old_value[$name])) > 0)
        {
          $widget_slug = array_values(array_diff($value[$name],$old_value[$name]))[0];
          $widget_name = $wp_registered_widgets[$widget_slug]['name'];

            if(empty($widget_name)){
              if(!empty($click5_registered_widget[$widget_slug])){
                $widget_name = $click5_registered_widget[$widget_slug]['name'];
              }else
              {
                $end_index = strpos($widget_slug,"-",1);
                $widget_name = substr($widget_slug,0,$end_index);
                $widget_name = str_replace("_"," ",$widget_name);
                $widget_name = str_replace("media ","",$widget_name);
                $widget_name = ucfirst($widget_name);
                $widget_name = preg_replace_callback('/\b\w{1,4}\b/', function($matches){
                  return strtoupper($matches[0]);
               }, $widget_name);
              }
            }
          $wpdb->insert($table_name, array('description'=>'Widget <b>' .  $widget_name . '</b> has been added to '.$sidebar_name,'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
          break;
        }
        else if(count(array_diff($old_value[$name],$value[$name])) > 0)
        {
          $widget_slug = array_values(array_diff($old_value[$name],$value[$name]))[0];
          $widget_name = $wp_registered_widgets[$widget_slug]['name'];
            if(empty($widget_name)){
              if(!empty($click5_registered_widget[$widget_slug])){
                $widget_name = $click5_registered_widget[$widget_slug]['name'];
              }else
              {
                $end_index = strpos($widget_slug,"-",1);
                $widget_name = substr($widget_slug,0,$end_index);
                $widget_name = str_replace("_"," ",$widget_name);
                $widget_name = str_replace("media ","",$widget_name);
                $widget_name = ucfirst($widget_name);
                $widget_name = preg_replace_callback('/\b\w{1,4}\b/', function($matches){
                  return strtoupper($matches[0]);
               }, $widget_name);
              }
            }

          $wpdb->insert($table_name, array('description'=>'Widget <b>' .  $widget_name . '</b> has been deleted from '.$sidebar_name,'date'=>date('Y-m-d H:i:s'), 'plugin'=>$plugin_name, 'user'=>$gCurrentUser));
          break;
        }
      }
    }
    /*if(!is_plugin_active("classic-widgets/classic-widgets.php")){
      global $click5_registered_widget;
      $widget_name = ucfirst($widgetName);
      foreach($value as $name => $content){
        if(is_array($value[$name]) && count($value[$name]) > 0 && $name != "wp_inactive_widgets"){
          $diff_new = array_values(array_diff($value[$name], $old_value[$name]));
          $diff_old = array_values(array_diff($old_value[$name], $value[$name]));
          $sidebar_name = $wp_registered_sidebars[$name];
  
            
          
          if(count($diff_new) > count($diff_old)){
            if($_COOKIE['widget_change'] == true || $_COOKIE['widget_change'] == "1"){
              $sidebar_name = $sidebar_name['name'];
              
              if(count($click5_registered_widget) < count($wp_registered_widgets))
                $wpdb->insert($table_name, array('description'=>'New Widget <b>'.$widget_name.'</b> has been added to <b>'.$sidebar_name.'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Widgets', 'user'=>$gCurrentUser));
              
            }
            setcookie("widget_change",false);
            break;
          }else if(count($diff_new) < count($diff_old)){
            if(!empty($click5_registered_widget)){
              $sidebar_name = $sidebar_name['name'];
              if(empty($widget_name) && !str_contains($diff_old[0],"block")){
                $widget_name = $wp_registered_widgets[$diff_old[0]]['name'];
              }
              if(count($click5_registered_widget) == count($wp_registered_widgets))
                $wpdb->insert($table_name, array('description'=>'Widget <b>'.$widget_name.'</b> has been deleted from <b>'.$sidebar_name.'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Widgets', 'user'=>$gCurrentUser));
              
            }
            setcookie("widget_change",false);
            break;
          }
        }
      }
    }*/

  }


  if(strpos($option, 'wpe_cache_last_cleared') !== false){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(esc_attr(get_option("click5_history_log_module_wp_engine")) === "1")
      $wpdb->insert($table_name, array('description'=>"<b>All caches</b> have been cleared",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WP Engine','user'=>$gCurrentUser));
    }

  if(strpos($option,'auto_update_themes') !== false){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $theme = "";
    
    if($old_value != $value)
    {
      if(count($value) > count($old_value))
      {
        $theme = wp_get_theme(array_values(array_diff($value,$old_value))[0]);
        if(esc_attr(get_option("click5_history_log_module_plugins")) === "1")
          $wpdb->insert($table_name, array('description'=>"<b>" . $theme->display("Name") . "</b> theme auto update has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Plugins', 'user'=>wp_get_current_user()->user_login));
      }
      else if(count($old_value) > count($value))
      {
        $theme = wp_get_theme(array_values(array_diff($old_value,$value))[0]);
        if(esc_attr(get_option("click5_history_log_module_plugins")) === "1")
          $wpdb->insert($table_name, array('description'=>"<b>" . $theme->display("Name") . "</b> theme auto update has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Plugins', 'user'=>wp_get_current_user()->user_login));
      }
        
    }
  } 

  if(strpos($option, 'start_of_week') !== false && $old_value != $value) {  
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    
      if($value == 1)
      {
        $value = "Monday";
      }
      else if($value == 0)
      {
        $value = "Sunday";
      }
      else if($value == 2)
      {
        $value = "Tuesday";
      }
      else if($value == 3)
      {
        $value = "Wednesday";
      }
      else if($value == 4)
      {
        $value = "Thursday";
      }
      else if($value == 5)
      {
        $value = "Friday";
      }
      else if($value == 6)
      {
        $value = "Saturday";
      }
      if(esc_attr(get_option("click5_history_log_module_settings")) === "1")
        $wpdb->insert($table_name, array('description'=>'<b>Week Starts On</b> field has been changed to <b>' .  $value . '</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
  }
    if(strpos($option, 'date_format') !== false && $old_value != $value) {  
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value!="")
      {
        if(esc_attr(get_option("click5_history_log_module_settings")) === "1")
          $wpdb->insert($table_name, array('description'=>'<b>Date format</b> has been changed to <b>' .  $value . '</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
      }
    }
    if(strpos($option, 'time_format') !== false && $old_value != $value) {  
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value!="")
      {
        if(esc_attr(get_option("click5_history_log_module_settings")) === "1")
          $wpdb->insert($table_name, array('description'=>'<b>Time format</b> has been changed to <b>' .  $value . '</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
      }
    }
    if(strpos($option, 'WPLANG') !== false && $old_value != $value) {  
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      require_once ABSPATH . 'wp-admin/includes/translation-install.php';
      $translations = wp_get_available_translations();  
      $lang_name = "English";
      foreach($translations as $translation_item) {
        if($translation_item["language"] == $value) {
          $lang_name = $translation_item["english_name"];
          break;
        }
      }
      if(esc_attr(get_option("click5_history_log_module_settings")) === "1")
        $wpdb->insert($table_name, array('description'=>'<b>Site language</b> has been changed to <b>' .  $lang_name . '</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
    }  
    if(strpos($option, 'timezone_string') !== false && $old_value != $value) {  
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value!="")
      {
        if(esc_attr(get_option("click5_history_log_module_settings")) === "1")
          $wpdb->insert($table_name, array('description'=>'<b>Timezone</b> has been changed to <b>' .  $value . '</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
      }
    }
    if(strpos($option, 'gmt_offset') !== false && $old_value != $value) {  
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value!="")
      {
        if($value>=0)
        {
          $value = "+". $value;
        }
        if(esc_attr(get_option("click5_history_log_module_settings")) === "1")
          $wpdb->insert($table_name, array('description'=>'<b>Timezone</b> has been changed to <b>UTC' .  $value . '</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
      }
    }

  if(strpos($option, 'siteurl') !== false && $old_value != $value) {  
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(esc_attr(get_option("click5_history_log_module_settings")) === "1")
      $wpdb->insert($table_name, array('description'=>'WordPress address (URL) email address has been changed to <b>' .  $value . '</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
  }
  if(strpos($option, 'new_admin_email') !== false && $old_value != $value) {  
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(esc_attr(get_option("click5_history_log_module_settings")) === "1")
      $wpdb->insert($table_name, array('description'=>'Administration email address has been changed to <b>' .  $value . '</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
  }
  if(strpos($option, 'blogdescription') !== false && $old_value != $value) {  
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(esc_attr(get_option("click5_history_log_module_settings")) === "1")
      $wpdb->insert($table_name, array('description'=>'Tagline has been changed to <b>' .  $value . '</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
  }
  if(strpos($option, 'blogname') !== false && $old_value != $value) {  
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(esc_attr(get_option("click5_history_log_module_settings")) === "1")
      $wpdb->insert($table_name, array('description'=>'Site title has been changed to <b>' .  $value . '</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
  }
  if(strpos($option, 'classic-editor-replace') !== false) {  
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if($old_value == "classic" && $value == "block" && esc_attr(get_option('click5_history_log_classic-editor/classic-editor.php')) == "1") {
      $wpdb->insert($table_name, array('description'=>'Editor has been changed to <b>block</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Classic Editor', 'user'=>$gCurrentUser));
    }
    if($old_value == "block" && $value == "classic" && esc_attr(get_option('click5_history_log_classic-editor/classic-editor.php')) == "1") {
      $wpdb->insert($table_name, array('description'=>'Editor has been changed to <b>classic</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Classic Editor', 'user'=>$gCurrentUser));
    }
  }  

  //YOAST SEO
  if(strpos($option, 'wpseo_titles') !== false) {  
    if(isset($old_value["breadcrumbs-enable"]) && isset($value["breadcrumbs-enable"]))
    {
      if($old_value["breadcrumbs-enable"] != $value["breadcrumbs-enable"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["breadcrumbs-enable"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Breadcrumbs</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Breadcrumbs</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }
  }  
  else if(strpos($option, 'wpseo') !== false) {  
    if(isset($old_value["enable_xml_sitemap"]) && isset($value["enable_xml_sitemap"]))
    {
      if($old_value["enable_xml_sitemap"] != $value["enable_xml_sitemap"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["enable_xml_sitemap"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>XML Sitemaps</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>XML Sitemaps</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }
    if(isset($old_value["keyword_analysis_active"]) && isset($value["keyword_analysis_active"]))
    {
      if($old_value["keyword_analysis_active"] != $value["keyword_analysis_active"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["keyword_analysis_active"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>SEO Analysis</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>SEO Analysis</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }
    if(isset($old_value["content_analysis_active"]) && isset($value["content_analysis_active"]))
    {
      if($old_value["content_analysis_active"] != $value["content_analysis_active"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["content_analysis_active"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Readability Analysis</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Readability Analysis</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }
    if(isset($old_value["enable_cornerstone_content"]) && isset($value["enable_cornerstone_content"]))
    {
      if($old_value["enable_cornerstone_content"] != $value["enable_cornerstone_content"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["enable_cornerstone_content"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Cornerstone Content</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Cornerstone Content</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }
    if(isset($old_value["enable_text_link_counter"]) && isset($value["enable_text_link_counter"]))
    {
      if($old_value["enable_text_link_counter"] != $value["enable_text_link_counter"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["enable_text_link_counter"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Text Link Counter</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Text Link Counter</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["enable_admin_bar_menu"]) && isset($value["enable_admin_bar_menu"]))
    {
      if($old_value["enable_admin_bar_menu"] != $value["enable_admin_bar_menu"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["enable_admin_bar_menu"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Admin Bar Menu</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Admin Bar Menu</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["disableadvanced_meta"]) && isset($value["disableadvanced_meta"]))
    {
      if($old_value["disableadvanced_meta"] != $value["disableadvanced_meta"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["disableadvanced_meta"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Security: no advanced or schema settings for authors</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Security: no advanced or schema settings for authors</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["tracking"]) && isset($value["tracking"]))
    {
      if($old_value["tracking"] != $value["tracking"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["tracking"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Usage Tracking</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Usage Tracking</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["enable_headless_rest_endpoints"]) && isset($value["enable_headless_rest_endpoints"]))
    {
      if($old_value["enable_headless_rest_endpoints"] != $value["enable_headless_rest_endpoints"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["enable_headless_rest_endpoints"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>REST API: Head Endpoint</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>REST API: Head Endpoint</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["enable_link_suggestions"]) && isset($value["enable_link_suggestions"]))
    {
      if($old_value["enable_link_suggestions"] != $value["enable_link_suggestions"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["enable_link_suggestions"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Link suggestions</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Link suggestions</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["enable_metabox_insights"]) && isset($value["enable_metabox_insights"]))
    {
      if($old_value["enable_metabox_insights"] != $value["enable_metabox_insights"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["enable_metabox_insights"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Insights</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Insights</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["enable_enhanced_slack_sharing"]) && isset($value["enable_enhanced_slack_sharing"]))
    {
      if($old_value["enable_enhanced_slack_sharing"] != $value["enable_enhanced_slack_sharing"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["enable_enhanced_slack_sharing"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Enhanced Slack Sharing</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Enhanced Slack Sharing</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["semrush_integration_active"]) && isset($value["semrush_integration_active"]))
    {
      if($old_value["semrush_integration_active"] != $value["semrush_integration_active"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["semrush_integration_active"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Semrush Integration</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Semrush Integration</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["ryte_indexability"]) && isset($value["ryte_indexability"]))
    {
      if($old_value["ryte_indexability"] != $value["ryte_indexability"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["ryte_indexability"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Ryte Integration</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Ryte Integration</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["wordproof_integration_active"]) && isset($value["wordproof_integration_active"]))
    {
      if($old_value["wordproof_integration_active"] != $value["wordproof_integration_active"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["wordproof_integration_active"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Wordproof Integration</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Wordproof Integration</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    
    if(isset($old_value["algolia_integration_active"]) && isset($value["algolia_integration_active"]))
    {
      if($old_value["algolia_integration_active"] != $value["algolia_integration_active"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["algolia_integration_active"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Algolia Integration</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Algolia Integration</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["zapier_integration_active"]) && isset($value["zapier_integration_active"]))
    {
      if($old_value["zapier_integration_active"] != $value["zapier_integration_active"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["zapier_integration_active"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Zapier Integration</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Zapier Integration</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    if(isset($old_value["wincher_integration_active"]) && isset($value["wincher_integration_active"]))
    {
      if($old_value["wincher_integration_active"] != $value["wincher_integration_active"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value["wincher_integration_active"]) {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Wincher Integration</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
          }     
        } else {
          if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
            $wpdb->insert($table_name, array('description'=>'<b>Wincher Integration</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser));
          }
        }
      }
    }

    /*if(isset($old_value["baiduverify"]) && isset($value["baiduverify"]))
    {
      if($old_value["baiduverify"] != $value["baiduverify"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
          $wpdb->insert($table_name, array('description'=>'<b>Baidu Verification Code</b> has been set to <b>'.$value["baiduverify"] .'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
        } 
      }
    }

    if(isset($old_value["msverify"]) && isset($value["msverify"]))
    {
      if($old_value["msverify"] != $value["msverify"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
          $wpdb->insert($table_name, array('description'=>'<b>Bing Verification Code</b> has been set to <b>'.$value["msverify"] .'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
        } 
      }
    }

    if(isset($old_value["googleverify"]) && isset($value["googleverify"]))
    {
      if($old_value["googleverify"] != $value["googleverify"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
          $wpdb->insert($table_name, array('description'=>'<b>Google Verification Code</b> has been set to <b>'.$value["googleverify"] .'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
        } 
      }
    }

    if(isset($old_value["yandexverify"]) && isset($value["yandexverify"]))
    {
      if($old_value["yandexverify"] != $value["yandexverify"]) {
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if(((esc_attr(get_option('click5_history_log_wordpress-seo/wp-seo.php')) == "1" && is_plugin_active('wordpress-seo/wp-seo.php')) || (esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1" && is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')))) { 
          $wpdb->insert($table_name, array('description'=>'<b>Yandex Verification Code</b> has been set to <b>'.$value["yandexverify"] .'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO', 'user'=>$gCurrentUser)); 
        } 
      }
    }*/
    if(strpos($option, "wpseo_premium") !== false)
    {
      if(esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1") { 
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        $save_value = $value;
        $save_old_value = $old_value;
        if(is_array($old_value["workouts"]) && is_array($value["workouts"])){
          $old_value = $old_value["workouts"];
          $value = $value["workouts"];
          if(is_array($old_value["cornerstone"]) && is_array($value["cornerstone"])){
            $workouts = array(
              "chooseCornerstones" => "Choose your cornerstones",
              "markCornerstones" => "Mark these articles as cornerstone content",
              "checkCornerstones" => "Check whether your cornerstones are correct",
              "checkLinks" => "Check the number of incoming internal links of your cornerstones",
              "addLinks" => "Add internal links towards your cornerstones",
            );
            $old_value = $old_value["cornerstone"];
            $value = $value["cornerstone"];
            if(is_array($old_value["finishedSteps"]) && is_array($value["finishedSteps"])){
              $old_value = $old_value["finishedSteps"];
              $value = $value["finishedSteps"];
              if(count($old_value) != count($value))
              {
                if(empty($old_value) && !empty($value)){
                  $val = array_diff($value,$old_value);
                  if(count($val) > 1)
                    $wpdb->insert($table_name, array('description'=>'<b>All</b> workouts has been finished','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                  else
                    $wpdb->insert($table_name, array('description'=>'Workout <b>'.$workouts[$value[0]].'</b> has been finished','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                }else if(empty($value) && !empty($old_value)){
                  $val = array_diff($old_value,$value);
                  if(count($val) > 1)
                    $wpdb->insert($table_name, array('description'=>'<b>All</b> workouts has been revised','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                  else
                    $wpdb->insert($table_name, array('description'=>'Workout <b>'.$workouts[$old_value[0]].'</b> has been revised','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                }else{
                  if(count($value) > count($old_value)){
                    $val = array_diff($value,$old_value);
                    $wpdb->insert($table_name, array('description'=>'Workout <b>'.$workouts[array_values($val)[0]].'</b> has been finished','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                  }else if(count($value) < count($old_value)){
                    $val = array_diff($old_value,$value);
                    $wpdb->insert($table_name, array('description'=>'Workout <b>'.$workouts[array_values($val)[0]].'</b> has been revised','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                  }
                }
              }
            }
            $old_value = $save_old_value;
            $value = $save_value;
            $old_value = $old_value["workouts"];
            $value = $value["workouts"];
          }
  
          if(isset($old_value["orphaned"]) && isset($value["orphaned"])){
            $old_value = $old_value["orphaned"];
            $value = $value["orphaned"];
            if(is_array($old_value["finishedSteps"]) && is_array($value["finishedSteps"])){
              $workouts = array(
                "improveRemove" => "Love it or leave it",
                "update" => "Should you update your article?",
                "addLinks" => "Add internal links towards your orphaned articles.",
              );
              $old_value = $old_value["finishedSteps"];
              $value = $value["finishedSteps"];
              if(count($old_value) != count($value))
              {
                if(empty($old_value) && !empty($value)){
                  $val = array_diff($value,$old_value);
                  if(count($val) > 1)
                    $wpdb->insert($table_name, array('description'=>'<b>All</b> workouts has been finished','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                  else
                    $wpdb->insert($table_name, array('description'=>'Workout <b>'.$workouts[$value[0]].'</b> has been finished','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                }else if(empty($value) && !empty($old_value)){
                  $val = array_diff($old_value,$value);
                  if(count($val) > 1)
                    $wpdb->insert($table_name, array('description'=>'<b>All</b> workouts has been revised','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                  else
                    $wpdb->insert($table_name, array('description'=>'Workout <b>'.$workouts[$old_value[0]].'</b> has been revised','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                }else{
                  if(count($value) > count($old_value)){
                    $val = array_diff($value,$old_value);
                    $wpdb->insert($table_name, array('description'=>'Workout <b>'.$workouts[array_values($val)[0]].'</b> has been finished','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                  }else if(count($value) < count($old_value)){
                    $val = array_diff($old_value,$value);
                    $wpdb->insert($table_name, array('description'=>'Workout <b>'.$workouts[array_values($val)[0]].'</b> has been revised','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$gCurrentUser)); 
                  }
                }
              }
            }
            $old_value = $save_old_value;
            $value = $save_value;
          }
        }
      }
    }
    if(strpos($option, "wpseo-premium-redirects-base") !== false){
      if(esc_attr(get_option('click5_history_log_wordpress-seo-premium/wp-seo-premium.php')) == "1"){
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        $usr = get_currentuserinfo()->data->user_login;
        if(!is_null($gCurrentUser))
          $usr = $gCurrentUser;
        $redirect_add = array_diff(array_keys($value),array_keys($old_value));
        $redirect_remove = array_diff(array_keys($old_value),array_keys($value));
        if(!empty($redirect_add) && empty($redirect_remove)){
          $redirect_info = $value[array_values($redirect_add)[0]];
          $wpdb->insert($table_name, array('description'=>'Redirect '.$redirect_info['type'].' from <b>'.esc_url(home_url($redirect_info['origin'])).'</b> to <b>'.esc_url(home_url($redirect_info['url'])).'</b> has been created','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$usr)); 
        }else if(empty($redirect_add) && !empty($redirect_remove)){
          if(count($redirect_remove) > 1){
            foreach(array_values($redirect_remove) as $index){
              $redirect_info = $old_value[$index];
              $wpdb->insert($table_name, array('description'=>'Redirect '.$redirect_info['type'].' from <b>'.esc_url(home_url($redirect_info['origin'])).'</b> to <b>'.esc_url(home_url($redirect_info['url'])).'</b> has been deleted','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$usr)); 
            }
          }else {
            $redirect_info = $old_value[array_values($redirect_remove)[0]];
            $wpdb->insert($table_name, array('description'=>'Redirect '.$redirect_info['type'].' from <b>'.esc_url(home_url($redirect_info['origin'])).'</b> to <b>'.esc_url(home_url($redirect_info['url'])).'</b> has been deleted','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast SEO Premium', 'user'=>$usr)); 
          }
        }
      }
    }
  }

  //RankMath SEO
  if(esc_attr(get_option('click5_history_log_seo-by-rank-math/rank-math.php')) == "1") {
  if(strpos($option, 'rank_math_modules') !== false) { 
      $module_status = "";
      $module_name = "";
      if(count($old_value) > count($value) )
      {
        $module_status = "turned off";
      }
      else if(count($old_value) < count($value))
      {
        $module_status = "turned on";
      }

      if($module_status == "turned on")
      {
        foreach($value as $item)
        {
          if(in_array($item, $old_value) == false)
          {
            $module_name = $item;
            break;
          }
        }
      }
      else if ($module_status == "turned off")
      {
        foreach($old_value as $item)
        {
          if(in_array($item, $value) == false)
          {
            $module_name = $item;
            break;
          }
        }
      }

      if($module_status != "" && $module_name != "")
      {
        if (function_exists('str_replace'))
      {
        $module_name = str_replace('_', ' ', $module_name);
        $module_name = str_replace('-', ' ', $module_name);
      }
        $premium_modules = array(
          '404 monitor',
          'analytics',
          'bbpress',
          'image seo',
          'local seo',
          'news sitemap',
          "redirections",
          'rich snippet',
          'video sitemap',
          'woocommerce'
        );

        if(in_array(strtolower($module_name),$premium_modules) == true)
        {
          if(esc_attr(get_option('click5_history_log_seo-by-rank-math-pro/rank-math-pro.php')) != "1")
            return;

          $premium = 'Pro';
        }
          
        else
          $premium = '';
        

        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        $wpdb->insert($table_name, array('description'=>'Module <b>'.$module_name. '</b> has been ' .$module_status ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math '.$premium, 'user'=>$gCurrentUser)); 
      }
      
  }
  
  if(strpos($option, 'rank-math-options-sitemap') !== false) {  
    if($old_value["items_per_page"] != $value["items_per_page"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>'<b>Items per page</b> has been changed from '.$old_value["items_per_page"]. ' to <b> '.$value["items_per_page"].'</b> in XML Sitemap'  ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
    }

    if($old_value["include_images"] != $value["include_images"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value["include_images"]=="on") {
        $wpdb->insert($table_name, array('description'=>'<b>Images in Sitemaps</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
           
    } else {
        $wpdb->insert($table_name, array('description'=>'<b>Images in Sitemaps</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser));
    }
    }
    if($old_value["include_featured_image"] != $value["include_featured_image"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value["include_featured_image"]=="on") {
        $wpdb->insert($table_name, array('description'=>'<b>Featured Images in Sitemaps</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
           
    } else {
        $wpdb->insert($table_name, array('description'=>'<b>Featured Images in Sitemaps</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser));
    }
    }

    if($old_value["ping_search_engines"] != $value["ping_search_engines"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value["ping_search_engines"]=="on") {
        $wpdb->insert($table_name, array('description'=>'<b>Ping Search Engines</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
           
    } else {
        $wpdb->insert($table_name, array('description'=>'<b>Ping Search Engines</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser));
    }
    }

    if($old_value["exclude_posts"] != $value["exclude_posts"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $changedTo = "empty";
      $changedFrom ="empty";
      if(isset($value["exclude_posts"])&& $value["exclude_posts"] != "")
      {
        $changedTo = $value["exclude_posts"];
      }
      if(isset($old_value["exclude_posts"])&& $old_value["exclude_posts"] != "")
      {
        $changedFrom = $old_value["exclude_posts"];
      }
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>'<b>Exclude Posts Field</b> has been changed from '. $changedFrom. ' to <b>'.$changedTo.'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
    }

    if($old_value["exclude_terms"] != $value["exclude_terms"]) {
      global $gCurrentUser;
      global $wpdb;
      $changedTo = "empty";
      $changedFrom ="empty";
      if(isset($value["exclude_terms"])&& $value["exclude_terms"] != "")
      {
        $changedTo = $value["exclude_terms"];
      }
      if(isset($old_value["exclude_terms"])&& $old_value["exclude_terms"] != "")
      {
        $changedFrom = $old_value["exclude_terms"];
      }
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>'<b>Exclude Terms Field</b> has been changed from '. $changedFrom. ' to <b>'.$changedTo.'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
    }

    if($old_value["pt_post_sitemap"] != $value["pt_post_sitemap"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
        if($value["pt_post_sitemap"]=="on") {
          $wpdb->insert($table_name, array('description'=>'<b>Posts</b> has been included to sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
            
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Posts</b> has been excluded from sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser));
      }
    }
    if($old_value["pt_page_sitemap"] != $value["pt_page_sitemap"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
        if($value["pt_page_sitemap"]=="on") {
          $wpdb->insert($table_name, array('description'=>'<b>Pages</b> has been included to sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
            
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Pages</b> has been excluded from sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser));
      }
    }

    if($old_value["pt_attachment_sitemap"] != $value["pt_attachment_sitemap"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
        if($value["pt_attachment_sitemap"]=="on") {
          $wpdb->insert($table_name, array('description'=>'<b>Attachments</b> has been included to sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
            
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Attachments</b> has been excluded from sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser));
      }
    }

    if($old_value["tax_category_sitemap"] != $value["tax_category_sitemap"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
        if($value["tax_category_sitemap"]=="on") {
          $wpdb->insert($table_name, array('description'=>'<b>Categories</b> has been included to sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
            
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Categories</b> has been excluded from sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser));
      }
    }

    if($old_value["tax_post_tag_sitemap"] != $value["tax_post_tag_sitemap"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
        if($value["tax_post_tag_sitemap"]=="on") {
          $wpdb->insert($table_name, array('description'=>'<b>Tags</b> has been included to sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
            
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Tags</b> has been excluded from sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser));
      }
    }
  }
  if(strpos($option, 'rank-math-options-general') !== false) {  

    if($old_value["breadcrumbs"] != $value["breadcrumbs"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value["breadcrumbs"]) {
          $wpdb->insert($table_name, array('description'=>'<b>Breadcrumbs</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
             
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Breadcrumbs</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser));
      }
    }
   
    
    if($old_value["google_verify"] != $value["google_verify"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
        $wpdb->insert($table_name, array('description'=>'<b>Google Verification Code</b> has been set to <b>'.$value["google_verify"] .'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
    }

    if($old_value["bing_verify"] != $value["bing_verify"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
        $wpdb->insert($table_name, array('description'=>'<b>Bing Verification Code</b> has been set to <b>'.$value["bing_verify"] .'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
    }

    if($old_value["baidu_verify"] != $value["baidu_verify"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
        $wpdb->insert($table_name, array('description'=>'<b>Baidu Verification Code</b> has been set to <b>'.$value["baidu_verify"] .'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
    }

    if($old_value["yandex_verify"] != $value["yandex_verify"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
        $wpdb->insert($table_name, array('description'=>'<b>Yandex Verification Code</b> has been set to <b>'.$value["yandex_verify"] .'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
    }

    if($old_value["pinterest_verify"] != $value["pinterest_verify"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
        $wpdb->insert($table_name, array('description'=>'<b>Pinterest Verification Code</b> has been set to <b>'.$value["pinterest_verify"] .'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
    }

    if($old_value["norton_verify"] != $value["norton_verify"]) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
        $wpdb->insert($table_name, array('description'=>'<b>Norton Safe Web Verification Code</b> has been set to <b>'.$value["norton_verify"] .'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Rank Math', 'user'=>$gCurrentUser)); 
    }
    
  }
}


//All In One SEO
if(esc_attr(get_option('click5_history_log_all-in-one-seo-pack/all_in_one_seo_pack.php')) == "1" || esc_attr(get_option('click5_history_log_all-in-one-seo-pack-pro/all_in_one_seo_pack.php')) == "1") {
  if((strpos($option, 'aioseo_options') !== false && is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php')) || (strpos($option, 'aioseo_options_internal_pro') !== false && is_plugin_active('all-in-one-seo-pack-pro/all_in_one_seo_pack.php'))) { 
    if(!function_exists('aioseo')) 
      return;
    global $click5_aioseo_options_old;
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $click5_aioseo_options_new = aioseo()->core->optionsCache->getOptions( "aioseo_options" );
    $breadcrumbs_old = $click5_aioseo_options_old["breadcrumbs"]["enable"]["value"];
    $breadcrumbs_new = $click5_aioseo_options_new["breadcrumbs"]["enable"]["value"];
    $sitemap_old = $click5_aioseo_options_old["sitemap"]["general"]["enable"]["value"];
    $sitemap_new = $click5_aioseo_options_new["sitemap"]["general"]["enable"]["value"];
    $sitemap_indexes_old = $click5_aioseo_options_old["sitemap"]["general"]["indexes"]["value"];
    $sitemap_indexes_new = $click5_aioseo_options_new["sitemap"]["general"]["indexes"]["value"];
    $sitemap_html_old = $click5_aioseo_options_old["sitemap"]["html"]["enable"]["value"];
    $sitemap_html_new = $click5_aioseo_options_new["sitemap"]["html"]["enable"]["value"];
    $sitemap_links_old = $click5_aioseo_options_old["sitemap"]["general"]["linksPerIndex"]["value"];
    $sitemap_links_new = $click5_aioseo_options_new["sitemap"]["general"]["linksPerIndex"]["value"];
    $sitemap_author_old = $click5_aioseo_options_old["sitemap"]["general"]["author"]["value"];
    $sitemap_author_new = $click5_aioseo_options_new["sitemap"]["general"]["author"]["value"];
    $sitemap_date_old = $click5_aioseo_options_old["sitemap"]["general"]["date"]["value"];
    $sitemap_date_new = $click5_aioseo_options_new["sitemap"]["general"]["date"]["value"];
    $sitemap_postTypes_old = $click5_aioseo_options_old["sitemap"]["general"]["postTypes"]["all"]["value"];
    $sitemap_postTypes_new = $click5_aioseo_options_new["sitemap"]["general"]["postTypes"]["all"]["value"];
    $sitemap_taxonomies_old = $click5_aioseo_options_old["sitemap"]["general"]["taxonomies"]["all"]["value"];
    $sitemap_taxonomies_new = $click5_aioseo_options_new["sitemap"]["general"]["taxonomies"]["all"]["value"];
    $sitemap_additionalPages_old = $click5_aioseo_options_old["sitemap"]["general"]["additionalPages"]["enable"]["value"];
    $sitemap_additionalPages_new = $click5_aioseo_options_new["sitemap"]["general"]["additionalPages"]["enable"]["value"];
    $sitemap_advancedSettings_old = $click5_aioseo_options_old["sitemap"]["general"]["advancedSettings"]["enable"]["value"];
    $sitemap_advancedSettings_new = $click5_aioseo_options_new["sitemap"]["general"]["additionalPages"]["enable"]["value"];

    $sitemap_html_postTypes_old = $click5_aioseo_options_old["sitemap"]["html"]["postTypes"]["all"]["value"];
    $sitemap_html_postTypes_new = $click5_aioseo_options_new["sitemap"]["html"]["postTypes"]["all"]["value"];
    $sitemap_html_taxonomies_old = $click5_aioseo_options_old["sitemap"]["html"]["taxonomies"]["all"]["value"];
    $sitemap_html_taxonomies_new = $click5_aioseo_options_new["sitemap"]["html"]["taxonomies"]["all"]["value"];
    $sitemap_html_sortOrder_old = $click5_aioseo_options_old["sitemap"]["html"]["sortOrder"]["value"];
    $sitemap_html_sortOrder_new = $click5_aioseo_options_new["sitemap"]["html"]["sortOrder"]["value"];
    $sitemap_html_sortDirection_old = $click5_aioseo_options_old["sitemap"]["html"]["sortDirection"]["value"];
    $sitemap_html_sortDirection_new = $click5_aioseo_options_new["sitemap"]["html"]["sortDirection"]["value"];
    $sitemap_html_publicationDate_old = $click5_aioseo_options_old["sitemap"]["html"]["publicationDate"]["value"];
    $sitemap_html_publicationDate_new = $click5_aioseo_options_new["sitemap"]["html"]["publicationDate"]["value"];
    $sitemap_html_compactArchives_old = $click5_aioseo_options_old["sitemap"]["html"]["compactArchives"]["value"];
    $sitemap_html_compactArchives_new = $click5_aioseo_options_new["sitemap"]["html"]["compactArchives"]["value"];
    $sitemap_html_advancedSettings_old = $click5_aioseo_options_old["sitemap"]["html"]["advancedSettings"]["enable"]["value"];
    $sitemap_html_advancedSettings_new = $click5_aioseo_options_new["sitemap"]["html"]["advancedSettings"]["enable"]["value"];
    $access_controll_old = $click5_aioseo_options_old['accessControl'];
    $access_controll_new = $click5_aioseo_options_new['accessControl'];
    $advanced_old = $click5_aioseo_options_old['advanced'];
    $advanced_new = $click5_aioseo_options_new['advanced'];


    if((esc_attr(get_option('click5_history_log_all-in-one-seo-pack-pro/all_in_one_seo_pack.php')) == "1") && is_plugin_active('all-in-one-seo-pack-pro/all_in_one_seo_pack.php')){
      $aio_plugin_name = "All In One SEO Pro";
    }else{
      $aio_plugin_name = "All In One SEO";
    }
    //* ACCESS CONTROLL */
    $is_changed = false;
    foreach($access_controll_new as $control => $val){
      global $wp_roles;
      foreach($wp_roles->role_names as $_role => $roleData){
        $roleOutput = $roleData;
        $roleToCheck = strtolower(str_replace(" ","",$roleOutput));
        if(strtolower($control) == $roleToCheck){
          $roleOutput = $roleData;
          break;
        }

        $roleOutput = $control;
      }
      if(is_array($val)){
        $control_added = "";
        $control_removed = "";
        $is_changed = false;
        $tmp_control = $access_controll_old;
        $tmp_control = $tmp_control[$control];
        if($val['useDefault']['value'] != $tmp_control['useDefault']['value'])
        {
          if($val['useDefault']['value']){
            $wpdb->insert($table_name, array('description'=>"<b>".$roleOutput.'</b> Default Settings has been turned on in Access Control tab','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
          }else{
            $wpdb->insert($table_name, array('description'=>"<b>".$roleOutput.'</b> Default Settings has been turned off in Access Control tab','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
          }
        }
            /*foreach($val as $key => $index){
              if($index['value'] != $tmp_control[$key]['value'])
              {
                $is_changed = true;
                if($index['value']){
                  $control_added .= "<b>".$key."</b>, ";
                }else{
                  if($key == "useDefault")
                    $key = "Use Default Settings";
                  $control_removed .= "<b>".$key."</b>, ";
                }
              }
              
            }
          if($is_changed && $val['useDefault']['value'] == false)
          {
            if(!empty($control_added))
              $control_added = "<br>Added ".$control_added;
            if(!empty($control_removed))
              $control_removed = "<br>Removed ".$control_removed;
            $wpdb->insert($table_name, array('description'=>'Access Control settings for <b>'.$control.'</b> has been changed'.$control_added.$control_removed,'date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
          }*/
            
      }
    }

    /* ADVANCED */

    $advanced_names = array(
      "truSeo"            => "TruSEO Score & Content",
      "headlineAnalyzer"  => "Headline Analyzer",
      "dashboardWidgets"  => "Dashboard Widgets",
      "announcements"     => "Announcements",
      "adminBarMenu"      => "Admin Bar Menu",
      "autoUpdates"       => "Automatic Updates",
      'usageTracking'     => "Usage Tracking"

    );

    foreach($advanced_new as $name => $array){
      if(isset($array['value']))
      {
        $tmp_advanced = $advanced_old;
        if($array['value'] != $tmp_advanced[$name]['value']){
          if(is_string($array['value'])){

            $name = $advanced_names[$name];
            $wpdb->insert($table_name, array('description'=>'Setting <b>'.$name.'</b> has been set to '.$array['value'],'date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
          }else{
            if($array['value'] == true){
              if($name == "adminBarMenu" || $name == "dashboardWidgets" || $name == "announcements")
                $action = "set to Show";
              else
                $action = "enabled";

              $name = $advanced_names[$name];
              $wpdb->insert($table_name, array('description'=>'Setting <b>'.$name.'</b> has been '.$action,'date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
            }else if($array['value'] == false){
              if($name == "adminBarMenu" || $name == "dashboardWidgets" || $name == "announcements")
                $action = "set to Hide";
              else
                $action = "disabled";
                
              $name = $advanced_names[$name];
              $wpdb->insert($table_name, array('description'=>'Setting <b>'.$name.'</b> has been '.$action,'date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
            }
          }
        }
      }else{
        if(is_array($array['all']) && !empty($array['all'])){
          $tmp_advanced = $advanced_old[$name];
          if($name == "postTypes")
            $name = "Post Types";
          else if($name == "taxonomies")
            $name = "Taxonomy";
          if($array['all']['value']){

            if($array['all']['value'] != $tmp_advanced['all']['value'])
                $wpdb->insert($table_name, array('description'=>'<b>Include All '.$name.'</b> has been enabled in Advanced tab','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
              
          }else{
            if($array['all']['value'] != $tmp_advanced['all']['value'])
              $wpdb->insert($table_name, array('description'=>'<b>Include All '.$name.'</b> has been disabled in Advanced tab','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
            
            /*
            if($array['included']['value'] != $tmp_advanced['included']['value']){
              $tmp_added = array_diff($array['included']['value'],$tmp_advanced['included']['value']);
              $tmp_removed = array_diff($tmp_advanced['included']['value'],$array['included']['value']);
              $output_added = "";
              $output_removed = "";
              foreach($tmp_added as $added){
                $output_added .= "<b>".$added."</b>, ";
              }

              foreach($tmp_removed as $removed){
                $output_removed .= "<b>".$removed."</b>, ";
              }

              if(!empty($output_added))
                $output_added = "<br>Added: ".$output_added;
              if(!empty($output_removed))
                $output_removed = "<br>Removed: ".$output_removed;
              //$wpdb->insert($table_name, array('description'=>'Setting <b>'.$name.'</b> has been changed '.$output_added.$output_removed,'date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
            }
          */}
        }
      }
    }


    if($sitemap_html_advancedSettings_old!= $sitemap_html_advancedSettings_new)
    {
      if($sitemap_html_advancedSettings_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Advanced Settings</b> has been turned on in HTML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Advanced Settings</b> has been turned off in HTML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }

    if($sitemap_html_compactArchives_old!= $sitemap_html_compactArchives_new)
    {
      if($sitemap_html_compactArchives_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Compact Archives</b> has been enabled in HTML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Compact Archives</b> has been disabled in HTML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }

    if($sitemap_html_publicationDate_old!= $sitemap_html_publicationDate_new)
    {
      if($sitemap_html_publicationDate_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Publication Date</b> has been enabled in HTML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Publication Date</b> has been disabled in HTML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }

    if($sitemap_html_sortDirection_old!= $sitemap_html_sortDirection_new)
    {
      $sitemap_html_direction_text_new = $sitemap_html_sortDirection_new;
      $sitemap_html_direction_text_old = $sitemap_html_sortDirection_old;
      if (function_exists('str_replace'))
      {
        $sitemap_html_direction_text_new = str_replace('_', ' ', $sitemap_html_sortDirection_new);
        $sitemap_html_direction_text_old = str_replace('_', ' ', $sitemap_html_sortDirection_old);
      }
      $wpdb->insert($table_name, array('description'=>'<b>Sort Order</b> has been changed from '.$sitemap_html_direction_text_old. ' to <b> '.$sitemap_html_direction_text_new.'</b> in HTML Sitemap'  ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
    }


    if($sitemap_html_sortOrder_old!= $sitemap_html_sortOrder_new)
    {
      $sitemap_html_order_text_new = $sitemap_html_sortOrder_new;
      $sitemap_html_order_text_old = $sitemap_html_sortOrder_old;
      if (function_exists('str_replace'))
      {
        $sitemap_html_order_text_new = str_replace('_', ' ', $sitemap_html_sortOrder_new);
        $sitemap_html_order_text_old = str_replace('_', ' ', $sitemap_html_sortOrder_old);
      }
      $wpdb->insert($table_name, array('description'=>'<b>Sort Order</b> has been changed from '.$sitemap_html_order_text_old. ' to <b> '.$sitemap_html_order_text_new.'</b> in HTML Sitemap'  ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
    }

    if($sitemap_html_taxonomies_old != $sitemap_html_taxonomies_new)
    {
      if($sitemap_html_taxonomies_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Include All Taxonomies</b> has been enabled in HTML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Include All Taxonomies</b> has been disabled in HTML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }

    if($sitemap_html_postTypes_old!= $sitemap_html_postTypes_new)
    {
      if($sitemap_html_postTypes_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Include All Post Types</b> has been enabled in HTML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Include All Post Types</b> has been disabled in HTML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }

    if($sitemap_advancedSettings_old!= $sitemap_advancedSettings_new)
    {
      if($sitemap_advancedSettings_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Advanced Settings</b> has been turned on in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Advanced Settings</b> has been turned off in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }

    if($sitemap_additionalPages_old!= $sitemap_additionalPages_new)
    {
      if($sitemap_additionalPages_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Additional Pages</b> has been turned on in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Additional Pages</b> has been turned off in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }

    if($sitemap_taxonomies_old != $sitemap_taxonomies_new)
    {
      if($sitemap_taxonomies_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Include All Taxonomies</b> has been enabled in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Include All Taxonomies</b> has been disabled in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }

    if($sitemap_postTypes_old!= $sitemap_postTypes_new)
    {
      if($sitemap_postTypes_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Include All Post Types</b> has been enabled in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Include All Post Types</b> has been disabled in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }


    if($sitemap_date_old!= $sitemap_date_new)
    {
      if($sitemap_date_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Date Archive Sitemap</b> has been enabled in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Date Archive Sitemap</b> has been disabled in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }

    if($sitemap_author_old!= $sitemap_author_new)
    {
      if($sitemap_author_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Author Sitemap</b> has been enabled in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Author Sitemap</b> has been disabled in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }

    if($sitemap_links_old!= $sitemap_links_new)
    {
      $wpdb->insert($table_name, array('description'=>'<b>Links Per Sitemap</b> has been changed from '.$sitemap_links_old. ' to <b> '.$sitemap_links_new.'</b> in XML Sitemap'  ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
    }

    if($breadcrumbs_old!= $breadcrumbs_new)
    {
      if($breadcrumbs_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Breadcrumbs</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Breadcrumbs</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }
    if($sitemap_old!= $sitemap_new)
    {
      if($sitemap_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Sitemap XML</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Sitemap XML</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }
    if($sitemap_indexes_old!= $sitemap_indexes_new)
    {
      if($sitemap_indexes_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Sitemap Indexes</b> has been enabled in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Sitemap Indexes</b> has been disabled in XML Sitemap','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }
    if($sitemap_html_old!= $sitemap_html_new)
    {
      if($sitemap_html_new) {
          $wpdb->insert($table_name, array('description'=>'<b>Sitemap HTML</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser)); 
      } else {
          $wpdb->insert($table_name, array('description'=>'<b>Sitemap HTML</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>$aio_plugin_name, 'user'=>$gCurrentUser));
      }
    }
  }
}

//CF7 Add-on click5

if(esc_attr(get_option('click5_history_log_cf7-add-on-by-click5/cf7-addon-by-click5.php')) == "1"){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(strpos($option, 'click5_cf7_addon_form_enable_') !== false) { 
    $form_post_id = intval(substr($option,strlen("click5_cf7_addon_form_enable_")));
    $form_post = WPCF7_ContactForm::find();
    $title = "";
    foreach($form_post as $form)
    {
      if($form->id == $form_post_id)
        $title = $form->title;
    }
    if($value != $old_value){
    if($value == true)
      $wpdb->insert($table_name, array('description'=>'Form <b>'.$title.'</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to Contact Form 7', 'user'=>$gCurrentUser)); 
    else
      $wpdb->insert($table_name, array('description'=>'Form <b>'.$title.'</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to Contact Form 7', 'user'=>$gCurrentUser)); 
    }
  }

  if(strpos($option, 'click5_cf7_addon_posting_url') !== false) { 
    if($value != $old_value){
      $wpdb->insert($table_name, array('description'=>'<b>Posting URL</b> has been changed to:<br> '.$value,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to Contact Form 7', 'user'=>$gCurrentUser)); 
    }
  }
}

//WPF Add-on click5

if(esc_attr(get_option('click5_history_log_wpf-add-on-by-click5/wpf-addon-by-click5.php')) == "1"){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(strpos($option, 'click5_wpf_addon_form_enable_') !== false) { 
    $form_post_id = intval(substr($option,strlen("click5_wpf_addon_form_enable_")));
    $form_post = wpforms()->form->get();
    $title = "";
    foreach($form_post as $form)
    {
      if($form->ID == $form_post_id)
        $title = $form->post_title;
    }
    if($value != $old_value){
    if($value == true)
      $wpdb->insert($table_name, array('description'=>'Form <b>'.$title.'</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to WPForms', 'user'=>$gCurrentUser)); 
    else
      $wpdb->insert($table_name, array('description'=>'Form <b>'.$title.'</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to WPForms', 'user'=>$gCurrentUser)); 
    }
  }

  if(strpos($option, 'click5_wpf_addon_posting_url') !== false) { 
    if($value != $old_value){
      $wpdb->insert($table_name, array('description'=>'<b>Posting URL</b> has been changed to:<br>'.$value,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to WPForms', 'user'=>$gCurrentUser)); 
    }
  }
}

//Ninja Forms Add-on click5

if(esc_attr(get_option('click5_history_log_click5-crm-add-on-to-ninja-forms/ninja-addon-by-click5.php')) == "1"){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(strpos($option, 'click5_ninja_addon_form_enable_') !== false) { 
    $form_post_id = intval(substr($option,strlen("click5_ninja_addon_form_enable_")));
    $form_post = Ninja_Forms()->form()->get_forms();
    $title = "";
    foreach($form_post as $form)
    {
      if($form->get_id() == $form_post_id)
        $title = $form->get_settings()['title'];
    }
    if($value != $old_value){
    if($value == true)
      $wpdb->insert($table_name, array('description'=>'Form <b>'.$title.'</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to Ninja Forms', 'user'=>$gCurrentUser)); 
    else
      $wpdb->insert($table_name, array('description'=>'Form <b>'.$title.'</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to Ninja Forms', 'user'=>$gCurrentUser)); 
    }
  }

  if(strpos($option, 'click5_ninja_addon_posting_url') !== false) { 
    if($value != $old_value){
      $wpdb->insert($table_name, array('description'=>'<b>Posting URL</b> has been changed to:<br>'.$value,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to Ninja Forms', 'user'=>$gCurrentUser)); 
    }
  }
}

//Gravity Forms Add-on click5

if(esc_attr(get_option('click5_history_log_gf-add-on-by-click5/gf-addon-by-click5.php')) == "1"){
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(strpos($option, 'click5_gf_addon_form_enable_') !== false) { 
    $form_post_id = intval(substr($option,strlen("click5_gf_addon_form_enable_")));
    $form_post = GFAPI::get_forms();
    $title = "";
    foreach($form_post as $form)
    {
      if($form['id'] == $form_post_id)
        $title = $form['title'];
    }
    if($value != $old_value){
    if($value == true)
      $wpdb->insert($table_name, array('description'=>'Form <b>'.$title.'</b> has been enabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to Gravity Forms', 'user'=>$gCurrentUser)); 
    else
      $wpdb->insert($table_name, array('description'=>'Form <b>'.$title.'</b> has been disabled','date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to Gravity Forms', 'user'=>$gCurrentUser)); 
    }
  }

  if(strpos($option, 'click5_gf_addon_posting_url') !== false) { 
    if($value != $old_value){
      $wpdb->insert($table_name, array('description'=>'<b>Posting URL</b> has been changed to:<br>'.$value,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'click5 CRM add-on to Gravity Forms', 'user'=>$gCurrentUser)); 
    }
  }
}

//Sitemap click5
  if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1"){
    if(strpos($option, 'click5_sitemap_html_pagination_items_per_page') !== false){
      if($value != $old_value){
        $it = "items";
        if(intval($value) == 1)
          $it = "item";
        $wpdb->insert($table_name, array('description'=>'<b>Pagination</b> has been changed to '.$value." ".$it." per page",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
      }
    }

     if(strpos($option, 'click5_sitemap_html_blog_sort_by') !== false){
      if($value != $old_value){
        $wpdb->insert($table_name, array('description'=>'<b>Sort by</b> option has been changed to '.ucfirst($value),'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
      }
    }

    if(strpos($option, 'click5_sitemap_html_blog_order_by') !== false){
      if($value != $old_value){
        $order = "Ascending";
        if($value === "DESC")
          $order = "Descending";

        $wpdb->insert($table_name, array('description'=>'<b>Order by</b> option has been changed to '.ucfirst($order),'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));

      }
    }
  }

  if(strpos($option, 'click5_sitemap_urls_list') !== false) { 
    $old_data = json_decode($old_value);
    $new_data = json_decode($value);
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(count($new_data) > 0 && count($old_data) > 0 && count($new_data) == count($old_data)) { 
      foreach($new_data as $new_data_item) {
        $update_item = false; 
        foreach($old_data as $old_data_item) {
          if( $new_data_item->ID == $old_data_item->ID ) {
            if($new_data_item->title != $old_data_item->title) {
              $update_item = true;
            }
            if($new_data_item->url != $old_data_item->url) {
              $update_item = true;
            }
            if($new_data_item->new_tab != $old_data_item->new_tab) {
              $update_item = true;
            }
            if($new_data_item->enabledHTML != $old_data_item->enabledHTML) {
              $update_item = true;
            }
            if($new_data_item->enabledXML != $old_data_item->enabledXML) {
              $update_item = true;
            }
            if($new_data_item->last_mod != $old_data_item->last_mod) {
              $update_item = true;
            }
            if($new_data_item->category->use_custom != $old_data_item->category->use_custom) {
              $update_item = true;
            } else {
              if($new_data_item->category->name != $old_data_item->category->name) {
                $update_item = true;
              }
            }
          }
        }
        if($update_item && esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") {
          $wpdb->insert($table_name, array('description'=>'<b>' . $new_data_item->title . '</b> has been updated in Custom URLs list','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      }
    }


    if(count($new_data) > count($old_data)) {
      foreach($new_data as $new_data_item) {
        $new_item = true; 
        foreach($old_data as $old_data_item) {
          if( $new_data_item->ID == $old_data_item->ID ) {
            $new_item = false;
          }
        }
        if($new_item && esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") {
          $wpdb->insert($table_name, array('description'=>'<b>' . $new_data_item->title . '</b> has been added to Custom URLs list','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      }
    }
    if(count($new_data) < count($old_data)) {
      foreach($old_data as $old_data_item) {
        $new_item = true; 
        foreach($new_data as $new_data_item) {
          if( $new_data_item->ID == $old_data_item->ID ) {
            $new_item = false;
          }
        }
        if($new_item && esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") {
          $wpdb->insert($table_name, array('description'=>'<b>' .  $old_data_item->title . '</b> has been removed from Custom URLs list','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      }
    }
  }

  if(strpos($option, 'click5_sitemap_seo_') !== false) {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(strpos($option, 'click5_sitemap_seo_post_type_') !== false && $option != "click5_sitemap_seo_post_type_post" && $option != "click5_sitemap_seo_post_type_page") { 
      $data_seo = explode("_type_", $option);
      if($data_seo[1] == 'faq') {
        $data_seo[1] = 'FAQ';
      }
      if($old_value == "" && $value == "1") {
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>"XML Sitemap for <b>" . ucfirst($data_seo[1]) . "</b> has been turned on",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      } 
      if($old_value == "1" && $value == "") {
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>"XML Sitemap for <b>" . ucfirst($data_seo[1]) . "</b> has been turned off",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      }
    }

    if(strpos($option, 'click5_sitemap_seo_xml_') !== false && $option != "click5_sitemap_seo_xml_categories" && $option != "click5_sitemap_seo_xml_tags" && $option != "click5_sitemap_seo_xml_authors" && $option != "click5_sitemap_seo_xml_docs" 
    && $option != "click5_sitemap_seo_xml_videos" && $option != "click5_sitemap_seo_xml_images") { 
      $data_seo_cutom = explode("_xml_", $option);
      $data_seo = explode("_", $data_seo_cutom[1]);
      if(strpos($data_seo[0], '-') !== false) { 
        $data_seo[0] = explode("-", $data_seo[0])[1];
      }
      if($data_seo[0] == 'faq') {
        $data_seo[0] = 'FAQ';
      }
      if($old_value == "" && $value == "1") {
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>"XML Sitemap for <b>" . ucfirst($data_seo[0]) . "</b> has been turned on",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      } 
      if($old_value == "1" && $value == "") {
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>"XML Sitemap for <b>" . ucfirst($data_seo[0]) . "</b> has been turned off",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      }
    }

    
    $array_settings_sitemap_seo = array(
      array("click5_sitemap_seo_robots_txt", "XML Sitemap for <b>robots.txt</b>"),
      array("click5_sitemap_seo_sitemap_xml", "XML Sitemap for <b>sitemap.xml</b>"),
      array("click5_sitemap_seo_include_sitemap_xml", "Include <b>sitemap.xml URL in robots.txt</b>"),

      array("click5_sitemap_seo_post_type_post", "XML Sitemap for <b>Posts</b>"),
      array("click5_sitemap_seo_post_type_page", "XML Sitemap for <b>Pages</b>"),

      array("click5_sitemap_seo_xml_categories", "XML Sitemap for <b>Categories</b>"),
      array("click5_sitemap_seo_xml_tags", "XML Sitemap for <b>Tags</b>"),
      array("click5_sitemap_seo_xml_authors", "XML Sitemap for <b>Authors</b>"),

      array("click5_sitemap_seo_xml_images", "XML Sitemap for <b>Images</b>"),
      array("click5_sitemap_seo_xml_docs", "XML Sitemap for <b>Documents</b>"),
      array("click5_sitemap_seo_xml_videos", "XML Sitemap for <b>Videos</b>"),
    );
    
    foreach($array_settings_sitemap_seo as $setting_sitemap_seo) {
      if($setting_sitemap_seo[0] == $option && $old_value == "" && $value == "1") {
        $description_sitemap = $setting_sitemap_seo[1] . " has been turned on";
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>$description_sitemap,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      } else if($setting_sitemap_seo[0] == $option && $old_value == "1" && $value == "") {
        $description_sitemap = $setting_sitemap_seo[1] . " has been turned off";
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>$description_sitemap,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      }
    }
  }

  if(strpos($option, 'click5_sitemap_blacklisted_array') !== false || strpos($option, 'click5_sitemap_seo_blacklisted_array') !== false) {
    $old_data = json_decode($old_value);
    $new_data = json_decode($value);
    global $gCurrentUser;
    global $wpdb;
    global $sitemapCurrentUser;
    $usr = $gCurrentUser;
    if(is_null($gCurrentUser) && !is_null($sitemapCurrentUser->data->user_login))
        $usr = $sitemapCurrentUser->data->user_login;
    $table_name = $wpdb->prefix . "c5_history";
    if(count($new_data) > count($old_data)) {
      foreach($new_data as $new_data_item) {
        $new_item = true; 
        foreach($old_data as $old_data_item) {
          if( $new_data_item->ID == $old_data_item->ID ) {
            $new_item = false;
          }
        }
        if($new_item) {
          if(strpos($option, 'click5_sitemap_blacklisted_array') !== false) {
            if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
              $wpdb->insert($table_name, array('description'=>'<b>' . $new_data_item->post_title . '</b> has been added to HTML blacklist','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$usr));
            }
          }
          if(strpos($option, 'click5_sitemap_seo_blacklisted_array') !== false) {
            if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
              $wpdb->insert($table_name, array('description'=>'<b>' . $new_data_item->post_title . '</b> has been added to XML blacklist','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$usr));
            }
          }
        }
      }
    }
    if(count($new_data) < count($old_data)) {
      foreach($old_data as $old_data_item) {
        $new_item = true; 
        foreach($new_data as $new_data_item) {
          if( $new_data_item->ID == $old_data_item->ID ) {
            $new_item = false;
          }
        }
        if($new_item) {
          if(strpos($option, 'click5_sitemap_blacklisted_array') !== false) {
            if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
              $wpdb->insert($table_name, array('description'=>'<b>' .  $old_data_item->post_title . '</b> has been deleted from HTML blacklist','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$usr));
            }
          }
          if(strpos($option, 'click5_sitemap_seo_blacklisted_array') !== false) {
            if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
              $wpdb->insert($table_name, array('description'=>'<b>' .  $old_data_item->post_title . '</b> has been deleted from XML blacklist','date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$usr));
            }           
          }
        }
      }
    }
  }

  if(strpos($option, 'click5_sitemap_html_blog_treat') !== false) {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history"; 
    if($option != "click5_sitemap_html_blog_treat_post" && $option != "click5_sitemap_html_blog_treat_page") { 
      $data_seo = explode("_treat_", $option);
      if($data_seo[1] == 'faq') {
        $data_seo[1] = 'FAQ';
      }
      if($old_value == "" && $value == "1") {
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") {  
          $wpdb->insert($table_name, array('description'=>"Grouping by <b>" . ucfirst($data_seo[1]) . "</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));  
        }
      } 
      if($old_value == "1" && $value == false) {
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") {  
          $wpdb->insert($table_name, array('description'=>"Grouping by <b>" . ucfirst($data_seo[1]) . "</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }       
      }
    }

    $array_settings_sitemap_url = array(
      array("click5_sitemap_html_blog_treat_post", "Grouping by <b>Posts</b>"),
      array("click5_sitemap_html_blog_treat_page", "Grouping by <b>Pages</b>")
    );

    foreach($array_settings_sitemap_url as $setting_sitemap_name) {
      if($setting_sitemap_name[0] == $option && $old_value == "" && $value == true) {
        $description_sitemap = $setting_sitemap_name[1] . " has been enabled";
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") {  
          $wpdb->insert($table_name, array('description'=>$description_sitemap,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      } else if($setting_sitemap_name[0] == $option && $old_value == "1" && $value == false) {
        $description_sitemap = $setting_sitemap_name[1] . " has been disabled";
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") {  
          $wpdb->insert($table_name, array('description'=>$description_sitemap,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      }
    }  
  }

  if(strpos($option, 'click5_sitemap_url') !== false) {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history"; 
    $array_settings_sitemap_url = array(
      array("click5_sitemap_url_target_blanc", "<b>Open links in new tab</b>")
    );

    foreach($array_settings_sitemap_url as $setting_sitemap_name) {
      if($setting_sitemap_name[0] == $option && $old_value == "" && $value == true) {
        $description_sitemap = $setting_sitemap_name[1] . " has been turned on";
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>$description_sitemap,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      } else if($setting_sitemap_name[0] == $option && $old_value == "1" && $value == false) {
        $description_sitemap = $setting_sitemap_name[1] . " has been turned off";
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>$description_sitemap,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }     
      }
    }  
  }

  if(strpos($option, 'click5_sitemap_order_list_old') !== false) {
    if($old_value != $value) {
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history"; 

      if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
          if(count((array)json_decode($value)) <= count((array)json_decode($old_value)))
        $wpdb->insert($table_name, array('description'=>"<b>HTML Sitemap</b> Order has been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
      }  
    }
  }  

  if(strpos($option, 'click5_sitemap_display') !== false) {
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history"; 
    if($option != "click5_sitemap_display_post" && $option != "click5_sitemap_display_page" && $option != "click5_sitemap_display_tag_tax" && $option != "click5_sitemap_display_cat_tax" && $option != "click5_sitemap_display_authors_tax") { 
      $data_seo = explode("_display_", $option);
      if($data_seo[1] == 'faq') {
        $data_seo[1] = 'FAQ';
      }
      if($old_value == "" && $value == "1" && esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") {
        $wpdb->insert($table_name, array('description'=>"HTML Sitemap <b>" . ucfirst($data_seo[1]) . "</b> has been turned on",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
      } 
      if($old_value == "1" && $value == false && esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") {
        $wpdb->insert($table_name, array('description'=>"HTML Sitemap <b>" . ucfirst($data_seo[1]) . "</b> has been turned off",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
      }
    }

    $array_settings_sitemap_names = array(
      array("click5_sitemap_display_post", "<b>Posts</b>"),
      array("click5_sitemap_display_page", "<b>Pages</b>"),
      array("click5_sitemap_display_tag_tax", "<b>Tags</b>"),
      array("click5_sitemap_display_cat_tax", "<b>Categories</b>"),
      array("click5_sitemap_display_authors_tax", "<b>Authors</b>")
    );
    
    foreach($array_settings_sitemap_names as $setting_sitemap_name) {
      if($setting_sitemap_name[0] == $option && $old_value == "" && $value == "1") {
        $description_sitemap = "HTML Sitemap " . $setting_sitemap_name[1] . " has been turned on";
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>$description_sitemap,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }
      } else if($setting_sitemap_name[0] == $option && $old_value == "1" && $value == null) {
        $description_sitemap = "HTML Sitemap " . $setting_sitemap_name[1] . " has been turned off";
        if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>$description_sitemap,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
        }  
      }
    }

    if($option == "click5_sitemap_display_columns") {
      if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
        $wpdb->insert($table_name, array('description'=>"Display in columns has been set to <b>" . $value . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
      }   
    }

    if($option == "click5_sitemap_display_style") {
      if($value == "merge") {
        $text = "Merge into one list";
      } 
      if($value == "group") {
        $text = "Split and group by post types";
      }
      if(esc_attr(get_option('click5_history_log_sitemap-by-click5/sitemap-by-click5.php')) == "1") { 
        $wpdb->insert($table_name, array('description'=>"Grouping Type has been set to <b>" . $text . "</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Sitemap by click5', 'user'=>$gCurrentUser));
      }
    }
  }
  
  if (strpos($option, 'click5_disable_comments_display') !== false) {
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history"; 
    $array_settings_names = array(
      array("click5_disable_comments_display_globally", "<b>Globally"),
      array("click5_disable_comments_display_post", "<b>Post"),
      array("click5_disable_comments_display_page", "<b>Page"),
      array("click5_disable_comments_display_attachment", "<b>Attachment"),
      array("click5_disable_comments_display_rpc", "<b>XML-RPC"),
      array("click5_disable_comments_display_api", "<b>REST API")
    );

    foreach($array_settings_names as $setting_name) {
      if($setting_name[0] == $option && $old_value == "" && $value == "1") {
        $description = $setting_name[1] . " Comments</b> has been turned on";
        if($setting_name[0] === "click5_disable_comments_display_rpc" || $setting_name[0] === "click5_disable_comments_display_api")
          $description = "External ".$setting_name[1] . " Comments </b> have been enabled";
        if(esc_attr(get_option('click5_history_log_disable-comments-by-click5/disable-comments-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>$description,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Disable Comments by click5', 'user'=>wp_get_current_user()->user_login));
        }       
      } else if($setting_name[0] == $option && $old_value == "1" && $value == null) {
        $description = $setting_name[1] . " Comments</b> has been turned off";
        if($setting_name[0] === "click5_disable_comments_display_rpc" || $setting_name[0] === "click5_disable_comments_display_api")
         $description = "External ".$setting_name[1] . " Comments</b> have been disabled";
        if(esc_attr(get_option('click5_history_log_disable-comments-by-click5/disable-comments-by-click5.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>$description,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Disable Comments by click5', 'user'=>wp_get_current_user()->user_login));
        }
      }
    }
  }

  if((esc_attr(get_option('click5_history_log_better-search-replace/better-search-replace.php')) == "1" && is_plugin_active('better-search-replace/better-search-replace.php')) || (esc_attr(get_option('click5_history_log_better-search-replace-pro/better-search-replace.php')) == "1" && is_plugin_active('better-search-replace-pro/better-search-replace.php'))){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    $pro = "";
    if(is_plugin_active('better-search-replace-pro/better-search-replace.php'))
      $pro = "Pro";

    if(strpos($option, 'bsr_license_key') !== false){
      if($old_value != $value){
        $wpdb->insert($table_name, array('description'=>"<b>License key</b> has been changed",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Better Search Replace '.$pro, 'user'=>$gCurrentUser));
      }
    }

    if(strpos($option, 'bsr-license-status') !== false){
      if((esc_attr(get_option('click5_history_log_better-search-replace/better-search-replace.php')) == "1" && is_plugin_active('better-search-replace/better-search-replace.php')) || (esc_attr(get_option('click5_history_log_better-search-replace-pro/better-search-replace.php')) == "1" && is_plugin_active('better-search-replace-pro/better-search-replace.php'))){
        if($old_value != $value){
          if($value == "invalid")
            $wpdb->insert($table_name, array('description'=>"<b>License key</b> has been deactivated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Better Search Replace '.$pro, 'user'=>$gCurrentUser));
          else if($value == "valid")
          $wpdb->insert($table_name, array('description'=>"<b>License key</b> has been activated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Better Search Replace '.$pro, 'user'=>$gCurrentUser));
        }
      }
    }
  }

  if(strpos($option, 'googlesitekit_analytics_settings') !== false){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $usr = $gCurrentUser;
    if(is_null($gCurrentUser))
      $usr = "WordPress Core";
    if($value != $old_value){
      if(esc_attr(get_option("click5_history_log_google-site-kit/google-site-kit.php")) == "1")
        $wpdb->insert($table_name, array('description'=>"<b>Analytics settings</b> have been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Site Kit by Google', 'user'=>$usr));
    }
  }

  if(strpos($option, 'googlesitekit_search-console_settings') !== false){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $usr = $gCurrentUser;
    if(is_null($gCurrentUser))
      $usr = "WordPress Core";
    if($value != $old_value){
      if(esc_attr(get_option("click5_history_log_google-site-kit/google-site-kit.php")) == "1")
        $wpdb->insert($table_name, array('description'=>"<b>Search Console settings</b> have been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Site Kit by Google', 'user'=>$usr));
    }
  }

  if(strpos($option, 'googlesitekit_adsense_settings') !== false){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $usr = $gCurrentUser;
    if(is_null($gCurrentUser))
      $usr = "WordPress Core";
    if($value != $old_value){
      if(esc_attr(get_option("click5_history_log_google-site-kit/google-site-kit.php")) == "1")
        $wpdb->insert($table_name, array('description'=>"<b>AdSense settings</b> have been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Site Kit by Google', 'user'=>$usr));
    }
  }

  if(strpos($option, 'googlesitekit_pagespeed-insights_settings') !== false){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $usr = $gCurrentUser;
    if(is_null($gCurrentUser))
      $usr = "WordPress Core";
    if($value != $old_value){
      if(esc_attr(get_option("click5_history_log_google-site-kit/google-site-kit.php")) == "1")
        $wpdb->insert($table_name, array('description'=>"<b>PageSpeed Insights settings</b> have been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Site Kit by Google', 'user'=>$usr));
    }
  }

  if(strpos($option, 'googlesitekit_optimize_settings') !== false){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $usr = $gCurrentUser;
    if(is_null($gCurrentUser))
      $usr = "WordPress Core";
    if($value != $old_value){
      $wpdb->insert($table_name, array('description'=>"<b>Optimize settings</b> have been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Site Kit by Google', 'user'=>$usr));
    }
  }

  if(strpos($option, 'googlesitekit_tag-manager_settings') !== false){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $usr = $gCurrentUser;
    if(is_null($gCurrentUser))
      $usr = "WordPress Core";
    if($value != $old_value){
      if(esc_attr(get_option("click5_history_log_google-site-kit/google-site-kit.php")) == "1")
        $wpdb->insert($table_name, array('description'=>"<b>Tag Manager settings</b> have been updated",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Site Kit by Google', 'user'=>$usr));
    }
  }

  if(strpos($option, 'sbi_oembed_token') !== false){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    if(esc_attr(get_option("click5_history_log_instagram-feed/instagram-feed.php")) == "1"){
      if($value['disabled'] != $old_value['disabled']){
        if($value['disabled'])
          $wpdb->insert($table_name, array('description'=>"<b>Instagram oEmbeds</b> have been turned off",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Smash Balloon Instagram Feed', 'user'=>$gCurrentUser));
        else
          $wpdb->insert($table_name, array('description'=>"<b>Instagram oEmbeds</b> have been turned on",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Smash Balloon Instagram Feed', 'user'=>$gCurrentUser));
      }
    }

  }

  if(strpos($option, 'cff_oembed_token') !== false){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    if(esc_attr(get_option("click5_history_log_instagram-feed/instagram-feed.php")) == "1"){
      if($value['disabled'] != $old_value['disabled']){
        if($value['disabled'])
          $wpdb->insert($table_name, array('description'=>"<b>Facebook oEmbeds</b> have been turned off",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Smash Balloon Instagram Feed', 'user'=>$gCurrentUser));
        else
          $wpdb->insert($table_name, array('description'=>"<b>Facebook oEmbeds</b> have been turned on",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Smash Balloon Instagram Feed', 'user'=>$gCurrentUser));
      }
    }
  }

  if(strpos($option, 'wpgmza_global_settings') !== false){
    if(esc_attr(get_option("click5_history_log_wp-google-maps/wpGoogleMaps.php")) == "1"){
      $value = (array)json_decode($value);
      $old_value = (array)json_decode($old_value);
      $diff = array_diff_assoc($value,$old_value);
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";

      $value_description = array(
          //"engine"                                            =>  "Maps Engine",
          "internal_engine"                                   =>  "Build",
          //"google_maps_api_key"                               =>  "Google Maps API Key",
          //"developer_mode"                                    =>  "Developer Mode",
          "user_interface_style"                              =>  "User Interface Style",     
          /*"wpgmza_gdpr_company_name"                          =>  "Company Name",
          "wpgmza_gdpr_retention_purpose"                     =>  "Retention Purpose(s)",
          "wpgmza_marker_xml_location"                        =>  "Marker data XML directory",
          "wpgmza_marker_xml_url"                             =>  "Marker data XML URL",*/
          "wpgmza_maps_engine"                                =>  "Maps Engine",
          "wpgmza_settings_map_full_screen_control"           =>  "Full Screen Control",
          "wpgmza_settings_map_streetview"                    =>  "StreetView",
          "wpgmza_settings_map_zoom"                          =>  "Zoom Controls",
          "wpgmza_settings_map_pan"                           =>  "Pan Controls",
          "wpgmza_settings_map_type"                          =>  "Map Type Controls",
          "wpgmza_settings_map_tilt_controls"                 =>  "Tilt Controls",
          "wpgmza_settings_map_scroll"                        =>  "Mouse Wheel Zoom",
          "wpgmza_settings_map_draggable"                     =>  "Mouse Dragging",
          "wpgmza_settings_map_clickzoom"                     =>  "Mouse Double Click Zooming",
          /*"wpgmza_settings_cat_logic"                         =>  "Category Selection Logic",
          "wpgmza_settings_filterbycat_type"                  =>  "Filter by category displayed as",
          "use_fontawesome"                                   =>  "Use FontAwesome",
          "wpgmza_load_engine_api_condition"                  =>  "Load Maps Engine API",
          "wpgmza_always_include_engine_api_on_pages"         =>  "Always include engine API on pages",
          "wpgmza_always_exclude_engine_api_on_pages"         =>  "Always exclude engine API on pages",
          "wpgmza_prevent_other_plugins_and_theme_loading_api"=>  "Prevent other plugins and theme loading API",
          "wpgmza_settings_access_level"                      =>  "Lowest level of access to the map editor",
          "wpgmza_settings_retina_width"                      =>  "Retina Icon Width",
          "wpgmza_settings_retina_height"                     =>  "Retina Icon Height",
          "wpgmza_force_greedy_gestures"                      =>  "Greedy Gesture Handling",
          "wpgmza_settings_map_open_marker_by"                =>  "Open Marker InfoWindows by",
          "wpgmza_settings_disable_infowindows"               =>  "InfoWindows",
          "wpgmza_settings_markerlist_icon"                   =>  "Hide the Icon column",
          "wpgmza_settings_markerlist_link"                   =>  "Hide the Link column",
          "wpgmza_settings_markerlist_title"                  =>  "Hide the Title column",
          "wpgmza_settings_markerlist_address"                =>  "Hide the Address column",
          "wpgmza_settings_markerlist_category"               =>  "Hide the Category column",
          "wpgmza_settings_markerlist_description"            =>  "Hide the Description column",
          "wpgmza_do_not_enqueue_datatables"                  =>  "Do not Enqueue Datatables",
          "wpgmza_default_items"                              =>  "Show X items by default",
          "wpgmza_settings_carousel_markerlist_theme"         =>  "Carousel Marker Listing Theme selection",
          "wpgmza_settings_carousel_markerlist_image"         =>  "Hide the Image",
          "wpgmza_settings_carousel_markerlist_title"         =>  "Hide the Title",
          "wpgmza_settings_carousel_markerlist_icon"          =>  "Hide the Marker Icon",
          "wpgmza_settings_carousel_markerlist_address"       =>  "Hide the Address",
          "wpgmza_settings_carousel_markerlist_description"   =>  "Hide the Description",
          "wpgmza_settings_carousel_markerlist_marker_link"   =>  "Hide the Marker Link",
          "wpgmza_settings_carousel_markerlist_directions"    =>  "Hide the Directions Link",
          "wpgmza_settings_carousel_markerlist_resize_image"  =>  "Resize Images with Timthumb",
          "carousel_lazyload"                                 =>  "Enable lazyload of images",
          "carousel_autoheight"                               =>  "Enable autoheight",
          "carousel_pagination"                               =>  "Enable pagination",
          "carousel_navigation"                               =>  "Enable navigation",
          "wpgmza_do_not_enqueue_owl_carousel"                =>  "Do not Enqueue Owl Carousel",
          "wpgmza_do_not_enqueue_owl_carousel_themes"         =>  "Do not Enqueue Owl Theme",
          "carousel_items"                                    =>  "Responsivity Settings Items",
          "carousel_items_tablet"                             =>  "Responsivity Settings Items (Tablet)",
          "carousel_items_mobile"                             =>  "Responsivity Settings Items (Mobile)",
          "carousel_autoplay"                                 =>  "Responsivity Settings Items Autoplay",
          "wpgmza_store_locator_radii"                        =>  "Store Locator Radii",
          "wpgmza_google_maps_api_key"                        =>  "Google Maps API Key",
          "wpgmza_settings_marker_pull"                       =>  "Pull marker data from",
          "wpgmza_custom_css"                                 =>  "Custom CSS",
          "wpgmza_custom_js"                                  =>  "Custom JS",
          "disable_compressed_path_variables"                 =>  "Compressed Path Variables",
          "disable_autoptimize_compatibility_fix"             =>  "Autoptimize Compatibility Fix",
          "enable_dynamic_sql_refac_filter"                   =>  "Dynamic SQL Refactors",
          "disable_automatic_backups"                         =>  "Automatic Backups",
          "wpgmza_developer_mode"                             =>  "Developer Mode",
          "wpgmza_gdpr_require_consent_before_load"           =>  "Require consent before loading Maps API",
          "wpgmza_gdpr_override_notice"                       =>  "Override GDPR Notice",
          "wpgmza_gdpr_notice_override_text"                  =>  "GDPR Override Text",*/
      );

      if(!empty($diff)){
        $current_value = array_values($diff)[0];
        if(!empty($value_description[array_keys($diff)[0]])){
          if(intval($current_value) || boolval($current_value) === false){
            if(intval($current_value) === 1){
              $wpdb->insert($table_name, array('description'=>"<b>".$value_description[array_keys($diff)[0]]."</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WP Go Maps', 'user'=>$gCurrentUser));
            }else if(intval($current_value) === 0){
              $wpdb->insert($table_name, array('description'=>"<b>".$value_description[array_keys($diff)[0]]."</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WP Go Maps', 'user'=>$gCurrentUser));
            }
          }else{
            $value_name = array_values($diff)[0];
            $value_name = ucwords(str_replace("-"," ",$value_name));
            $wpdb->insert($table_name, array('description'=>"<b>".$value_description[array_keys($diff)[0]]."</b> has been changed to <b>".$value_name.'</b>','date'=>date('Y-m-d H:i:s'), 'plugin'=>'WP Go Maps', 'user'=>$gCurrentUser));
          }
        }
      }
    }
  }

  if(esc_attr(get_option("click5_history_log_jetpack/jetpack.php")) == "1"){

    if(strpos($option, 'carousel_display_exif') !== false){ 
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $mode = "";
      if($value === true)
        $mode = "enabled";
      else if($value === false)
        $mode = "disabled";

        $wpdb->insert($table_name, array('description'=>"<b>Show photo Exif metadata in carousel</b> has been ".$mode,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
    } 

    if(strpos($option, 'carousel_display_comments') !== false){ 
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $mode = "";
      if($value === true)
        $mode = "enabled";
      else if($value === false)
        $mode = "disabled";

        $wpdb->insert($table_name, array('description'=>"<b>Show comments area in carousel</b> has been ".$mode,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
    } 

    if(strpos($option, 'carousel_background_color') !== false){ 
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value != $old_value){
        $wpdb->insert($table_name, array('description'=>"<b>Carousel color scheme</b> has been changed to ".ucfirst($value),'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
      }
    } 

    if(strpos($option, 'jetpack_comment_form_color_scheme') !== false){ 
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value != $old_value){
        $wpdb->insert($table_name, array('description'=>"Comments <b>Color scheme</b> has been changed to ".ucfirst($value),'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
      }
    }

    if(strpos($option, 'highlander_comment_form_prompt') !== false){ 
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value != $old_value){
        $wpdb->insert($table_name, array('description'=>"<b>Comment form introduction</b> has been changed to ".ucfirst($value),'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
      }
    }
    
    if(strpos($option, 'jetpack_relatedposts') !== false){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";

      if($value['show_headline'] != $old_value['show_headline']){
        if($value['show_headline'] == true){
          $wpdb->insert($table_name, array('description'=>"<b>Highlight related content with a heading</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
        }else{
          $wpdb->insert($table_name, array('description'=>"<b>Highlight related content with a heading</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
        }
      }

      if($value['show_thumbnails'] != $old_value['show_thumbnails']){
        if($value['show_thumbnails'] == true){
          $wpdb->insert($table_name, array('description'=>"<b>Show a thumbnail image where available</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
        }else{
          $wpdb->insert($table_name, array('description'=>"<b>Show a thumbnail image where available</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
        }
      }
    }

    if(strpos($option, 'jetpack_blocks_disabled') !== false){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $mode = "";
      if($value === false)
        $mode = "enabled";
      else if($value === true)
        $mode = "disabled";
        
      $wpdb->insert($table_name, array('description'=>"<b>Jetpack Blocks</b> has been ".$mode,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
    }
  
    if(strpos($option,"jetpack_testimonial") !== false){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $mode = "";
  
      if($value === false)
        $mode = "disabled";
      else if($value === true)
        $mode = "enabled";
        
      $wpdb->insert($table_name, array('description'=>"<b>Testimonials</b> has been ".$mode,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
    }
  
    if(strpos($option,"jetpack_portfolio") !== false){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $mode = "";
  
      if($value === false)
        $mode = "disabled";
      else if($value === true)
        $mode = "enabled";
        
      $wpdb->insert($table_name, array('description'=>"<b>Portoflios</b> has been ".$mode,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
    }
  
    if(strpos($option,"wpcom_publish_comments_with_markdown") !== false){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $mode = "";
  
      if($value === false)
        $mode = "disabled";
      else if($value === true)
        $mode = "enabled";
        
      $wpdb->insert($table_name, array('description'=>"<b>Markdown use for comments</b> has been ".$mode,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
    }
  
    if(strpos($option,"wpcom_publish_posts_with_markdown") !== false){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $mode = "";
  
      if($value === false)
        $mode = "disabled";
      else if($value === true)
        $mode = "enabled";
        
      $wpdb->insert($table_name, array('description'=>"<b>Write posts or pages in plain-text Markdown syntax</b> has been ".$mode,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
    }
  
    if(strpos($option,"infinite_scroll") !== false){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $wpdb->insert($table_name, array('description'=>"<b>Infinite scroll</b> has been changed",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Jetpack', 'user'=>$gCurrentUser));
    }
  }

  if(esc_attr(get_option("click5_history_log_duplicate-post/duplicate-post.php")) == "1"){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $optionDesc = array(
      "thumbnail"             => "Featured Image",
      "format"                => "Post Format",
      "menuorder"             => "Menu Order",
      "blacklist"             => "Do not copy these fields",
      "taxonomies_blacklist"  => "Do not copy these taxonomies",
      "aioseo_manager"        => "SEO Manager",
      "aioseo_editor"         => "SEO Editor",
      "wp_navigation"         => "Navigation Menus",
      "wp-rest-api-log"       => "REST API Log Entries",
      "new_draft"             => "New Draft",
      "rewrite_republish"     => "Rewrite & Republish",
      "bulkactions"           => "Bulk Actions",
      "adminbar"              => "Admin bar",
      "submitbox"             => "Edit screen",
      "row"                   => "Post list",
      "in_post_states"        => "After the title in the Post list",
      "meta_box"              => "In a metabox in the Edit screen",
      "column"                => "In a column in the Post list"


    );

    //***************** */
    //DUPLICATE POST COPY
    //***************** */
    if(strpos($option,"duplicate_post_copy") !== false){
      $optionName = str_replace("duplicate_post_copy","",$option);
      if(!empty($optionDesc[$optionName])){
        $optionName = $optionDesc[$optionName];
      }else{
        $optionName = ucfirst($optionName);
      }
      if($value != $old_value){
        if($value === "1")
        $wpdb->insert($table_name, array('description'=>"<b>".$optionName."</b> in <b>Post/page elements to copy</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
      else
        $wpdb->insert($table_name, array('description'=>"<b>".$optionName."</b> in <b>Post/page elements to copy</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
      }
    }else if(strpos($option,"duplicate_post_") !== false){
      $optionName = str_replace("duplicate_post_","",$option);
      //***************** */
      //DUPLICATE POST TAXONOMIES BLACKLIST
      //***************** */
      if($optionName == "taxonomies_blacklist"){

        if(!is_array($value))
          $value = array();
        if(!is_array($old_value))
          $old_value = array();

        $old_diff = array_diff($old_value,$value);
        $new_diff = array_diff($value,$old_value);

        if(count($old_diff) != 0 && count($new_diff) < count($old_diff)){
          foreach($old_diff as $index => $value){
            $wpdb->insert($table_name, array('description'=>"<b>".get_taxonomy($value)->label."</b> in <b>Do not copy these taxonomies</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
          }
        }

        if(count($new_diff) != 0 && count($new_diff) > count($old_diff)){
          foreach($new_diff as $index => $value){
            $wpdb->insert($table_name, array('description'=>"<b>".get_taxonomy($value)->label."</b> in <b>Do not copy these taxonomies</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
          }
        }  
      //***************** */
      //DUPLICATE POST ROLES
      //***************** */
      }else if($optionName == "roles"){
        if(!is_array($value))
          $value = array();
        if(!is_array($old_value))
          $old_value = array();
  
        $old_diff = array_diff($old_value,$value);
        $new_diff = array_diff($value,$old_value);

        if(count($old_diff) != 0 && count($new_diff) < count($old_diff)){
          foreach($old_diff as $index => $value){
            if(empty($optionDesc[$value]))
            {
              $value = str_replace("_"," ",$value);
              $value = ucfirst($value);
            }else
            {
              $value = $optionDesc[$value];
            }
            $wpdb->insert($table_name, array('description'=>"<b>".$value."</b> in <b>Roles allowed to copy</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
          }
        }

        if(count($new_diff) != 0 && count($new_diff) > count($old_diff)){
          foreach($new_diff as $index => $value){
            if(empty($optionDesc[$value]))
            {
              $value = str_replace("_"," ",$value);
              $value = ucfirst($value);
            }else
            {
              $value = $optionDesc[$value];
            }
            $wpdb->insert($table_name, array('description'=>"<b>".$value."</b> in <b>Roles allowed to copy</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
          }
        }
      //***************** */
      //DUPLICATE POST TYPES
      //***************** */ 
      }else if($optionName == "types_enabled"){
        if(!is_array($value))
          $value = array();
        if(!is_array($old_value))
          $old_value = array();
  
        $old_diff = array_diff($old_value,$value);
        $new_diff = array_diff($value,$old_value);

        if(count($old_diff) != 0 && count($new_diff) < count($old_diff)){
          foreach($old_diff as $index => $value){
            if(empty($optionDesc[$value]))
            {
              $value = str_replace("_"," ",$value);
              $value = ucfirst($value);
            }else
            {
              $value = $optionDesc[$value];
            }
            $wpdb->insert($table_name, array('description'=>"<b>".$value."</b> in <b>Enable for these post types</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
          }
        }

        if(count($new_diff) != 0 && count($new_diff) > count($old_diff)){
          foreach($new_diff as $index => $value){
            if(empty($optionDesc[$value]))
            {
              $value = str_replace("_"," ",$value);
              $value = ucfirst($value);
            }else
            {
              $value = $optionDesc[$value];
            }
            $wpdb->insert($table_name, array('description'=>"<b>".$value."</b> in <b>Enable for these post types</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
          }
        }
      //***************** */
      //DUPLICATE POST SHOW LINKS
      //***************** */
      }else if($optionName == "show_link"){
        if(!is_array($value))
          $value = array();
        if(!is_array($old_value))
          $old_value = array();
  
        $old_diff = array_diff(array_keys($old_value),array_keys($value));
        $new_diff = array_diff(array_keys($value),array_keys($old_value));

        if(count($old_diff) != 0 && count($new_diff) < count($old_diff)){
          foreach($old_diff as $index => $value){
            if(empty($optionDesc[$value]))
            {
              $value = str_replace("_"," ",$value);
              $value = ucfirst($value);
            }else
            {
              $value = $optionDesc[$value];
            }
            $wpdb->insert($table_name, array('description'=>"<b>".$value."</b> in <b>Show these links</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
          }
        }

        if(count($new_diff) != 0 && count($new_diff) > count($old_diff)){
          foreach($new_diff as $index => $value){
            if(empty($optionDesc[$value]))
            {
              $value = str_replace("_"," ",$value);
              $value = ucfirst($value);
            }else
            {
              $value = $optionDesc[$value];
            }
            $wpdb->insert($table_name, array('description'=>"<b>".$value."</b> in <b>Show these links</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
          }
        }
      }else if($optionName == "show_link_in"){
        if(!is_array($value))
          $value = array();
        if(!is_array($old_value))
          $old_value = array();
  
        $old_diff = array_diff(array_keys($old_value),array_keys($value));
        $new_diff = array_diff(array_keys($value),array_keys($old_value));

        if(count($old_diff) != 0 && count($new_diff) < count($old_diff)){
          foreach($old_diff as $index => $value){
            if(empty($optionDesc[$value]))
            {
              $value = str_replace("_"," ",$value);
              $value = ucfirst($value);
            }else
            {
              $value = $optionDesc[$value];
            }
            $wpdb->insert($table_name, array('description'=>"<b>".$value."</b> in <b>Show links in</b> has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
          }
        }

        if(count($new_diff) != 0 && count($new_diff) > count($old_diff)){
          foreach($new_diff as $index => $value){
            if(empty($optionDesc[$value]))
            {
              $value = str_replace("_"," ",$value);
              $value = ucfirst($value);
            }else
            {
              $value = $optionDesc[$value];
            }
            $wpdb->insert($table_name, array('description'=>"<b>".$value."</b> in <b>Show links in</b> has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
          }
        }
      //***************** */
      //DUPLICATE POST SHOW ORIGINAL
      //***************** */
      }else if(strpos($optionName,"show_original") !== false){
        $optionName = str_replace("show_original_","",$optionName);
        $action = "enabled";
        if($value != $old_value){
          if($value == "1")
            $action = "enabled";
          else if($old_value == "1")
            $action = "disabled";

          if(!empty($optionDesc[$optionName]))
            $wpdb->insert($table_name, array('description'=>"<b>".$optionDesc[$optionName]."</b> in <b>Show original item</b> has been ".$action,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));

        } 
      //***************** */
      //DUPLICATE POST SHOW NOTICE
      //***************** */
      }else if($optionName == "show_notice"){
        if($value != $old_value){
          if($value == "1")
            $action = "enabled";
          else if($old_value == "1")
            $action = "disabled";

          $wpdb->insert($table_name, array('description'=>"<b>Show welcome notice</b> has been ".$action,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
        }
      }
      //***************** */
      //DUPLICATE POST INPUT TEXT
      //***************** */
      else{
        if(!empty($optionDesc[$optionName])){
          $optionName = $optionDesc[$optionName];
        }else{
          $optionName = str_replace("_"," ",$optionName);
          $optionName = ucfirst($optionName);
        }
  
        if($value != $old_value){
          if(!empty($value)){
            $new_opt_value = $value;
          }else{
            $new_opt_value = "empty";
          }
  
          if(!empty($old_value)){
            $old_opt_value = $old_value;
          }else{
            $old_opt_value = "empty";
          }
  
          $wpdb->insert($table_name, array('description'=>"<b>".$optionName."</b> has been changed from ".$old_opt_value." to <b>".$new_opt_value."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Yoast Duplicate Post', 'user'=>$gCurrentUser));
         }
      }
    }
  }

  if(esc_attr(get_option("click5_history_log_updraftplus/updraftplus.php")) == "1"){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    $optionDescription = array(
			'updraft_interval'                    =>  "Files backup schedule interval",
			'updraft_interval_database'           =>  "Database backup schedule interval",  
			'updraft_retain'                      =>  "Files backup schedule - retain",
			'updraft_retain_db'                   =>  "Database backup schedule - retain",
			'updraft_service'                     =>  "Remote storage",
      'updraft_dropbox'                     =>  "Dropbox",
			'updraft_googledrive'                 =>  "Google Drive",
      'updraft_googlecloud'                 =>  "Google Cloud",
      'updraft_email'                       =>  "Email",
			'updraft_sftp'                        =>  "SFTP/SCP",
      'updraft_ftp'                         =>  "FTP",
			'updraft_s3'                          =>  "Amazon S3",
      'updraft_s3_login',
			'updraft_s3_pass',
			'updraft_s3_remote_path',
			'updraft_s3generic'                   =>  "S3-Compatible (Generic)",
      'updraft_s3generic_login'             =>  "",
			'updraft_s3generic_pass',
			'updraft_s3generic_remote_path',
			'updraft_s3generic_endpoint',
			'updraft_dreamhost'                   =>  "DreamObjects",
      'updraft_dreamobjects_login',
			'updraft_dreamobjects_pass',
			'updraft_dreamobjects_remote_path',
			'updraft_dreamobjects'                =>  "DreamObjects",
			'updraft_webdav'                      =>  "WebDAV",
			'updraft_openstack'                   =>  "OpenStack (Swift)",
			'updraft_onedrive'                    =>  "Microsoft OneDrive",
			'updraft_azure'                       =>  "Microsoft Azure",
			'updraft_cloudfiles'                  =>  "Rockspace Cloud Files",
			'updraft_cloudfiles_user',
			'updraft_cloudfiles_apikey',
			'updraft_cloudfiles_path',
			'updraft_cloudfiles_authurl',
      'updraft_backblaze'                   =>  "Backblaze",
      'updraft_updraftvault'                =>  "UpdraftPlus Vault",
      "host"                                =>  "Host/Server",
      "user"                                =>  "User/Login",
      "pass"                                =>  "Password",
      "password"                            =>  "Password",
      "path"                                =>  "Path",
      "passive"                             =>  "Passive mode",
      "accesskey"                           =>  "Access key",
      "secretkey"                           =>  "Secret key",
      "endpoint"                            =>  "Endpoint",
      "bucket_access_style"                 =>  "Bucket access style",
      "authurl"                             =>  "Authentication URL",
      "tenant"                              =>  "Tenant",
      "region"                              =>  "Region",
      "container"                           =>  "Container",
      'apikey'                              =>  "API Key",

    );

    if(strpos($option,"updraft") !== false){
      if($value != $old_value){
        if(is_string($value) || is_null($value)){
          if($option == "updraft_service"){
            $old_service = "";
            $new_service = "";
            if(!empty($optionDescription["updraft_".$value])){
              $new_service = $optionDescription["updraft_".$value];
            }else{
              if(!empty($value))
                $new_service = ucfirst($value);
              else
                $new_service = "empty";
            }

            if(!empty($optionDescription["updraft_".$old_value])){
              $old_service = $optionDescription["updraft_".$old_value];
            }else{
              if(!empty($old_value))
                $old_service = ucfirst($old_value);
              else
                $old_service = "empty";
            }
            $optionName = $optionDescription[$option];
            $wpdb->insert($table_name, array('description'=>"<b>".$optionName."</b> has been changed from <b>".$old_service."</b> to <b>".$new_service."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'UpdraftPlus - Backup/Restore', 'user'=>$gCurrentUser));

          }else{
            if(!empty($optionDescription[$option]) && $option != "updraft_email"){
              $optionName = $optionDescription[$option];
              $updraft_interval = "";
              if(strpos($value,"every") !== false && strpos($value,"hours") !== false){
                $updraft_interval = str_replace("every","",$value);
                $updraft_interval = str_replace("hours","",$updraft_interval);
                $updraft_interval = "Every ".$updraft_interval." hours";
              }else{
                $updraft_interval = ucfirst($value);
              }
              $wpdb->insert($table_name, array('description'=>"<b>".$optionName."</b> has been changed to <b>".$updraft_interval."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'UpdraftPlus - Backup/Restore', 'user'=>$gCurrentUser));

            }
          }
        }else{
          if(!empty($optionDescription[$option])){
            $optionName = $optionDescription[$option];
            if(!is_array($value)){
              $updraft_copy = "";
              if($value > 1)
                $updraft_copy = "copies";
              else
                $updraft_copy = "copy";
              $wpdb->insert($table_name, array('description'=>"<b>".$optionName."</b> has been changed to <b>".$value."</b> ".$updraft_copy,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'UpdraftPlus - Backup/Restore', 'user'=>$gCurrentUser));
            }/*else{
              if(isset($value['settings']) && is_array($value['settings'])){
                $new_val = array_values($value['settings'])[0];
                $old_val = array_values($old_value['settings'])[0];
                $diff = array_diff_assoc($new_val,$old_val);

                foreach($diff as $key => $index){
                  if(!empty($optionDescription[$key])){
                    $wpdb->insert($table_name, array('description'=>"<b>".$optionDescription[$key]."</b> has been changed in <b>".$optionName."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'UpdraftPlus - Backup/Restore', 'user'=>$gCurrentUser));
                  }
                }
              }
            } */  
          }
        }
      }
    }
  }

  if($option == "updraft_backup_history"){
    if(!is_array($value))
      $value = array();

    if(!is_array($old_value))
      $old_value = array();

    $new_diff = array_values(array_diff_assoc(array_keys($value),array_keys($old_value)));
    $old_diff = array_values(array_diff_assoc(array_keys($old_value),array_keys($value)));
    $user = $gCurrentUser;
    if(is_null($user))
      $user = 'UpdraftPlus - Backup/Restore';

    if(count($new_diff) > count($old_diff)){
      $wpdb->insert($table_name, array('description'=>"<b>Backup</b> has been created",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'UpdraftPlus - Backup/Restore', 'user'=>$user));
    }else if(count($new_diff) < count($old_diff)){
      if(!empty($old_value[$old_diff[0]])){
        $fileName = "";
        $backup_data = $old_value[$old_diff[0]];
        if(isset($backup_data['plugins'])){
          $fileName = $backup_data['plugins'][0];
        }else if(isset($backup_data['uploads'])){
          $fileName = $backup_data['uploads'][0];
        }else if(isset($backup_data['others'])){
          $fileName = $backup_data['others'][0];
        }else if(isset($backup_data['db'])){
          $fileName = $backup_data['db'];
        }else if(isset($backup_data['themes'])){
          $fileName = $backup_data['themes'][0];
        }
        $fileName = str_replace("backup_","",$fileName);
        $pos = strpos($fileName,"_");
        $fileName = substr($fileName,0,$pos);
        $fileNameDt = explode("-",$fileName);

        $newData = mktime($fileNameDt[3][0].$fileNameDt[3][1],$fileNameDt[3][2].$fileNameDt[3][3],0,$fileNameDt[1],$fileNameDt[2],$fileNameDt[0]);
        $newData = date("M d, Y h:i",$newData);
        $wpdb->insert($table_name, array('description'=>"Backup from <b>".$newData."</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'UpdraftPlus - Backup/Restore', 'user'=>$gCurrentUser));
      }
    }
  }

  if(esc_attr(get_option("click5_history_log_loco-translate/loco.php")) == "1"){
    if(strpos($option,"loco_settings") !== false){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
  
      $description = array(
        'gen_hash'              =>  "Generate hash tables",
        'use_fuzzy'             =>  "Include Fuzzy strings",
        'fuzziness'             =>  "Fuzzy matching tolerance",
        'num_backups'           =>  "Number of backups to keep of each file",
        'php_alias'             =>  "Scan PHP files with extensions",
        'jsx_alias'             =>  "Scan JavaScript files with extensions",
        'fs_persist'            =>  "Save credentials in session",
        'fs_protect'            =>  "Modification of installed files",
        'pot_protect'           =>  "Editing of POT (template) files",
        'pot_expected'          =>  "Sync with source when template missing",
        'max_php_size'          =>  "Skip PHP files larger than",
        'po_utf8_bom'           =>  "Add UTF-8 byte order mark",
        'po_width'              =>  "Maximum line length",
        'jed_pretty'            =>  "Pretty formatting",
        'jed_clean'             =>  "Delete redundant files",
        'ajax_files'            =>  "Enable Ajax file uploads",
        'deepl_api_key'         =>  "DeepL Translator API key",
        'deepl_api_url'         =>  "DeepL Translator API URL",
        'google_api_key'        =>  "Google Translate API key",
        'microsoft_api_key'     =>  "Microsoft Translator API key",
        'microsoft_api_region'  =>  "Microsoft Translator API region",
        'lecto_api_key'         =>  "Lecto AI API key"
      );
  
      $selectDescription = array(
        "Allow",
        "Allow (with warning)",
        "Disallow",
        "for" => array(
          "fs_protect",
          "pot_protect",
          "pot_expected"
        )
      );
      foreach($value['d'] as $key => $index){
        $isChanged = false;
        if(!is_array($index)){
          if($index != $old_value['d'][$key]){
            if(is_bool($index)){
              if($index)
                $mode = "enabled";
              else
                $mode = "disabled";
            }else{
              if(array_search($key,$selectDescription['for']) !== false){
                $mode = "changed to ".$selectDescription[$index];
              }else{
                if(!empty($index))
                  $mode = "changed to ".$index;
                else
                  $mode = "changed to empty";
              } 
            }
              $isChanged = true;
          }
        }else{
          $mode = "changed to ";
            if(count($index) != count($old_value['d'][$key])){
              if(count($index) != 0)
                $mode .= implode(",",$index);
              else
                $mode .= "empty";
                
              $isChanged = true;
            }
        }
  
        if( (isset($description[$key]) && !empty($description[$key])) && $isChanged){
          $wpdb->insert($table_name, array('description'=>"<b>".$description[$key]."</b> has been ".$mode,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));   
        }
          
      }
  
      if(!isset($_REQUEST['caps']))
        $_REQUEST['caps'] = array();
  
      if(isset($_REQUEST['caps'])){
        foreach(wp_roles()->roles as $role => $data){
          if(isset($_REQUEST['caps'][$role]['loco_admin'])){
            $roleCaps = $data['capabilities'];
            if(!isset($roleCaps['loco_admin']) && $role != "administrator"){
              $wpdb->insert($table_name, array('description'=>"Access for <b>".$data['name']."</b> has been granted" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
            }
          }else if(!isset($_REQUEST['caps'][$role]['loco_admin'])){
            $roleCaps = $data['capabilities'];
            if(isset($roleCaps['loco_admin']) && $role != "administrator"){
              $wpdb->insert($table_name, array('description'=>"Access for <b>".$data['name']."</b> has been removed" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Loco Translate', 'user'=>$gCurrentUser));
            }
          }
        }
      }
    }
  }

  if(esc_attr(get_option("click5_history_log_duplicator/duplicator.php")) == "1"){
    if(strpos($option,"duplicator_settings") !== false){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";

      $duplicatorSettingDescription = array(
        'uninstall_settings'          => "Delete Plugin Settings",
        'uninstall_files'             => "Delete Entire Storage Directory",
        'uninstall_tables'            => "",
        'package_debug'               => "Debug options throughout user interface",
        'package_mysqldump'           => "",
        'package_mysqldump_path'      => "",
        'package_mysqldump_qrylimit'  => "",
        'package_zip_flush'           => "",
        'installer_name_mode'         => "",
        'storage_position'            => "",
        'storage_htaccess_off'        => "",
        'archive_build_mode'          => "",
        'skip_archive_scan'           => "Skip Archive scan",
        'unhook_third_party_js'       => "Foreign JavaScript",
        'unhook_third_party_css'      => "Foreign CSS",
        'active_package_id'           => "",
        'wpfront_integrate'           => "Custom Roles",
        'trace_log_enabled'           => "Trace Log",
      );

      $diff = array_diff_assoc($value, $old_value);
      foreach($diff as $optName => $optVal){
        $mode = "";
        if($optName == "unhook_third_party_js" || $optName == "unhook_third_party_css"){
          if(intval($optVal))
            $mode = "disabled";
          else
            $mode = "enabled";
        }else{
          if(intval($optVal))
            $mode = "enabled";
          else
            $mode = "disabled";
        }
        if(isset($duplicatorSettingDescription[$optName]) && !empty($duplicatorSettingDescription[$optName]))
          $wpdb->insert($table_name, array('description'=>"<b>".$duplicatorSettingDescription[$optName]."</b> has been ".$mode ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Duplicator', 'user'=>$gCurrentUser));
      }
    }
  }

  if(esc_attr(get_option("click5_history_log_polylang/polylang.php")) == "1"){
    if(strpos($option,"polylang") !== false){
      $polylangSettingDescription = array(
        "force_lang_0"      => "The language is set from content",
        "force_lang_1"      => "The language is set from the directory name in pretty permalinks",
        "force_lang_2"      => "The language is set from the subdomain name in pretty permalinks",
        "force_lang_3"      => "The language is set from different domains",
        "rewrite_0"         => "Remove /language/ in pretty permalinks",
        "rewrite_1"         => "Keep /language/ in pretty permalinks",
        "browser"           => "Detect browser language",
        "media_support"     => "Media",
        "hide_default"      => "Hide URL language information for default language",
        "sync"              => "Synchronization",
        "_thumbnail_id"     => "Featured image",
        "_wp_page_template" => "Page template",
        "post_meta"         => "Custom fields",
        "post_date"         => "Publish date",
        "menu_order"        => "Page order",
        "post_parent"       => "Page parent"

      );

      if(is_array($value) && is_array($old_value))
        $diff = array_diff_assoc($value,$old_value);

      if(is_array($diff)){
        foreach($diff as $key => $index){
          $settingName = "";
          if(!is_array($index)){
            if(isset($polylangSettingDescription[$key."_".$index])){
              $setting_name = $polylangSettingDescription[$key."_".$index];
            }else{
              if(isset($polylangSettingDescription[$key])){
                $setting_name = $polylangSettingDescription[$key];
              }
            }
            if($key == "force_lang" || $key == "rewrite"){
              $wpdb->insert($table_name, array('description'=>"<b>URL Modifications</b> has been changed to <b>".$setting_name."</b>" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
            }
            else{
              if($index){
                $wpdb->insert($table_name, array('description'=>"<b>".$setting_name."</b> has been enabled" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
              }else{
                $wpdb->insert($table_name, array('description'=>"<b>".$setting_name."</b> has been disabled" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
              }
            }
          }
        }
      }
      
      if(is_array($value)){
        foreach($value as $key => $index){
          $setting_name = "";
          if(is_array($index)){
            if(isset($polylangSettingDescription[$key])){
              $setting_name = $polylangSettingDescription[$key];
            }
            if((count($index) != 0 || count($old_value[$key]) != 0) && !empty($setting_name)){

                $added = array_diff($index,$old_value[$key]);
                $removed = array_diff($old_value[$key],$index);
  
              if(count($added) != 0){
                foreach($added as $key => $index){
                  if(isset($polylangSettingDescription[$index])){
                    if(array_search($old_value[$key],$index) === false)
                      $wpdb->insert($table_name, array('description'=>"<b>".$polylangSettingDescription[$index]."</b> has been disabled in Synchronization tab" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
                    else
                      $wpdb->insert($table_name, array('description'=>"<b>".$polylangSettingDescription[$index]."</b> has been enabled in Synchronization tab" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
                  }
                  else{
                    $name = str_replace("_"," ",$index);
                    $name = ucfirst($name);
  
                    if(array_search($old_value[$key],$index) === false)
                      $wpdb->insert($table_name, array('description'=>"<b>".$name."</b> has been disabled in Synchronization tab" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
                    else
                      $wpdb->insert($table_name, array('description'=>"<b>".$name."</b> has been enabled in Synchronization tab" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
                  }
  
                }
              }
  
              if(count($removed) != 0){
                foreach($removed as $key => $index){
                  if(isset($polylangSettingDescription[$index])){
                    if(array_search($value[$key],$index) === false)
                      $wpdb->insert($table_name, array('description'=>"<b>".$polylangSettingDescription[$index]."</b> has been enabled in Synchronization tab" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
                    else
                      $wpdb->insert($table_name, array('description'=>"<b>".$polylangSettingDescription[$index]."</b> has been disabled in Synchronization tab" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
                  }
                  else{
                    $name = str_replace("_"," ",$index);
                    $name = ucfirst($name);
                    if(array_search($value[$key],$index) === false)
                      $wpdb->insert($table_name, array('description'=>"<b>".$name."</b> has been enabled in Synchronization tab" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
                    else
                      $wpdb->insert($table_name, array('description'=>"<b>".$name."</b> has been disabled in Synchronization tab" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Polylang', 'user'=>$gCurrentUser));
                  }
  
                }
              }
            }
          }
        }
      }

    }
  }

  if(esc_attr(get_option("click5_history_log_limit-login-attempts-reloaded/limit-login-attempts-reloaded.php")) == "1"){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";

    if(strpos($option,"limit_login_") !== false){
      $optionName = str_replace("limit_login_","",$option);
      $limit_login_description = array(
        "gdpr"                     => "GDPR compliance",
        "gdpr_message"             => "GDPR message",
        "show_top_level_menu_item" => "Show top-level menu item",
        "hide_dashboard_widget"    => "Hide Dashboard Widget",
        "show_warning_badge"       => "Show Warning Badge",
        "allowed_retries"          => "Lockout allowed retries",
        "lockout_duration"         => "First lockout time",
        "valid_duration"           => "Lockout reset",
        "allowed_lockouts"         => "Max Lockout",
        "long_duration"            => "Lockout time",
        "notify_email_after"       => "Notify on lockout after",
        "trusted_ip_origins"       => "Trusted IP Origins",
        "admin_notify_email"       => "Email notify on lockout",
        "active_app"               => "Active App",
        "lockout_notify"           => "Notify on lockout"
      );

      $settingName = "";

      if(isset($limit_login_description[$optionName])){
        $settingName = $limit_login_description[$optionName];
      

        $mode = "";
        if(!is_array($value)){
          if($value != $old_value){
            if(is_int($value) !== false){
              if(($value == 1 || $value == 0) && $optionName != "notify_email_after" && $optionName != "long_duration" && $optionName != "valid_duration" && $optionName != "allowed_lockouts" && $optionName != "lockout_duration" && $optionName != "allowed_retries"){
                if($value)
                  $mode = "enabled";
                else
                  $mode = "disabled";

              }else if($value > 0){
                $timeUnit = "";
                if($optionName == "lockout_duration"){
                  $value = $value/60;
                  if($value > 1)
                    $timeUnit = " minutes";
                  else
                    $timeUnit = " minute";
                } 
                else if ($optionName == "valid_duration" || $optionName == "long_duration"){
                  $value = $value/60/60;
                  if($value > 1)
                    $timeUnit = " hours";
                  else
                    $timeUnit = " hour";
                }      

                if($optionName == "allowed_lockouts")
                  $mode = "increased to <b>".$value."</b>";
                else
                  $mode = "changed to <b>".$value."</b>".$timeUnit;
              }
            }else if(is_string($value)){
              if($optionName == "gdpr_message")
                $mode = "changed";
              else if($optionName == "lockout_notify"){
                if(empty($value))
                  $mode = "disabled";
                else
                  $mode = "enabled";
              }
              else{
                  $mode = "changed to ".$value;
              }
                
            }
            if(!empty($mode))
              $wpdb->insert($table_name, array('description'=>"<b>".$settingName."</b> has been ".$mode ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Limit Login Attempts Reloaded', 'user'=>$gCurrentUser));
          }
        }else{
          $new_diff = array_diff($value,$old_value);
          $old_diff = array_diff($old_value,$value);
          if(count($new_diff) != 0 || $old_diff != 0){
            if(!empty($value)){
              $mode = "changed to ".implode(",",$value);
            }else{
              $mode = "changed to empty";
            }
            $wpdb->insert($table_name, array('description'=>"<b>".$settingName."</b> has been ".$mode ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Limit Login Attempts Reloaded', 'user'=>$gCurrentUser));
          }
        }
      }else if($optionName == "lockouts"){
        if(empty($value))
          $value = array();
        if(empty($old_value))
          $old_value = array();

        $newLock = array_diff_assoc($value,$old_value);
        $newUnlock = array_diff_assoc($old_value,$value);
        $retries = get_option("limit_login_retries");
        $allowedRetires = intval(esc_attr(get_option("limit_login_allowed_lockouts"))) * intval(esc_attr(get_option("limit_login_allowed_retries")));
        $retireTime = (intval(esc_attr(get_option("limit_login_lockout_duration")))/60);
        $lockoutTime = (intval(esc_attr(get_option("limit_login_valid_duration")))/60/60);
        foreach($newLock as $userIP => $data){
          if(isset($retries[$userIP])){
            $userRetries = $retries[$userIP];
            if(($userRetries+1) % $allowedRetires == 0){
              if($lockoutTime > 1)
                $retireTimeMessage = $retireTime." hours";
              else
                $retireTimeMessage = $retireTime." hour";

              //$wpdb->insert($table_name, array('description'=>"Login for user by IP <b>".$userIP."</b> has been blocked for ".$retireTimeMessage." from reason <b>".$allowedRetires."</b> wrong login attempts" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Limit Login Attempts Reloaded', 'user'=>'Limit Login Attempts Reloaded'));
              $wpdb->insert($table_name, array('description'=>"Login for user by IP <b>".$userIP."</b> has been blocked for ".$retireTimeMessage,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Limit Login Attempts Reloaded', 'user'=>'Limit Login Attempts Reloaded'));
            }else{
              $userRetries = $retries[$userIP];
              if($retireTime > 1)
                $retireTimeMessage = $retireTime." minutes";
              else
                $retireTimeMessage = $retireTime." minute";

              //$wpdb->insert($table_name, array('description'=>"Login for user by IP <b>".$userIP."</b> has been blocked for ".$retireTimeMessage." from reason <b>".($userRetries+1)."</b> wrong login attempts" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Limit Login Attempts Reloaded', 'user'=>'Limit Login Attempts Reloaded'));
              $wpdb->insert($table_name, array('description'=>"Login for user by IP <b>".$userIP."</b> has been blocked for ".$retireTimeMessage,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Limit Login Attempts Reloaded', 'user'=>'Limit Login Attempts Reloaded'));
            }
          }else{
            if($retireTime > 1)
                $retireTimeMessage = $retireTime." minutes";
            else
                $retireTimeMessage = $retireTime." minute";

            //$wpdb->insert($table_name, array('description'=>"Login for user by IP <b>".$userIP."</b> has been blocked for ".$retireTimeMessage." from reason <b>1</b> wrong login attempts" ,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Limit Login Attempts Reloaded', 'user'=>'Limit Login Attempts Reloaded'));
            $wpdb->insert($table_name, array('description'=>"Login for user by IP <b>".$userIP."</b> has been blocked for ".$retireTimeMessage,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Limit Login Attempts Reloaded', 'user'=>'Limit Login Attempts Reloaded'));
          }
        }
      }
    }
  }
  
  if(strpos($option, 'backwpup_cfg') !== false){
    if(esc_attr(get_option("click5_history_log_backwpup/backwpup.php")) === "1"){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $optionName = str_replace("backwpup_cfg_","",$option);
      $optionList = array(
        'showadminbar' => 'Admin bar',
        'showfoldersize' => 'Folder sizes',
        'jobstepretry' => 'Maximum number of retries for job steps',
        'jobmaxexecutiontime' => 'Maximum script execution time',
        'protectfolders' => 'Protect folders',
        'keepplugindata' => 'Plugin data',
        'jobrunauthkey' => 'Key to start jobs externally with an URL',
        'jobwaittimems' => 'Reduce server load',
        'jobdooutput' => 'Empty output on working',
        'windows' => 'Windows IIS compatibility',
        'logfolder' => 'Log file folder',
        'maxlogs' => 'Maximum log files',
        'gzlogs' => 'Compression',
        'loglevel' => 'Logging Level',
        'authentication' => 'Authentication method',
        'easycronapikey' => 'Api key',
        'easycronwp' => 'Trigger WordPress Cron'
      );
      if($value != $old_value){
        $content = "";
        if(is_bool($value)){
          if($value)
            $content = "enabled";
          else
            $content = "disabled";
        }else{
          if($optionName == "jobwaittimems"){
            if($value == 10000)
              $content = "changed to minimum";
            else if($value == 30000)
              $content = "changed to minimum";
            else if($value == 90000)
              $content = "changed to maximum";
          else
              $content = "disabled";
          }else if($optionName == "authentication"){
            if(empty($value['method'])){
              $content = "changed to none";
            }else if($value['method'] == 'basic'){
              $content = "changed to Basic auth";
            }else if($value['method'] == 'user'){
              $content = "changed to WordPress user";
            }else if($value['method'] == 'query_arg'){
              $content = "changed to Query argument";
            }
          }else{
            $content = "changed to ".ucfirst(str_replace("_"," ",$value));
            if($optionName === "jobmaxexecutiontime"){
              if(intval($value) === 1)
                $content .= " second";
              else
                $content .=" seconds";
            }
          }
        }
        if(isset($optionList[$optionName]))
          $wpdb->insert($table_name, array('description'=>"<b>".$optionList[$optionName]."</b> has been ".$content,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>$gCurrentUser));
        else
          $wpdb->insert($table_name, array('description'=>"<b>".$optionName."</b> has been ".$content,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'BackWPup', 'user'=>$gCurrentUser));
      }
    }
  }

  if(strpos($option, 'user_role_editor') !== false){
    if(esc_attr(get_option("click5_history_log_user-role-editor/user-role-editor.php")) === "1"){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      $content = "";
      $optionList = array(
        'show_admin_role' => "Show Administrator role at User Role Editor	",
        'ure_caps_readable' => "Show capabilities in the human readable form",
        'ure_show_deprecated_caps' => "Show deprecated capabilities",
        'ure_confirm_role_update' => "Confirm role update",
        'edit_user_caps' => "Edit user capabilities",
        'caps_columns_quant' => "Show capabilities",
        'other_default_roles' => "Other default roles",
        'count_users_without_role' => "Count users without role"
      );
      foreach($value as $key => $val){
        if(isset($old_value[$key]) && !is_array($val)){
          if(intval($val) === 1 && $key != 'caps_columns_quant'){
            $content = "enabled";
          }else if(intval($val) === 0 && $key != 'caps_columns_quant'){
            $content = "disabled";
          }else{
            $content = "changed to ".$val;
            if($key == "caps_columns_quant"){
              if($val === 1)
                $content .= " column";
              else
                $content .= " columns";
            }
          }
        }else if(!is_array($val)){
          if(intval($val) === 1 && $key != 'caps_columns_quant'){
            $content = "enabled";
          }else if(intval($val) === 0 && $key != 'caps_columns_quant'){
            $content = "disabled";
          }else{
            $content = "changed to ".$val;
          }
        }else{
          $content = "changed";
        }
        if(isset($optionList[$key])){
          if($val != $old_value[$key])
            $wpdb->insert($table_name, array('description'=>"<b>".$optionList[$key]."</b> has been ".$content,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>$gCurrentUser));
        }  
      }
    }
  }

  if(strpos($option, 'wp_user_roles') !== false){
    global $gCurrentUser;
    global $wpdb;
    $table_name = $wpdb->prefix . "c5_history";
    if(isset($_REQUEST['sub_action'])){
      if(count($value) === count($old_value) && $_REQUEST['sub_action'] === "update_role"){
        foreach($value as $key => $val){
          if(isset($val['capabilities']) && isset($old_value[$key]['capabilities'])){
            $newCount = count($val['capabilities']);
            $oldCount = count($old_value[$key]['capabilities']);
    
            if($newCount != $oldCount){
              $roleName = $val['name'];
              $wpdb->insert($table_name, array('description'=>"Capabilities for <b>$roleName</b> role has been changed",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>$gCurrentUser));
            }
          }
        }
      }else if($_REQUEST['sub_action'] === "add_capability"){
        if(isset($_REQUEST['user_role']) && isset($_REQUEST['capability_id'])){
          if(!empty($_REQUEST['user_role']) && !empty($_REQUEST['capability_id'])){
            $wpdb->insert($table_name, array('description'=>"New capability <b>{$_REQUEST['capability_id']}</b> has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>$gCurrentUser));
          }
        }
      }else if($_REQUEST['sub_action'] === "delete_capability"){
        if(isset($_REQUEST['values'])){
          if(!empty(array_values($_REQUEST['values'])[0])){
            $wpdb->insert($table_name, array('description'=>"Capability <b>".array_values($_REQUEST['values'])[0]."</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>$gCurrentUser));
          }
        }
      }else if($_REQUEST['sub_action'] === "add_role" || $_REQUEST['sub_action'] === "delete_role"){
        $addDiff = array_diff_assoc($value,$old_value);
        $delDiff = array_diff_assoc($old_value,$value);
          if(count($addDiff) > 0){
            foreach($addDiff as $role){
              $roleName = $role['name'];
              $wpdb->insert($table_name, array('description'=>"New role <b>$roleName</b> has been added",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>$gCurrentUser));
            }
          }
    
          if(count($delDiff) > 0){
            foreach($delDiff as $role){
              $roleName = $role['name'];
              $wpdb->insert($table_name, array('description'=>"Role <b>$roleName</b> has been deleted",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>$gCurrentUser));
            }
          }
      }else if($_REQUEST['sub_action'] === "rename_role"){
        if(isset($_REQUEST['user_role_id']) && isset($_REQUEST['user_role_name'])){
          $wpdb->insert($table_name, array('description'=>"Role <b>".$_REQUEST['user_role_id']."</b> has been renamed to <b>".$_REQUEST['user_role_name']."</b>",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>$gCurrentUser));
        }
      }
    }
  }

  if(strpos($option, 'default_role') !== false){
    if(esc_attr(get_option("click5_history_log_user-role-editor/user-role-editor.php")) === "1"){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      if($value != $old_value)
        $wpdb->insert($table_name, array('description'=>"<b>Default role</b> has been changed to ".ucfirst($value),'date'=>date('Y-m-d H:i:s'), 'plugin'=>'User Role Editor', 'user'=>$gCurrentUser));
    }
  }

  if(strpos($option, 'siteground_optimizer') !== false){
    if(esc_attr(get_option("click5_history_log_sg-cachepress/sg-cachepress.php")) === "1"){
      global $gCurrentUser;
      global $wpdb;
      $table_name = $wpdb->prefix . "c5_history";
      
      $optionsList = array(
        "file_caching" => "File-Based Caching",
        "autoflush_cache" => "Automatic Purge",
        "purge_rest_cache" => "Purge the WordPress API cache",
        "user_agent_header" => "Browser-specific Caching",
        "enable_gzip_compression" => "GZIP Compression",
        "enable_browser_caching" => "Browser Caching",
        "heartbeat_dashboard_interval" => "Admin Pages Heartbeat Optimization",
        "heartbeat_post_interval" => "Post & Pages Heartbeat Optimization",
        "heartbeat_frontend_interval" => "Frontend Heartbeat Optimization",
        "database_optimization" => "Scheduled Database Maintenance",

        "ssl"           => "HTTPS Enforce",
        "fix_insecure_content"  => "Fix Insecure Content",
        "optimize_css" => "Minify CSS Files",
        "minify_css_exclude" => "Exclude from CSS Minification",
        "combine_css" => "Combine CSS Files",
        "combine_css_exclude" => "Exclude from CSS Combination",
        "preload_combined_css" => "Preload Combined CSS",
        
        "optimize_javascript" => "Minify JavaScript Files",
        "minify_javascript_exclude" => "Exclude from Javascript Minification",
        "combine_javascript" => "Combine Javascript Files",
        "combine_javascript_exclude" => "Exclude from Javascript Combination",
        "async_javascript_exclude" => "Exclude from Deferral of Render-blocking JS",

        "optimize_html" => "Minify HTML Files",
        "minify_html_exclude" => "Exclude from HTML Minification",
        "optimize_web_fonts" => "Web Fonts Optimization",
        "fonts_preload_urls" => "Fonts Preloading",
        "remove_query_strings" => "Remove Query Strings from Static Resources",
        "disable_emojis" => "Emojis",
        "dns_prefetch_urls" => "DNS Pre-fetch for External Domains",
        "lazyload_images" => "Lazy Load Media",
        "excluded_lazy_load_classes" => "Exclude CSS Classes from Lazy Load",
        "excluded_lazy_load_media_types" => "Exclude Media Types from Lazy Load",
        "resize_images" => "Maximum Image Width"

      );

      $optionName = str_replace("siteground_optimizer_","",$option);
      $content = "";
      if(!is_array($value)){
        if($value != $old_value){
          if($value === 1){
            $content = "enabled";
          }else if($value === 0){
            $content = "disabled";
          }else{
            $content = "changed to ".$value."px";
            if($optionName == "resize_images")
              $content = "changed to ".$value."px";
          }
          if(isset($optionsList[$optionName]))
            $wpdb->insert($table_name, array('description'=>"<b>".$optionsList[$optionName]."</b> has been ".$content,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'SiteGround Optimizer', 'user'=>$gCurrentUser));
        }
      }else{
        $new_array = array_diff($value,$old_value);
        $old_array = array_diff($old_value,$value);

        $currentArray = array();

        if(count($new_array) > count($old_array))
          $currentArray = $new_array;
        else
          $currentArray = $old_array;

        if(count($currentArray) > 0){
          if(isset($optionsList[$optionName]))
            $wpdb->insert($table_name, array('description'=>"<b>".$optionsList[$optionName]."</b> has been changed",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'SiteGround Optimizer', 'user'=>$gCurrentUser));
        }
      }
    }
  }
}, 10, 3 );

function click5_history_log_load_plugin_textdomain() {
  load_plugin_textdomain( 'history-log-by-click5', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'click5_history_log_load_plugin_textdomain' );
function c5_history_log_save_changes_notice() {
      echo '<div class="notice inline notice-success is-dismissible" style="">
      <p>Settings have been saved.</p>
      </div>'; 
}

function click5_history_log_settings_page() {
  	
?>

<div class="wrap">
  <h1 class="click5_history_log_heading"><?php _e('History Log by click5', 'history-log-by-click5'); ?> <span class="version">v<?php echo click5_history_log_VERSION; ?></span></h1>
</div>
<?php
            if( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = sanitize_key($_GET[ 'tab' ]);
            } else {
              $active_tab = 'log';
            }
?>

<h2 class="nav-tab-wrapper">
    <a href="?page=history-log-by-click5%2Fhistory-log-by-click5.php&tab=log" class="nav-tab <?php echo $active_tab == 'log' ? 'nav-tab-active' : ''; ?>"><?php _e('History Log', 'history-log-by-click5'); ?></a>
    <a href="?page=history-log-by-click5%2Fhistory-log-by-click5.php&tab=alerts" class="nav-tab <?php echo $active_tab == 'alerts' ? 'nav-tab-active' : ''; ?>"><?php _e('Email Alerts', 'history-log-by-click5'); ?></a>
    <a href="?page=history-log-by-click5%2Fhistory-log-by-click5.php&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', 'history-log-by-click5'); ?></a>
</h2>

<div class="wrap click5_history_log_wrapper_content_settings">
<div class="content-left">
<?php
      $verification_token = md5(uniqid(rand(), true));
      $cur_user_id = wp_get_current_user()->user_login;
      update_option('click5_history_log_token_'.$cur_user_id, $verification_token);
?>
<input type="hidden" id="verification_token" value="<?php echo esc_attr($verification_token); ?>" />
<input type="hidden" id="user_identificator" value="<?php echo esc_attr($cur_user_id); ?>" />

<form method="post" action="">
<?php wp_nonce_field( 'click5_history_log_nonce','click5_history_log_nonce' ); ?>
<?php if ($active_tab == 'log'): ?>  
    <?php settings_fields( 'click5_history-log' ); ?>
    <?php do_settings_sections( 'click5_history-log' ); ?>
    <?php
    $db_history = new Click5_Grid();
    $db_history->prepare_items();
		?>
		<div class="wrap grid-margin">
    <div class="upper_container"><p style="font-size:23px;font-weight:400; width:50%">History Log

    <?php if(isset($_SESSION['search_param'])){?>
        <?php if($_SESSION['search_param']!= ""){?>
      <span style="margin-right:auto;" id="searchInfo" class="subtitle"><?php echo (isset($_SESSION['search_param']))?"Search results for: <b>". $_SESSION['search_param'] . "</b>":'';?> </span>
    <?php } ?>
    <?php } ?>
  
    </p>

    <div class="search-box" style="margin-right:10px">
           <input style="border-radius:4px; border-style:solid; border-color:#8c8f94; border-width: 1px; min-height: 30px; font-size:14px; width:192px;" id="searchRecords" name="searchRecords" value = "<?php echo (isset($_SESSION['search_param']))?$_SESSION['search_param']:'';?>">
           <input value="Search Logs" class="button button-secondary" type="submit" name="search_button" />
    </div>
    
    </div>
      
      <div class="wrap table-block grid-margin">
      
        <div class="filter_slot">
          <form method="GET">
            <?php wp_nonce_field( 'click5_history_log_nonce','click5_history_log_nonce' ); ?>
                      <label for="filter-by-month" class="screen-reader-text">Filter by month</label>
            <select id="filter-by-month" name="filter-by-month">
              <option value="0" <?php if (!isset($_SESSION["cookie-filter-by-month"]) || $_SESSION["cookie-filter-by-month"] == "0") { echo ' selected="selected"'; } ?>>All months</option>
              <?php 
                global $wpdb;
                $table_name = $wpdb->prefix . "c5_history";
                $query = "SELECT * FROM $table_name";
                $query = $wpdb->prepare($query);
                $results = $wpdb->get_results($query);
                $valid_dates = array();
                foreach($results as $result_item) {
                  $add_new_option = true;
                  $time_array = explode("-", $result_item->date);
                  $date_option = $time_array[0] . $time_array[1];
                  foreach($valid_dates as $valid_item) {
                    if($valid_item == $date_option) {
                      $add_new_option = false;
                    }
                  }
                  if($add_new_option) {
                    array_push($valid_dates, $date_option);   
                  }
                }
                arsort($valid_dates);
                foreach($valid_dates as $valid_dates_option_items) {
                  $display_string = "";
                  $month_maps = array(
                    array('01', 'January'),
                    array('02', 'February'),
                    array('03', 'March'),
                    array('04', 'April'),
                    array('05', 'May'),
                    array('06', 'June'),
                    array('07', 'July'),
                    array('08', 'August'),
                    array('09', 'September'),
                    array('10', 'October'),
                    array('11', 'November'),
                    array('12', 'December')
                  );
                  foreach($month_maps as $month_maps_item) {
                    if($month_maps_item[0] == substr($valid_dates_option_items, -2)) {
                      $display_string = $month_maps_item[1] . " " . substr($valid_dates_option_items, 0, -2);
                    }
                  }
                  ?><option value=<?php echo esc_attr($valid_dates_option_items) ?> <?php if (isset($_SESSION["cookie-filter-by-month"]) && $_SESSION["cookie-filter-by-month"] == $valid_dates_option_items ) { echo ' selected="selected"'; } ?>><?php echo esc_attr($display_string) ?></option><?php
                }
              ?>
            </select>
            <input type="hidden" name="max_page" value=<?php echo esc_attr($db_history->_pagination_args['total_pages']) ?> />
            <label for="filter-by-user" class="screen-reader-text">Filter by user</label>
            <select id="filter-by-user" name="filter-by-user">
              <option <?php if (!isset($_SESSION["cookie-filter-by-user"]) && $_SESSION["cookie-filter-by-user"] == "all") { echo ' selected="selected"'; } ?> value="all">All users</option>
              <?php 
                global $wpdb;
                $table_name = $wpdb->prefix . "c5_history";
                $user_tab_name = $wpdb->prefix . 'users'; 
                $query = "SELECT * FROM $table_name " . " WHERE 1=1 AND user IS NOT NULL ";
                $query_user = "SELECT * FROM $user_tab_name";
                $query = $wpdb->prepare($query);
                $query_user = $wpdb->prepare($query_user);
                $results = $wpdb->get_results($query);
                $results_user = $wpdb->get_results($query_user);
                $user_array = array();
                $user_roles = array("Global","Administrator");
                foreach($results as $results_item) {
                  $add_element = true;
                  foreach($user_array as $user_item) { 
                    if($user_item == $results_item->user) {
                      $add_element = false;
                    }
                  }
                  if($add_element && $results_item->user != "") {
                    array_push($user_array, $results_item->user);
                  }
                }
                $user_list_items = array();
                foreach($user_array as $user_element) { 
                    $name_user = "";
                    $user_login = "";
                    foreach($results_user as $user_single) {
                      if($user_single->user_login == $user_element) {
                        $name_user = $user_single->display_name;
                        $user_id = $user_single->ID;
                        $user_login = $user_single->user_login;
                        break;
                      }
                    }
                    if($name_user == "") {
                      $name_user = $user_element;
                    }
                    $user_meta = get_user_by("login",$user_login);
                    $user_role = null;
                    if(isset($user_meta->roles))
                    {
                      $user_role = ucfirst($user_meta->roles[0]);
                      if($user_role!= null)
                      {
                        if(in_array($user_role, $user_roles) == false){
                          array_push($user_roles,$user_role );
                        }
                      }
                    }
                    if(is_null($user_role))
                    {
                      $user_role="Global";
                    }
                    array_push($user_list_items, array($user_element, $name_user, $user_role));
                }
                usort($user_list_items, function($x, $s) {
                  return strcmp(strtolower($x[1]), strtolower($s[1]));
                });
                        
                if(count($user_list_items)>=10)
                {
                  foreach ($user_roles as $role)
                  {
                    if($role == "Global")
                    {
                      foreach($user_list_items as $user_list_item) {
                        if($user_list_item[2] == $role)
                        {
                        ?>
                        <option value=<?php echo str_replace(" ","%%%", esc_attr($user_list_item[0]));  ?> <?php if (isset($_SESSION["cookie-filter-by-user"]) && $_SESSION["cookie-filter-by-user"] == $user_list_item[0]) { echo ' selected="selected"'; } ?> ><?php if(strlen(esc_attr($user_list_item[1])) > 15) { echo substr(esc_attr($user_list_item[1]), 0, 15) . "..."; } else { echo esc_attr($user_list_item[1]); } ?></option><?php
                        }
                        elseif($role == "Other" && $user_list_item[2] ==null){
                          ?>
                        <option value=<?php echo str_replace(" ","%%%", esc_attr($user_list_item[0]));  ?> <?php if (isset($_SESSION["cookie-filter-by-user"]) && $_SESSION["cookie-filter-by-user"] == $user_list_item[0]) { echo ' selected="selected"'; } ?> ><?php if(strlen(esc_attr($user_list_item[1])) > 15) { echo substr(esc_attr($user_list_item[1]), 0, 15) . "..."; } else { echo esc_attr($user_list_item[1]); } ?></option><?php
                        }
                      }
                    }
                    
                  }
                  foreach ($user_roles as $role)
                  {
                    if($role != "Global"){
                    ?>
                    <optgroup label=<?php echo $role."s" ?>>
                      <?php 
                    foreach($user_list_items as $user_list_item) {
                      if($user_list_item[2] == $role)
                      {
                      ?>
                      <option value=<?php echo str_replace(" ","%%%", esc_attr($user_list_item[0]));  ?> <?php if (isset($_SESSION["cookie-filter-by-user"]) && $_SESSION["cookie-filter-by-user"] == $user_list_item[0]) { echo ' selected="selected"'; } ?> ><?php if(strlen(esc_attr($user_list_item[1])) > 15) { echo substr(esc_attr($user_list_item[1]), 0, 15) . "..."; } else { echo esc_attr($user_list_item[1]); } ?></option><?php
                      }
                      elseif($role == "Other" && $user_list_item[2] ==null){
                        ?>
                      <option value=<?php echo str_replace(" ","%%%", esc_attr($user_list_item[0]));  ?> <?php if (isset($_SESSION["cookie-filter-by-user"]) && $_SESSION["cookie-filter-by-user"] == $user_list_item[0]) { echo ' selected="selected"'; } ?> ><?php if(strlen(esc_attr($user_list_item[1])) > 15) { echo substr(esc_attr($user_list_item[1]), 0, 15) . "..."; } else { echo esc_attr($user_list_item[1]); } ?></option><?php
                      }
                    }
                  }
                  }
                }
                else
                {
                  foreach($user_list_items as $user_list_item) {
                    ?>
                    <option value=<?php echo str_replace(" ","%%%", esc_attr($user_list_item[0]));  ?> <?php if (isset($_SESSION["cookie-filter-by-user"]) && $_SESSION["cookie-filter-by-user"] == $user_list_item[0]) { echo ' selected="selected"'; } ?> ><?php if(strlen(esc_attr($user_list_item[1])) > 15) { echo substr(esc_attr($user_list_item[1]), 0, 15) . "..."; } else { echo esc_attr($user_list_item[1]); } ?></option><?php
                  }
                }
              ?>
            </select>
            <label for="filter-by-module" class="screen-reader-text">Filter by plugin/module</label>
            <select id="filter-by-module" name="filter-by-module">
              <option value="all" <?php if (!isset($_SESSION["cookie-filter-by-module"]) || $_SESSION["cookie-filter-by-module"] == "all") { echo ' selected="selected"'; } ?>>All plugins/modules</option>
              <?php 
                $mod_array = array();
                $add_mod = false;
                foreach($results as $results_single_item) { 
                  if(!empty($results_single_item->user))
                    $add_mod = true;
                  foreach($mod_array as $mod_item) { 
                    if($mod_item == $results_single_item->plugin) {
                      $add_mod = false;
                    }
                  }
                  if($add_mod) {
                    if($results_single_item->plugin !== "404 Errors")
                      array_push($mod_array, $results_single_item->plugin); 
                  }
                }
                usort($mod_array, function($x,$s){
                  return strcmp(strtolower($x), strtolower($s));
                });
                array_unshift($mod_array, '404 Errors');
                foreach($mod_array as $mod_element) { 
                ?><option value=<?php echo str_replace(" ","%%%", esc_attr($mod_element)); ?> <?php if (isset($_SESSION["cookie-filter-by-module"]) && $_SESSION["cookie-filter-by-module"] == $mod_element) { echo ' selected="selected"'; } ?> ><?php if(strlen(esc_attr($mod_element)) > 30) { echo substr(esc_attr($mod_element), 0, 30) . "..."; } else { echo esc_attr($mod_element); } ?></option><?php
              }
              ?>
            </select>
            <?php wp_nonce_field( 'click5_filter_nonce','click5_filter_nonce' ); ?>
            <input value="Filter" class="button button-secondary" type="submit" name="filter_button"/>
          </form>
        </div>
        <?php $db_history->display(); ?>
      </div>
		</div>
    <div id="poststuff">
      <div id="post-body-content">     
      </div>
    </div>
    <?php elseif( $active_tab == 'settings' ): ?>
    <?php settings_fields( 'click5_sitemap_seo' ); ?>
    <?php do_settings_sections( 'click5_sitemap_seo' ); ?>
    <div id="poststuff">
      <div id="post-body-content" >
      <form action="" method="post">
          <?php wp_nonce_field( 'click5_history_log_nonce','click5_history_log_nonce' ); ?>
          <div class="postbox" style="margin-right: 20px!important">
            <h3 class="hndle"><span><?php _e('History Log Settings', 'sitemap-by-click5'); ?></span></h3>
            <div class="inside">
              <div class="wrap" style="margin-left: 15px;">
                  <?php if(isset($_REQUEST['save_button'])){
                          c5_history_log_save_changes_notice();
                      } ?>
                </div>
              <p><strong style="margin-left: 15px">Store History Log Data for</strong></p>
              <table class="form-table">
                <tbody>
                  <tr>
                    <select id="click5_history_log_store_time" name="click5_history_log_store_time" style="margin-left: 15px;width: 245px;">
                      <!--<option value="day" <?php echo (esc_attr( get_option('click5_log_store_time') ) == 'day' ? 'selected' : ''); ?>>1 day</option>-->
                      <option value="week" <?php echo (esc_attr( get_option('click5_log_store_time') ) == 'week' ? 'selected' : ''); ?>>1 week</option>
                      <option value="month" <?php echo (esc_attr( get_option('click5_log_store_time') ) == 'month' ? 'selected' : ''); ?>>1 month</option>
                      <option value="3_month" <?php echo (esc_attr( get_option('click5_log_store_time') ) == '3_month' ? 'selected' : ''); ?>>3 months</option>
                      <option value="6_month" <?php echo (esc_attr( get_option('click5_log_store_time') ) == '6_month' ? 'selected' : ''); ?>>6 months</option>
                      <option value="12_month" <?php echo (esc_attr( get_option('click5_log_store_time') ) == '12_month' ? 'selected' : ''); ?>>12 months</option>
                      <option value="indefinitely" <?php echo (esc_attr( get_option('click5_log_store_time') ) == 'indefinitely' ? 'selected' : ''); ?>>Indefinitely (Not Recommended)</option>
                    </select>
                  </tr>
                </tbody>
              </table>
              <input value="Save Changes" style="margin-left: 15px" class="button button-primary" type="submit" name="save_button"/>
              <br/>
              <div style="margin-left: 15px">
                <b><h4>Purge History Log Data</h4></b>
                Warning: This will delete all history logs from your database.
                <br/>
              </div>            
              <br/>
              <input value="Purge Data" style="margin-left: 15px" type="button" class="button button-outline-primary" id ="myBtn" name="myBtn" onclick='btnClick()'/>

              <div id="c5ConfirmModal" class="c5modal">
                <!-- Modal content -->
                <div class="c5modal-content">
                  <span onclick="closeModal()" class="c5close">×</span>
                  <p style="margin-left: 15px">If you want to delete logs, enter 'DELETE' below and press Purge Data.</p>
                  <input style="margin-left: 15px" id="confirmDelete" name="confirmDelete" />
                  <div name="confirm-delete-message" style="display: none; color: red; margin-left: 15px;">
                      <p>The text you entered is incorrect</p>
                  </div>
                  <br/>
                  <br/>
                  
                  <input value="Purge Data" style="margin-left: 15px" type="submit" class="button button-primary" name="save_button_op"/>
                  <input value="Cancel" style="margin-left: 15px" type="submit" class="button button-outline-primary" name="cancel_button_op"/>
                </div>
                </div>
            </div>
          </div>
          <div class="module-block">
            <h1><b style="margin-left: 5px"><i class="bi bi-check"></i> Modules</b></h1>               
            <div style="margin-right: 20px!important">
              <div class="table-block">
              <?php
                $db_history_modules = new Click5_Grid_Modules();
                $db_history_modules->prepare_items();
                $db_history_modules->display();
              ?>
              </div>
            </div>
          </div>

          <div class="plugin-block">
            <h1><b style="margin-left: 5px"><i class="bi bi-check"></i> Plugins</b></h1>               
            <div style="margin-right: 20px!important">
              <div class="table-block">
              <?php
                $db_history_plugins = new Click5_Grid_Plugins();
                $db_history_plugins->prepare_items();
                $db_history_plugins->display();
              ?>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php elseif( $active_tab == 'alerts' ): 
      require_once("pages/click5_alert.php");
     endif; ?>
</form>
</div>
<div class="content-right">
      <div id="poststuff">
        <div id="post-body-content">
            <div class="postbox">
              <h3 class="hndle"><span>Plugin Support</span></h3>
              <div class="inside">
                <p>Visit our <a href="http://wordpress.org/support/plugin/history-log-by-click5" target="_blank" rel="nofollow">community forum</a> to find answers to common issues, ask questions, submit bug reports, feature suggestions and other tips about our plugin.</p>
                <p>Please consider supporting our plugin by <a href="https://wordpress.org/support/plugin/history-log-by-click5/reviews/?filter=5" target="_blank" rel="nofollow">leaving a review</a>. Thank You!</p>
              </div>
            </div>
            <?php
              $currentMonth = new DateTime(); 
              $currentMonth->setTimezone(new DateTimeZone(wp_timezone_string()));
              //$currentMonth->setTimestamp(strtotime("+3 months"));
              $currentMonth = $currentMonth->format('m');
 
              $monthSwitch = intval($currentMonth%2);
 
              $c5SiteMap_Pathpluginurl = WP_PLUGIN_DIR . '/sitemap-by-click5/sitemap-by-click5.php';
              $c5SiteMap_IsInstalled = file_exists( $c5SiteMap_Pathpluginurl );
 
              $c5DisableComments_Pathpluginurl = WP_PLUGIN_DIR . '/disable-comments-by-click5/disable-comments-by-click5.php';
              $c5DisableComments_IsInstalled = file_exists( $c5DisableComments_Pathpluginurl );
              $OtherWordpressPlugins = "sitemap";
              $AdImageURL = "assets/banner-300x515-sitemap-plugin.png";
              $AdImageSiteURL = "https://click5crm.com/?utm_source=comments-plugin&utm_medium=sidebar&utm_campaign=wp-plugins";
              $AdBoxSiteURL = "https://wordpress.org/plugins/sitemap-by-click5/";
              
              switch($monthSwitch){
                case 0:
                  $OtherWordpressPlugins = false;
                if($c5SiteMap_IsInstalled === false){
                    $AdImageURL = "assets/sitemap.png";
                    $AdImageSiteURL = "https://wordpress.org/plugins/sitemap-by-click5/";
                    $OtherWordpressPlugins = false;
                  }else if($c5DisableComments_IsInstalled === false){
                    $OtherWordpressPlugins = "disablecomments";
                  }
                 
                  break;
                  
                case 1:
                  if($c5DisableComments_IsInstalled === false && $c5SiteMap_IsInstalled === false){
                    $OtherWordpressPlugins = "sitemap";
                  }else if($c5DisableComments_IsInstalled === false){
                    $OtherWordpressPlugins = "disablecomments";
                  }
                  else{
                    if($c5SiteMap_IsInstalled === true){
                      $OtherWordpressPlugins = false;
                    }
                  }
                  break;
              }
            ?>
            <div class="postbox with-image">
              <a href="<?php echo esc_url($AdImageSiteURL); ?>" target="_blank" rel="nofollow">
                <img src="<?php echo plugin_dir_url( __FILE__ ).$AdImageURL; ?>" alt="click5crm">
              </a>
            </div>
            <?php
            if($OtherWordpressPlugins === "sitemap"){
              ?>
              <div class="postbox">
              <h3 class="hndle"><span>Our Other WordPress Plugins</span></h3>
              <div class="inside">
                <p><strong><a href="https://wordpress.org/plugins/sitemap-by-click5/" target="_blank" rel="nofollow">Sitemap by click5</a></strong></p>
                <p>Simple and easy to use plugin allowing you to take control over the comments section on your blog posts, pages, media attachments, and custom post types.</p>
                <p><strong><a  href="https://wordpress.org/plugins/search/click5/" target="_blank" rel="nofollow">and more...</a</strong></p>
              </div>
             </div>
              <?php
            }else if($OtherWordpressPlugins === "disablecomments"){
              ?>
              <div class="postbox">
              <h3 class="hndle"><span>Our Other WordPress Plugins</span></h3>
              <div class="inside">
                <p><strong><a href="https://pl.wordpress.org/plugins/disable-comments-by-click5/" target="_blank" rel="nofollow">Disable Comments by click5</a></strong></p>
                <p>Simple and easy to use plugin allowing you to take control over the comments section on your blog posts, pages, media attachments, and custom post types.</p>
                <p><strong><a  href="https://wordpress.org/plugins/search/click5/" target="_blank" rel="nofollow">and more...</a</strong></p>
              </div>
             </div>
              <?php
            }
            
            ?>
        </div>
      </div>
</div>
</div>
<?php }
function click5_history_log_init_admin_scripts() {
  $screen = get_current_screen();
  $version = click5_history_log_DEV_MODE ? time() : click5_history_log_VERSION;
  if(strpos($screen->base, 'history-log-by-click5') !== false) {
    wp_enqueue_style( 'click5_history_log_css_admin', plugins_url('/css/admin/index.css', __FILE__), array(), $version);
    wp_enqueue_script( 'click5_sitemap_js_admin', plugins_url('/js/index.js', __FILE__), array(), $version);
  }
  wp_localize_script( 'jquery', 'c5resturl', array('wpjson' => get_rest_url()) );

}

add_action('admin_enqueue_scripts', 'click5_history_log_init_admin_scripts');
function click5_history_log_uninstallFunction() {
  $current_user = wp_get_current_user();
  delete_option('click5_history_log_authentication_token_'.wp_get_current_user()->user_login);
}
register_uninstall_hook(__FILE__, 'click5_history_log_uninstallFunction');

add_filter( 'wp_ajax_wordfence_saveOptions', 'click5_wordfence_saveOptions' );
function click5_wordfence_saveOptions( ) {
  if (!empty($_POST['changes']) && ($changes = json_decode(stripslashes($_POST['changes']), true)) !== false) {
    $arrayKeys = array_keys($changes);
    foreach($arrayKeys as $value)
    {
      if($value == 'wafStatus')
      {
        $wafValue = $changes[$value];
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if(esc_attr(get_option('click5_history_log_wordfence/wordfence.php')) == "1") { 
          $wpdb->insert($table_name, array('description'=>"<b>Wordfence Firewall Status</b> has been changed to: " . $wafValue,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Wordfence', 'user'=>$gCurrentUser));
        }
      }
      else
      {
        $optionVal = $changes[$value];
        global $gCurrentUser;
        global $wpdb;
        $table_name = $wpdb->prefix . "c5_history";
        if($value == "alertOn_block") {
          if($optionVal) {
            $optVal = "<b>turned on</b>";
          } 
          if(!$optionVal) {
            $optVal = "<b>turned off</b>";
          }
          if(esc_attr(get_option('click5_history_log_wordfence/wordfence.php')) == "1") { 
            $wpdb->insert($table_name, array('description'=>"Alert when an IP address is blocked has been " . $optVal,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Wordfence', 'user'=>$gCurrentUser));
          }       
        }
        if($value == "alertOn_lostPasswdForm") {
          if($optionVal) {
            $optVal = "<b>turned on</b>";
          } 
          if(!$optionVal) {
            $optVal = "<b>turned off</b>";
          }
          if(esc_attr(get_option('click5_history_log_wordfence/wordfence.php')) == "1") { 
            $wpdb->insert($table_name, array('description'=>'Alert when the "lost password" form is used for a valid user has been ' . $optVal,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Wordfence', 'user'=>$gCurrentUser));
          }
        }
        if($value == "alertOn_wordfenceDeactivated") {
          if($optionVal) {
            $optVal = "<b>turned on</b>";
          } 
          if(!$optionVal) {
            $optVal = "<b>turned off</b>";
          }
          if(esc_attr(get_option('click5_history_log_wordfence/wordfence.php')) == "1") { 
            $wpdb->insert($table_name, array('description'=>'Email if Wordfence is deactivated has been ' . $optVal,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Wordfence', 'user'=>$gCurrentUser));
          }
        }
        if($value == "loginSecurityEnabled") {
          if($optionVal) {
            $optVal = "<b>turned on</b>";
          } 
          if(!$optionVal) {
            $optVal = "<b>turned off</b>";
          }
          if(esc_attr(get_option('click5_history_log_wordfence/wordfence.php')) == "1") { 
            $wpdb->insert($table_name, array('description'=>'Brute Force Protection has been ' . $optVal,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Wordfence', 'user'=>$gCurrentUser));
          }       
        }
        if($value == "scanType") {
          if($optionVal == "limited") {
            $optVal = "<b>Limited Scan</b>";
          } 
          if($optionVal == "standard") {
            $optVal = "<b>Standard Scan</b>";
          } 
          if($optionVal == "highsensitivity") {
            $optVal = "<b>High Sensitivity</b>";
          } 
          if($optionVal == "custom") {
            $optVal = "<b>Custom Scan</b>";
          }
          if(esc_attr(get_option('click5_history_log_wordfence/wordfence.php')) == "1") { 
            $wpdb->insert($table_name, array('description'=>'Scan Options have been changed to ' . $optVal,'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Wordfence', 'user'=>$gCurrentUser));
          }       
        }
      }
    }
  }
}
add_filter('wp_ajax_wordfence_scan', 'click5_wordfence_scan');
function click5_wordfence_scan( ) {
   global $gCurrentUser;
   global $wpdb;
   $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option('click5_history_log_wordfence/wordfence.php')) == "1") { 
    $wpdb->insert($table_name, array('description'=>"<b>Wordfence</b> scan has been launched",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Wordfence', 'user'=>$gCurrentUser)); 
  }   
}
add_filter('wp_ajax_wordfence_killScan', 'click5_wordfence_killScan');
function click5_wordfence_killScan( ) {
   global $gCurrentUser;
   global $wpdb;
   $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option('click5_history_log_wordfence/wordfence.php')) == "1") { 
    $wpdb->insert($table_name, array('description'=>"<b>Wordfence</b> scan has been stopped",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Wordfence', 'user'=>$gCurrentUser));
  }  
}
add_filter('wp_ajax_wordfence_restoreDefaults', 'click5_wordfence_restoreDefaults');
function click5_wordfence_restoreDefaults( ) {
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option('click5_history_log_wordfence/wordfence.php')) == "1") { 
    $wpdb->insert($table_name, array('description'=>"<b>Wordfence</b> default settings has been restored",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Wordfence', 'user'=>$gCurrentUser));
  }
}

function click5_wp_login( $user_login, $user ) {
  // Display Name> successfully logged in
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  $currentRoleName = click5_get_current_user_role($user->ID);
  if(esc_attr(get_option("click5_history_log_module_wordpress_core")) === "1")
    $wpdb->insert($table_name, array('description'=>"<b>" . $user->data->display_name . "</b> ($currentRoleName)  successfully logged in",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$user_login));
}
add_action('wp_login', 'click5_wp_login', 10, 2);

add_action('future_to_publish', 'click5_wp_future_to_publish');
function click5_wp_future_to_publish( $postid ) {
  $post_data = get_post($postid);
  global $gCurrentUser;
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if($post_data->post_type == "post") {
    if(esc_attr(get_option("click5_history_log_module_posts")) === "1")
      $wpdb->insert($table_name, array('description'=>"Post <b>" . $post_data->post_title . "</b>  has been published",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Posts', 'user'=>'WordPress Core'));
  }
  if($post_data->post_type == "page") {
    if(esc_attr(get_option("click5_history_log_module_pages")) === "1")
      $wpdb->insert($table_name, array('description'=>"Page <b>" . $post_data->post_title . "</b>  has been published",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'Pages', 'user'=>'WordPress Core'));
  }
}

add_action('profile_update', 'click5_password_reset', 10, 3);
function click5_password_reset($user_id, $old_user_data, $userdata) {
  global $wpdb;
  global $gCurrentUser;
  $new_pass = $userdata["user_pass"];
  $old_pass = $old_user_data->data->user_pass;
  if($old_pass != $new_pass) {
    $table_name = $wpdb->prefix . "c5_history";
    if(esc_attr(get_option("click5_history_log_module_wordpress_users")) === "1")
      $wpdb->insert($table_name, array('description'=>"User <b>" . $userdata["display_name"] . "</b> set new password",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$gCurrentUser));
  }
}


add_action('after_password_reset', 'click5_after_password_reset', 10, 2);
function click5_after_password_reset($user_data, $pass) {
  global $wpdb;
  $table_name = $wpdb->prefix . "c5_history";
  if(esc_attr(get_option("click5_history_log_module_wordpress_users")) === "1")
    $wpdb->insert($table_name, array('description'=>"User <b>" . $user_data->display_name . "</b> reset password",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$user_data->user_login));
}
/*
add_action( 'register_new_user', "click5_register_new_user",10,2 );
function click5_register_new_user(int $user_id) {
  global $wpdb;
  $new_user_name = $wpdb->query( $wpdb->prepare( "SELECT display_name FROM " . $wpdb->prefix . "users WHERE ID = %s LIMIT 1",  $user_id ));
  $table_name = $wpdb->prefix . "c5_history";
  //$wpdb->insert($table_name, array('description'=>"User <b>" . $user_data->display_name . "</b> reset password",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'WordPress Core', 'user'=>$user_data->user_login));
}
*/