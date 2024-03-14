<?php
/*
Plugin Name: SEO Breadcrumbs
Plugin URI:  http://krnmsaikrishna.wordpress.com/plugins/seo-breadcrumbs
Description: SEO Breadcrumbs is powerful and easy to use plugin that can add five different breadcrumbs navigation to your wordpress website, and this plugin fully customizable and responsive. Plugin shows breadcrumbs on post, page, custom taxonomies, archives, attachements, error 404, search results and more.
Version:     7.0
Author:      Krnm Saikrishna
Author URI:  https:/krnmsaikrishna.wordpress.com
License:     GPL2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: seo-breadcrumbs

SEO BREADCRUMBS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
SEO BREADCRUMBS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with SEO BREADCRUMBS. If not, see http://www.gnu.org/licenses/gpl-2.0.html .
*/

// Prevent direct access
  if ( ! defined( 'ABSPATH' ) ) {
	  
     die( 'Nice try, But not here!!!' );
	 
   }

define ( 'SBC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
 require_once SBC_PLUGIN_PATH . 'settings.php';
 require_once SBC_PLUGIN_PATH . 'functions.php';
 require_once SBC_PLUGIN_PATH . 'widget.php';
 require_once SBC_PLUGIN_PATH . 'shortcode.php';


   function seo_breadcrumbs_styles_scripts ( $hook ) 
  {
	  
     if( $hook != 'toplevel_page_seo_breadcrumbs' ) 
{
		 return;

      }

wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_style( 'seo_breadcrumbs_styles', plugins_url('css/admin-settings-page-styles.css', __FILE__) ); 
wp_enqueue_script( 'cpa_custom_js', plugins_url( 'js/jquery.custom.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), '', true  );
 
 }
  

function  seo_breadcrumbs_add_scripts() {
wp_enqueue_style( 'seo-breadcrumbs-styles',plugins_url( 'css/seo-breadcrumbs-styles.css',__FILE__));
}

add_action( 'wp_enqueue_scripts', 'seo_breadcrumbs_add_scripts' );
add_action( 'admin_enqueue_scripts', 'seo_breadcrumbs_styles_scripts' ); 


// addding a plugin action links to plugin list block.
add_filter( 'plugin_action_links', 'sbc_action_links',10,5);
  
   function sbc_action_links( $actions, $plugin_file ) 
 {
	static $plugin;
	
	if ( ! isset($plugin) ) {
		
	   $plugin = plugin_basename(__FILE__);

	  }
	if ( $plugin == $plugin_file ) {
		
	     $settings = array( 'settings' => '<a href="options-general.php?page=seo_breadcrumbs">Settings</a>' );
	      $site_link = array( 'support' => '<a href="https://krnmsaikrishna.wordpress.com/plugins/seo-breadcrumbs" target="_blank">Support</a>' );
		
    	   $actions = array_merge($settings, $actions);
			$actions = array_merge($site_link, $actions);
			
		}
	      return $actions;
  }

?>