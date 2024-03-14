<?php

/*
  Plugin Name: Service Box Slider
  Plugin URI: https://www.wordpress.org/downloads/service-box-with-slider/
  Description: Service Box Showcase is a powerful &amp; robust but easy to represent your services with grid and slider.
  Author: Sk Abul Hasan
  Author URI: http://www.wpmart.org/
  Version: 1.0
 */
if (!defined('ABSPATH'))
   exit;

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);   

define('sbs_6310_plugin_url', plugin_dir_path(__FILE__));
define('sbs_6310_plugin_dir_url', plugin_dir_url(__FILE__));
define ( 'SBS_6310_PLUGIN_CURRENT_VERSION', 1.0 ); 
   
add_shortcode('sbs_6310_service_box', 'sbs_6310_service_shortcode');

function sbs_6310_service_shortcode($atts)
{
   extract(shortcode_atts(array('id' => ' ',), $atts));
   $ids = (int) $atts['id'];

   ob_start();
   include(sbs_6310_plugin_url . 'shortcode.php');
   return ob_get_clean();
}


add_action('admin_menu', 'sbs_6310_service_with_slider_menu');

function sbs_6310_service_with_slider_menu()
{
  $options = sbs_6310_get_user_roles();
   add_menu_page('Service box Slider', 'Service Box Slider', $options, 'sbs-6310-service-box', 'sbs_6310_home', 'dashicons-format-image', 20);
   add_submenu_page('sbs-6310-service-box', 'Service box Slider', 'All Service Box',  $options, 'sbs-6310-service-box', 'sbs_6310_home');
   add_submenu_page('sbs-6310-service-box', 'Template 01-10', 'Template 01-10', $options, 'sbs-6310-template-01-10', 'sbs_6310_template_01_10');
   add_submenu_page('sbs-6310-service-box', 'Template 11-20', 'Template 11-20', $options, 'sbs-6310-template-11-20', 'sbs_6310_template_11_20');
   add_submenu_page('sbs-6310-service-box', 'Template 21-30', 'Template 21-30', $options, 'sbs-6310-template-21-30', 'sbs_6310_template_21_30');
   add_submenu_page('sbs-6310-service-box', 'Template 31-40', 'Template 31-40', $options, 'sbs-6310-template-31-40', 'sbs_6310_template_31_40');
   add_submenu_page('sbs-6310-service-box', 'Template 41-50', 'Template 41-50', $options, 'sbs-6310-template-41-50', 'sbs_6310_template_41_50');
   add_submenu_page('sbs-6310-service-box', 'Manage Items', 'Manage Items', $options, 'sbs-6310-service-box-manage-items', 'sbs_6310_team_6310_manage_items');
   add_submenu_page('sbs-6310-service-box', 'Settings', 'Settings', $options, 'sbs-6310-service-box-setting', 'sbs_6310_service_6310_setting'); 
   add_submenu_page('sbs-6310-service-box', 'Import / Export Plugin', 'Import/Export Plugin', $options, 'sbs-6310-service-box-import-export', 'sbs_6310_service_6310_import_export');  

   add_submenu_page('sbs-6310-service-box', 'License', 'License', $options, 'sbs-6310-service-box-license', 'sbs_6310_service_6310_lincense');
   add_submenu_page('sbs-6310-service-box', 'How to use', 'Help', $options, 'sbs-6310-service-box-use', 'sbs_6310_service_6310_how_to_use');
   add_submenu_page('sbs-6310-service-box', 'WpMart Plugins', 'WpMart Plugins', $options, 'sbs-6310-wpmart-plugins', 'sbs_6310_wpmart_plugins');
   add_submenu_page('sbs-6310-service-box', 'Privacy Policy', 'Privacy Policy', $options, 'sbs-6310-privacy-policy', 'sbs_6310_privacy_policy');
}

function sbs_6310_home()
{
   global $wpdb;
   

   $style_table = $wpdb->prefix . 'sbs_6310_style';
   include sbs_6310_plugin_url . 'header.php';
   include sbs_6310_plugin_url . 'home.php';
}

include sbs_6310_plugin_url . 'template-menu.php';


add_action('wp_ajax_sbs_6310_team_member_info', 'sbs_6310_team_member_info');

function sbs_6310_my_enqueue()
{
   wp_localize_script('ajax-script', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'sbs_6310_my_enqueue');

if (is_admin()) {
   add_action('wp_ajax_sbs_6310_team_member_details', 'sbs_6310_team_member_details');
} else {
   add_action('wp_ajax_nopriv_sbs_6310_team_member_details', 'sbs_6310_team_member_details');
}

add_action('wp_ajax_nopriv_sbs_6310_team_member_details', 'sbs_6310_team_member_details');

register_activation_hook(__FILE__, 'sbs_6310_service_with_slider_install');
include_once(sbs_6310_plugin_url . 'functions.php');

function sbs_6310_ajax_enqueue()
{
   wp_localize_script('sbs-6310-ajax-script', 'sbs_6310_ajax_object', array('sbs_6310_ajax_url' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'sbs_6310_ajax_enqueue');

function sbs_6310_activation_redirect( $plugin ) {
   if( $plugin == plugin_basename( __FILE__ ) ) {
       exit( wp_redirect( admin_url( 'admin.php?page=sbs-6310-service-box-use' ) ) );
   }
}
add_action( 'activated_plugin', 'sbs_6310_activation_redirect' );

add_action( 'admin_enqueue_scripts', 'sbs_6310_link_css_js' );

function sbs_6310_plugin_update_check() {
   sbs_6310_version_status();
}
add_action('plugins_loaded', 'sbs_6310_plugin_update_check');