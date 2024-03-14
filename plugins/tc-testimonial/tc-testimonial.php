<?php
/**
 * Plugin Name:		   TC Testimonial
 * Plugin URI:		   https://www.themescode.com/items/tc-testimonial-pro/
 * Description:		   Testimonial Slider is an easy plugin to display testimonials of clients,business partners or affiliates along with title, URL on your website.
 * Version: 		   1.1
 * Author: 			   themesCode
 * Author URI: 		   https://www.themescode.com/items/tc-testimonial-pro
 * Text Domain:        TCODES
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * License: GPL2
 */

 /**
  * Protect direct access
  */

 if( ! defined( 'TCODES_TESTIMONIAL_HACK_MSG' ) ) define( 'TCODES_TESTIMONIAL_HACK_MSG', __( 'Sorry ! You made a mistake !', 'TCODES' ) );
 if ( ! defined( 'ABSPATH' ) ) die(TCODES_TESTIMONIAL_HACK_MSG);

 /**
  * Defining constants
 */

 if( ! defined( 'TCODES_TESTIMONIAL_PLUGIN_DIR' ) ) define( 'TCODES_TESTIMONIAL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
 if( ! defined( 'TCODES_TESTIMONIAL_PLUGIN_URI' ) ) define( 'TCODES_TESTIMONIAL_PLUGIN_URI', plugins_url( '', __FILE__ ) );

// require setting files
require_once TCODES_TESTIMONIAL_PLUGIN_DIR .'/lib/setting/class.settings-api.php';
require_once TCODES_TESTIMONIAL_PLUGIN_DIR .'/lib/setting/tc-testimonial-settings.php';
new themesCode_Settings_API_Test();

// require files

require_once TCODES_TESTIMONIAL_PLUGIN_DIR .'/lib/tc-testimonial-cpt.php';
require_once TCODES_TESTIMONIAL_PLUGIN_DIR .'/lib/tc-testimonial-metabox.php';
require_once TCODES_TESTIMONIAL_PLUGIN_DIR .'/lib/tc-testimonial-column.php';
require_once TCODES_TESTIMONIAL_PLUGIN_DIR .'/public/tc-testimonial-view.php';


 function tcodes_testimonial_enqueue_scripts() {
   // Vendors style & scripts
    wp_enqueue_style('owl.carousel', TCODES_TESTIMONIAL_PLUGIN_URI.'/vendors/owl-carousel/assets/owl.carousel.css');
    wp_enqueue_script('owl-carousel', TCODES_TESTIMONIAL_PLUGIN_URI.'/vendors/owl-carousel/owl.carousel.min.js', array('jquery'), 1.0, true);
    //Plugin Main CSS File
     wp_enqueue_style( 'tc-custom-style', TCODES_TESTIMONIAL_PLUGIN_URI.'/assets/css/tc-testimonial.css');
     wp_enqueue_style( 'font-awesome','//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  }

 add_action( 'wp_enqueue_scripts', 'tcodes_testimonial_enqueue_scripts' );

 function tcodes_testimonial_admin_style() {

  wp_enqueue_style( 'tcodes_testimonial_admin', TCODES_TESTIMONIAL_PLUGIN_URI.'/assets/css/tc-admin.css');

 }
 add_action( 'admin_enqueue_scripts', 'tcodes_testimonial_admin_style' );


 if ( function_exists( 'add_theme_support' ) ) {
     add_theme_support( 'post-thumbnails' );
 }

 // Sub Menu Page
 add_action('admin_menu', 'tcodes_testimonial_menu_init');
 function tcodes_testimonial_menu_help(){
   include('lib/tc-testimonial-help-upgrade.php');
 }

 function tcodes_testimonial_menu_init()
   {

     add_submenu_page('edit.php?post_type=tctestimonial', __('Help & Upgrade','TCODES'), __('Help & Upgrade','TCODES'), 'manage_options', 'tcodes_testimonial_menu_help', 'tcodes_testimonial_menu_help');

   }

 // end  Sub Menu Page
// adding link
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'tcodes_testimonial_plugin_action_links' );

function tcodes_testimonial_plugin_action_links( $links ) {
   // $links[] = '<a class="tc-pro-link" href="http://themescode.com/items/tc-testimonial-pro" target="_blank">Pro Version</a>';
   $links[] = '<a href="http://themescode.com/items/category/wordpress-plugins" target="_blank">TC Plugins</a>';
   return $links;
}

// After Plugin Activation redirect

 if( !function_exists( 'tct_activation_redirect' ) ){
   function tct_activation_redirect( $plugin ) {
       if( $plugin == plugin_basename( __FILE__ ) ) {
           exit( wp_redirect( admin_url( 'edit.php?post_type=tctestimonial&page=tct-settings' ) ) );
       }
   }
 }
 add_action( 'activated_plugin', 'tct_activation_redirect' );
