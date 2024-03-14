<?php
/**
 * Plugin Name:		   TC Product Catalog
 * Plugin URI:		   https://www.themescode.com/items/tc-product-catalog-pro/
 * Description:		   Product Catalog helps to nicely present your company products in your WordPress Website.New products can be added with custom post type like any wordpress post . Product title, Description,product image,product gallery,price ,sale price , product catalog , product category etc.
 * Version: 		     1.2
 * Author: 			     themesCode
 * Author URI: 		   https://www.themescode.com/items/tc-product-catalog-pro/
 * Text Domain:      tcpc
 * License:          GPL-2.0+
 * License URI:      http://www.gnu.org/licenses/gpl-2.0.txt
 * License: GPL2
 */

 /**
  * Protect direct access
  */

 if( ! defined( 'TC_PRODUCT_CATALOG_HACK_MSG' ) ) define( 'TC_PRODUCT_CATALOG_HACK_MSG', __( 'Sorry ! You made a mistake !', 'tcpc' ) );
 if ( ! defined( 'ABSPATH' ) ) die( TC_PRODUCT_CATALOG_HACK_MSG );

 /**
  * Defining constants
 */

 if( ! defined( 'TCPRODUCTCATALOG_PLUGIN_DIR' ) ) define( 'TCPRODUCTCATALOG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
 if( ! defined( 'TCPRODUCTCATALOG_PLUGIN_URI' ) ) define( 'TCPRODUCTCATALOG_PLUGIN_URI', plugins_url( '', __FILE__ ) );

// require files

require_once TCPRODUCTCATALOG_PLUGIN_DIR .'/lib/tcpc-cpt.php';
require_once TCPRODUCTCATALOG_PLUGIN_DIR .'/lib/tcpc-taxonomy.php';
require_once TCPRODUCTCATALOG_PLUGIN_DIR .'/lib/tcpc-column.php';
require_once TCPRODUCTCATALOG_PLUGIN_DIR .'/public/tcpc-view.php';
require_once TCPRODUCTCATALOG_PLUGIN_DIR .'/lib/tcpc-metabox.php';

// include files

 function tcpc_enqueue_scripts() {
      wp_enqueue_style('tcpc-style', TCPRODUCTCATALOG_PLUGIN_URI.'/assets/css/tcpc.css');
  }

 add_action( 'wp_enqueue_scripts', 'tcpc_enqueue_scripts' );

  function tcpc_admin_style() {
  wp_enqueue_style('tcpc-admin-style', TCPRODUCTCATALOG_PLUGIN_URI.'/assets/css/tcpc-admin.css');
  }

  add_action( 'admin_enqueue_scripts', 'tcpc_admin_style' );

 if ( function_exists( 'add_theme_support' ) ) {

     add_theme_support( 'post-thumbnails' );

 }
 // add submenu page

 add_action('admin_menu', 'tcpc_menu_init');



 function tcpc_menu_help(){
   include('lib/tcpc-help-upgrade.php');
 }

 function tcpc_menu_init()
   {

     add_submenu_page('edit.php?post_type=tcpc', __('Help & Upgrade','tcpc'), __('Help & Upgrade','tcpc'), 'manage_options', 'tcpc_menu_help', 'tcpc_menu_help');

   }

 /* Move Featured Image Below Title */

 require_once TCPRODUCTCATALOG_PLUGIN_DIR .'/lib/class-featured-image-metabox-cusomizer.php';

new Featured_Image_Metabox_Customizer(array(
	'post_type'     => 'tcpc',
	'metabox_title' => __( 'Product Image', 'tcpc' ),
	'set_text'      => __( 'Add Product Image', 'tcpc' ),
	'remove_text'   => __( 'Remove Product Image', 'tcpc' )
));



// After Plugin Activation redirect

 if( !function_exists( 'tcpc_activation_redirect' ) ){
   function tcpc_activation_redirect( $plugin ) {
       if( $plugin == plugin_basename( __FILE__ ) ) {
           exit( wp_redirect( admin_url( 'edit.php?post_type=tcpc&page=tcpc_menu_help') ) );
       }
   }
 }
 add_action( 'activated_plugin', 'tcpc_activation_redirect' );


// adding link
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'tcpc_plugin_action_links' );

function tcpc_plugin_action_links( $links ) {
   $links[] = '<a class="tc-pro-link" href="https://www.themescode.com/items/tc-product-catalog-pro/" target="_blank">Go Pro! </a>';
   $links[] = '<a href="https://www.themescode.com/items/category/wordpress-plugins" target="_blank">TC Plugins</a>';
   return $links;
}
