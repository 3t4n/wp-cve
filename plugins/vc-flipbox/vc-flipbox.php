<?php
/*
Plugin Name: Flipbox Addon for WPBakery Page Builder
Plugin URI: http://codenpy.com/item/flipbox-addon-visual-composer/
Description: visual composer flipbox addon lets you add flipbox to your website with various options. 
Author: themebon
Author URI: http://codenpy.com/
License: GPLv2 or later
Text Domain: favc
Version: 1.1.8
*/

// Don't load directly
if (!defined('ABSPATH')){die('-1');}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'js_composer/js_composer.php' ) ){
    
/* Constants */
define( 'ASVC_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'ASVC_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
if ( ! function_exists( 'prime_WordPressCheckup' ) ) {
    function prime_WordPressCheckup( $version = '3.8' ) {
        global $wp_version;
        if ( version_compare( $wp_version, $version, '>=' ) ) {
            return "true";
        } else {
            return "false";
        }
    }
}

// Admin Style CSS
function asvc_admin_enqeue() {
    
    wp_enqueue_style( 'asvc_admin_css', plugins_url( 'admin/admin.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'asvc_admin_enqeue' );


//params
require_once 'admin/params/index.php';
require_once 'inc/helper.php';


require_once( 'shortcodes/flip-box/flip-box.php' );
require_once( 'shortcodes/flip-box-two/flip-box-two.php' );
require_once( 'shortcodes/flip-box-advanced/flip-box-advanced.php' );




    }
// Check If VC is activate
else {
    function asvc_required_plugin() {
        if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'js_composer/js_composer.php' ) ) {
            add_action( 'admin_notices', 'asvc_required_plugin_notice' );

            deactivate_plugins( plugin_basename( __FILE__ ) ); 

            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
        }

    }
add_action( 'admin_init', 'asvc_required_plugin' );

    function asvc_required_plugin_notice(){
        ?><div class="error"><p>Error! you need to install or activate the <a target="_blank" href="https://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=themebonwp">WPBakery Page Builder for WordPress (formerly Visual Composer)</a> plugin to run "<span style="font-weight: bold;">Flipbox Addon for WPBakery Page Builder</span>" plugin.</p></div><?php
    }
}
?>