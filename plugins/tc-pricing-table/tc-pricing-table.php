<?php
/**
 * Plugin Name:		   TC Pricing Table
 * Plugin URI:		   https://www.themescode.com/items/tc-pricing-table-pro/
 * Description:		   Pricing Table  plugin helps to create unlimited clean and flat design based pricing table in your WordPress website.
 * Version: 		   1.2
 * Author: 			   themesCode
 * Author URI: 		   https://www.themescode.com/items/tc-pricing-table-pro/
 * Text Domain:      tc-pricing-table
 * License:          GPL-2.0+
 * License URI:      http://www.gnu.org/licenses/gpl-2.0.txt
 * License: GPL2
 */
 require_once('loader.php');

  // Sub Menu Page

 add_action('admin_menu', 'tcpt_table_menu_init');

 function tcpt_menu_help(){
   include('lib/tcpt-help-upgrade.php');
 }

 function tcpt_table_menu_init()
   {

     add_submenu_page('edit.php?post_type=tcpricingtable', __('Help & Upgrade','tc-pricing-table'), __('Help & Upgrade','tc-pricing-table'), 'manage_options', 'tcpt_menu_help', 'tcpt_menu_help');

   }

 // After Plugin Activation redirect

if( !function_exists( 'tcpt_table_after_activation_redirect' ) ){
  function tcpt_table_after_activation_redirect( $plugin ) {
      if( $plugin == plugin_basename( __FILE__ ) ) {
          exit( wp_redirect( admin_url( 'edit.php?post_type=tcpricingtable&page=tcpt_menu_help' ) ) );
      }
  }
}
add_action( 'activated_plugin', 'tcpt_table_after_activation_redirect' );

 // adding link
 add_filter( 'plugin_action_links_' .plugin_basename(__FILE__), 'tcpt_table_plugin_action_links' );

 function tcpt_table_plugin_action_links( $links ) {
    $links[] = '<a class="tc-pro-link" href="https://www.themescode.com/items/tc-pricing-table-pro" target="_blank">Go Pro !</a>';
    $links[] = '<a href="https://www.themescode.com/items/category/wordpress-plugins" target="_blank">TC Plugins</a>';
    return $links;
 }
