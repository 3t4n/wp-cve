<?php
/*
Plugin Name: NS Smooth scroll back to top
Plugin URI: https://wordpress.org/plugins/ns-smooth-scroll-back-to-top/
Description: Add a back to top button on your theme when scroll site.
Version: 1.6.1
Author: NsThemes
Author URI: http://nsthemes.com
Text Domain: ns-smooth-scroll-back-to-top
Domain Path: /languages
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** 
 * @author        PluginEye
 * @copyright     Copyright (c) 2019, PluginEye.
 * @version         1.0.0
 * @license       https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 * PLUGINEYE SDK
*/

require_once('plugineye/plugineye-class.php');
$plugineye = array(
    'main_directory_name'       => 'ns-smooth-scroll-back-to-top',
    'main_file_name'            => 'ns-back-to-top-arrow.php',
    'redirect_after_confirm'    => 'admin.php?page=ns-btta-options-page',
    'plugin_id'                 => '166',
    'plugin_token'              => 'NWNmYTY5MjZkOGY4YzllYjJkNTQzYTBhMzYxNTJiMDA3N2RiMzk4ZTcxZDNlMGYwM2QzZWY5ZTBhNzM5ZjBkMjU3MDQyYTRjN2VmYjI=',
    'plugin_dir_url'            => plugin_dir_url(__FILE__),
    'plugin_dir_path'           => plugin_dir_path(__FILE__)
);

$plugineyeobj166 = new pluginEye($plugineye);
$plugineyeobj166->pluginEyeStart();      
         
        

if ( ! defined( 'WPBTTA_NS_PLUGIN_DIR' ) )
	define( 'WPBTTA_NS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'WPBTTA_NS_PLUGIN_DIR_URI' ) )
    define( 'WPBTTA_NS_PLUGIN_DIR_URI', plugin_dir_url( __FILE__ ) );

/* *** include css *** */
require_once( WPBTTA_NS_PLUGIN_DIR.'/ns-btta-css.php');

/* *** include js *** */
require_once( WPBTTA_NS_PLUGIN_DIR.'/ns-btta-js.php');

/* *** print button *** */
require_once( WPBTTA_NS_PLUGIN_DIR.'/ns-btta-print-button.php');


/* *** plugin options *** */
require_once( WPBTTA_NS_PLUGIN_DIR.'/ns-btta-options.php');



// function ns_btta_options_form()
// {
// 	require_once( WPBTTA_NS_PLUGIN_DIR.'/ns-btta-options-page-html.php');
// }


// function ns_btta_add_option_page() {
//     add_menu_page('Back To Top', 'Back To Top', 'manage_options', 'ns-btta-options-page', 'ns_btta_options_form', WPBTTA_NS_PLUGIN_DIR_URI.'/assets/img/backend-sidebar-icon.png', 60);
// }
 
// add_action('admin_menu', 'ns_btta_add_option_page');

require_once( plugin_dir_path( __FILE__ ).'ns-admin-options/ns-admin-options-setup.php');

/* *** include text domain *** */
function ns_woocommerce_back_to_top_arrow_load_plugin_textdomain() {
    load_plugin_textdomain( 'ns-smooth-scroll-back-to-top', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'ns_woocommerce_back_to_top_arrow_load_plugin_textdomain' );

/* *** plugin review trigger *** */
require_once( plugin_dir_path( __FILE__ ) .'/class/class-plugin-theme-review-request.php');

/* *** print dynamic css *** */
require_once( WPBTTA_NS_PLUGIN_DIR.'/ns-btta-dynamic-style.php');

/* *** print dynamic script *** */
require_once( WPBTTA_NS_PLUGIN_DIR.'/ns-btta-dynamic-script.php');


/* *** add link premium *** */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'nssmoothscrollbacktotop_add_action_links' );

function nssmoothscrollbacktotop_add_action_links ( $links ) {	
 $mylinks = array('<a id="nsssbttlinkpremium" href="https://www.nsthemes.com/product/back-to-top-btta-plugin/?ref-ns=2&campaign=SSBTT-linkpremium" target="_blank">'.__( 'Premium Version', 'ns-smooth-scroll-back-to-top' ).'</a>');
return array_merge( $links, $mylinks );
}




// ajax function, count hit in back to top
add_action( 'wp_ajax_ns_btta_ajax_hit', 'ns_btta_ajax_hit' );
add_action( 'wp_ajax_nopriv_ns_btta_ajax_hit', 'ns_btta_ajax_hit' );
function ns_btta_ajax_hit(){
    //do stuff
    check_ajax_referer( 'ns-btta-ajax-nonce-click', 'ns_btta_security' );

    if ( !is_user_logged_in() ) 
    {    
        $old_value_for_rating = get_option( 'btta_total_open_count', 0 );
        update_option( 'btta_total_open_count', $old_value_for_rating+1 );
    }    
	die();
}
?>