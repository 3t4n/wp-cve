<?php
/*
* Plugin Name: WP Bing Map Pro
* Plugin URI: https://tuskcode.com
* Version: 5.0
* Author: dan009
* Description: Simple, and easy to use, unlimited maps, pins, and infoboxes for every page on your website
* Text Domain: bing-map-pro
* License: GPLv3
*/

if( ! defined('ABSPATH') ) die('No Access to this page');

$BingMapPro_MinimalRequiredPhpVersion = '5.2';
$BMP_PLUGIN_VERSION = '5.0';
define( 'BMP_PLUGIN_URL', plugins_url( '', __FILE__ ) );

/* Check the php version, and display a message if the running version is lower than the required on */
function BingMapPro_noticePhpVersionWrong(){
    global $BingMapPro_MinimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
        esc_html__( 'Error: plugin "Bing Map Pro" requires a higher version of PHP to be running.', 'bing-map-pro').
        '<br/>' . esc_html__('Minimal version of PHP required: ', 'bing-map-pro') . '<strong>' . $BingMapPro_MinimalRequiredPhpVersion . '</strong>'.
        '<br/>' . esc_html__('Your server\'s PHP version: ', 'bing-map-pro') . '<strong>' . phpversion() . '</strong>';
}

function BingMapPro_PhpVersionCheck(){
    global $BingMapPro_MinimalRequiredPhpVersion;
    if( version_compare(phpversion(), $BingMapPro_MinimalRequiredPhpVersion ) < 0 ){
        add_action('admin_notices', 'BingMapPro_noticePhpVersionWrong');
        return false;
    }
    return true;
}

/* Initialize internationalization */
function BingMapPro_i18n_init(){
    $pluginDir = dirname( plugin_basename(__FILE__) );
    load_plugin_textdomain('bing-map-pro', false, $pluginDir . '/languages/');
}

add_action( 'plugins_loaded', 'BingMapPro_i18n_init');

if( BingMapPro_PhpVersionCheck() ){
    include_once( 'wp-bing-map-pro_init.php');  
    BingMapPro_Plugin_init::BingMapPro_init( __FILE__ );
}