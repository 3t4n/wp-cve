<?php 
/*
Plugin Name: Tiny Google Analytics
Plugin URI: http://todoestamal.com.ar/tiny-google-analytics-plugin-for-wordpress/
Description: Adds Google Analytics Tracking Code using an optimzed code, useful for MultiSite.(Low resource usage).
Author: HellMind
Author URI: http://facebook.com/hellmind
Version: 20130416
License: GPL v2
Usage: Visit the "Tiny Google Analytics" options page to enter your Google Analytics Property ID and its done.
*/
$gap_plugin  = __('Tiny Google Analytics');
$gap_options = get_option('gap_options');
$gap_path    = plugin_basename(__FILE__);
$gap_homeurl = 'http://todoestamal.com.ar/tiny-google-analytics-plugin-for-wordpress/';
$gap_version = '20130416';
// require minimum version of WordPress
add_action('admin_init', 'gap_require_wp_version');
function gap_require_wp_version() {
 global $wp_version, $gap_path, $gap_plugin;
 if (version_compare($wp_version, '3.4', '<')) {
  if (is_plugin_active($gap_path)) {
   deactivate_plugins($gap_path);
   $msg =  '<strong>' . $gap_plugin . '</strong> ' . __('requires WordPress 3.4 or higher, and has been deactivated!') . '<br />';
   $msg .= __('Please return to the ') . '<a href="' . admin_url() . '">' . __('WordPress Admin area') . '</a> ' . __('to upgrade WordPress and try again.');
   wp_die($msg);
  }
 }
}
function google_analytics_tracking_code(){ 
 $options = get_option('gap_options'); 
 if(!isset($options['gap_id']))return;
 echo'<script type="text/javascript">';
 echo'var _gaq=_gaq||[];';
 echo'_gaq.push(["_setAccount","'.$options['gap_id'].'"]);';
 echo'_gaq.push(["_trackPageview"]);';
 echo'(function(){';
 echo'var ga=document.createElement("script");';
 echo'ga.type="text/javascript";';
 echo'ga.async=true;';
 echo'ga.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";';
 echo'var s=document.getElementsByTagName("script")[0];';
 echo's.parentNode.insertBefore(ga,s);';
 echo'})();';
 echo'</script>';
}
add_action('wp_head', 'google_analytics_tracking_code');
add_filter ('plugin_action_links', 'gap_plugin_action_links', 10, 2);
function gap_plugin_action_links($links, $file) {
 global $gap_path;
 if ($file == $gap_path) {
  $gap_links = '<a href="' . get_admin_url() . 'options-general.php?page=' . $gap_path . '">' . __('Settings') .'</a>';
  array_unshift($links, $gap_links);
 }
 return $links;
}
function gap_delete_plugin_options() {
 delete_option('gap_options');
}
register_activation_hook (__FILE__, 'gap_add_defaults');
function gap_add_defaults() {
 $tmp = get_option('gap_options');
 if(($tmp['default_options'] == '1') || (!is_array($tmp))) {
  $arr = array(
  'gap_id'          => 'UA-XXXXX-X',
  );
  update_option('gap_options', $arr);
 }
}
add_action ('admin_init', 'gap_init');
function gap_init() {
 register_setting('gap_plugin_options', 'gap_options', 'gap_validate_options');
}
function gap_validate_options($input) {
 $input['gap_id'] = wp_filter_nohtml_kses($input['gap_id']);
 return $input;
}
add_action ('admin_menu', 'gap_add_options_page');
function gap_add_options_page() {
 global $gap_plugin;
 add_options_page($gap_plugin, 'Tiny Google Analytics', 'manage_options', __FILE__, 'gap_render_form');
}
function gap_render_form() {
 global $gap_plugin, $gap_options, $gap_path, $gap_homeurl, $gap_version;
 screen_icon();
 echo'<h2>'.$gap_plugin.'<small> v'.$gap_version.'</small></h2>';
 echo'<form method="post" action="options.php">';
  $gap_options=get_option('gap_options');
  settings_fields('gap_plugin_options');
  echo'<h3>'._e('Overview').'</h3>';
  echo'<p>';
   echo'<strong>'.$gap_plugin.'</strong>'._e('Tiny Google Analytics Plugin  adds Google Analytics Tracking Code to your WordPress site or multisite.');
  echo'</p>';
  echo'<p>';
   echo _e('Enter your Google Analytics Property ID, save your option, and done. Log into your Google Analytics account to view your stats.');
  echo'</p>';
  echo'<p>'._e('Enter your Tracking Code to enable the plugin.').'</p>';
  echo'<p>';
  echo'<label class="description" for="gap_options[gap_id]">'._e('Google Analytics property ID (e.g.: UA-36515120-21) ').'</label>';
  echo'<input type="text" size="20" maxlength="20" name="gap_options[gap_id]" value="'.$gap_options['gap_id'].'" />';
  echo'</p>';
  echo'<input type="submit" class="button-primary" value="'._e('Save Settings').'" />';
 echo'</form>';
}
?>