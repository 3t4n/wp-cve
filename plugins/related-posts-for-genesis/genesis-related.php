<?php
   /*
   Plugin Name: Related Posts for Genesis
   Plugin URI: https://websiteguider.com/Related-posts-for-genesis.html
   Description: A plugin to create awesome related posts below content
   Version: 2.2
   Author: WebsiteGuider
   Author URI: https://websiteguider.com
   License: GPL2
   */

if( !defined('ABSPATH' )) {
	echo "Well done! Try Again";
	die();
}

register_activation_hook( __FILE__, 'grp_activation_check' );

  function grp_activation_check() {
      $latest = '2.0';
      $theme_info = wp_get_theme( 'genesis' );
  
      if ( 'genesis' != basename( TEMPLATEPATH ) ) {
          deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate plugin
          wp_die( sprintf( __( 'Sorry, you can\'t activate Related Posts for Genesis unless you have installed the Genesis Framework. Go back to the Plugins Page.', 'related-posts-for-genesis' ) ));
      }
  
      if ( version_compare( $theme_info['Version'], $latest, '<' ) ) {
          deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate plugin
          wp_die( sprintf( __( 'Sorry, you can\'t activate Related Posts for Genesis unless you have installed the Genesis. Go back to the Plugins Page.', 'related-posts-for-genesis' )) );
      }
  }
// Loads Language file
add_action('plugins_loaded', 'grp_load_textdomain');
function grp_load_textdomain() {
  load_plugin_textdomain( 'related-posts-for-genesis' );
}
// Includes main file
include_once dirname( __FILE__ ) . '/lib/related-posts.php';

// Includes Customizer Settings File
include_once dirname(__FILE__). '/admin/customizer.php';

// Includes Sanitize Callback file
include_once dirname(__FILE__). '/admin/sanitize-callbacks.php';

//Includes Plugin functions file
include_once dirname(__FILE__). '/lib/plugin-functions.php';

// Enqueues styles and scripts for frontend use only
function wg_plugin_name_scripts() {
    wp_enqueue_style('cl-chanimal-styles', plugin_dir_url( __FILE__ ) . '/lib/style.css' );
}
add_action( 'wp_enqueue_scripts', 'wg_plugin_name_scripts' );

// Enqueues styles and scripts for backend use only
function load_custom_wp_admin_style(){
    wp_enqueue_script( "customizer_js", plugin_dir_url( __FILE__ ) . '/admin/customizer.js' );
}
add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');
?>