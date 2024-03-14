<?php
/*
Plugin Name: Fuse Social Floating Sidebar
Plugin URI: https://www.fusefloat.com/
Description: This Fuse Social Floating Sidebar plugin allow you to put social icons which can be link with your social media profiles.
Version: 5.4.10
Author: Daniyal Ahmed
Author URI: https://www.fusefloat.com/
License: GNU General Public License v3.0
License URI: http://www.opensource.org/licenses/gpl-license.php
NOTE: This plugin is released under the GPLv2 license. The icons used in this plugin are the property
of their respective owners, and do not, necessarily, inherit the GPLv2 license.
*/
/**
 * 
 * Defining Version Number
 * 
 * */
define('FUSE_VERSION', '5.4.10');
define('FUSE_URL', dirname( __FILE__ ));

/**
 * 
 * Including Redux
 * 
 * */ 
require_once 'inc/extensions/loader.php';
if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/framework/redux-framework.php' ) ) {
    require_once dirname( __FILE__ ) . '/framework/redux-framework.php';
}
// Create a helper function for easy SDK access.

if ( !function_exists( 'fs_fs' ) ) {
    // Create a helper function for easy SDK access.
    function fs_fs()
    {
        global  $fs_fs ;
        
        if ( !isset( $fs_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $fs_fs = fs_dynamic_init( array(
                'id'             => '2701',
                'slug'           => 'fuse-social-floating-sidebar',
                'type'           => 'plugin',
                'public_key'     => 'pk_70ed0c631ac1720148be7f62dca7e',
                'is_premium'     => false,
                'premium_suffix' => 'FUSE PRO',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'    => 'FUSESoicalFloatingSidebar',
                'support' => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $fs_fs;
    }
    
    // Init Freemius.
    fs_fs();
    // Signal that SDK was initiated.
    do_action( 'fs_fs_loaded' );
}

if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/framework/settings/fuse-config.php' ) ) {
    require_once dirname( __FILE__ ) . '/framework/settings/fuse-config.php';
}
// Creating Icons
require_once 'inc/fuse_social_sidebar_func.php';
// Getting Style for awesome icons
require_once 'inc/fuse_social_sidebar_scripts.php';
// Add settings link on plugin page
function fuse_social_dashboard_icons()
{
    wp_register_style( 'fuse-social-dash', plugin_dir_url( __FILE__ ) . 'inc/css/dashicon.css' );
    wp_enqueue_style( 'fuse-social-dash' );
}

// This example assumes the opt_name is set to redux_demo.  Please replace it with your opt_name value.
add_action( 'admin_enqueue_scripts', 'fuse_social_dashboard_icons' );
// Admin Script
function fuse_social_admin_styles()
{
    if ( !empty($_GET['page']) ) {
        if ( $_GET['page'] == "FUSESoicalFloatingSidebar" ) {
            wp_enqueue_style( 'fuse-styles', plugin_dir_url( __FILE__ ) . 'inc/css/admin.css', array(), rand() );
            wp_enqueue_script( 'fuse-admin-script', plugin_dir_url( __FILE__ ) . 'inc/js/admin-fuse.js', array(), rand() );
        }
    }
}

add_action( 'admin_enqueue_scripts', 'fuse_social_admin_styles' );

function fuse_social_settings_link( $links )
{
    $settings_link = '<a href="options-general.php?page=FUSESoicalFloatingSidebar">Settings</a>';
    array_unshift( $links, $settings_link );
    return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_{$plugin}", 'fuse_social_settings_link' );
add_action( 'wp_footer', 'fuse_social_sidebar', 100 );
function fuse_social_sidebar()
{
    $makeawesome_icons = new Making_Fuse_Icons();
    // Getting Icons for Shortcode
    $makeawesome_icons->Create_Awesome_Icons();
}
