<?php 
/*
 * Plugin Name: View Count by Webline
 * URI: http://www.weblineindia.com
 * Description: This plugin allows user to see how many times a  given page is viewed on each page load. Additional it provides  other features like logged in user view count, reports for admin to do detail analysis
 * Author: Weblineindia
 * Author URL: http://www.weblineindia.com
 * Version: 1.0.4
 * Network: false
 */


define ( 'VC_VERSION',      '1.0.4' );
define ( 'VC_DEBUG',        TRUE );
define ( 'VC_PATH',         plugin_dir_path( __FILE__ ) );
define ( 'VC_URL',          plugins_url( '', __FILE__ ) );
define ( 'VC_PLUGIN_FILE',  basename( __FILE__ ) );
define ( 'VC_PLUGIN_DIR',   plugin_basename( dirname( __FILE__ ) ) );
define ( 'VC_OPTION_NAME',  'page_view_option' );


define ( 'VC_ADMIN_DIR',    VC_PATH.'/admin' );

define ( 'VC_CLASS_DIR',    'class' );

define ( 'VC_CLASS',        VC_PATH.VC_CLASS_DIR );

define ( 'VC_TABLENAME',    'pageview_history' );

// Adding Hook Class
require_once( VC_CLASS . '/hook.php' );


if(is_admin()) {
    // Admin Menu
    require_once (VC_ADMIN_DIR.'/class/assets.php');
    require_once (VC_ADMIN_DIR.'/class/page-view.php');
    require_once (VC_ADMIN_DIR.'/class/menu.php');

    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'pvcbw_add_action_links');
    // Function for add action link
    function pvcbw_add_action_links($links_array)
    {
        array_unshift($links_array, '<a href="' . admin_url('admin.php?page=page-views') . '">Dashboard</a>');
        return $links_array;
    }
} else {
    // Adding Hook Class
    require_once( VC_CLASS . '/generate-view.php' );
    require_once( VC_CLASS . '/show-view.php' );
}