<?php
/*
Plugin Name: WPS Visitor Counter Plugin 
Plugin URI: https://techmix.xyz/downloads/wps-visitor-counter-plugin-for-wordpress/
Description: WPS Visitor Counter plugin will display your websites traffic statistics at front end. This Plugin support Widget, Shortcode and Gutenberg Block.
Version: 1.4.8
Text Domain: wps-visitor-counter
Domain Path: /languages
Author: TechMix
Author URI: https://techmix.xyz/
*/

if ( ! function_exists( 'wps_getRealIpAddr' ) ) {
	function wps_getRealIpAddr() {
		foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key){
			if(array_key_exists($key, $_SERVER) === true){
				foreach (explode(',', $_SERVER[$key]) as $ip){
					$ip = trim($ip); // just to be safe
					if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
						return $ip;
					}
				}
			}
		}
	}
}

global $wpdb;
define('WPS_VC_TABLE_NAME', $wpdb->prefix . 'wps_statistic');
define('WPS_VC_OPTIONS_TABLE_NAME', $wpdb->prefix . 'wps_st_options');
require_once( ABSPATH . 'wp-includes/pluggable.php' );
require_once (dirname ( __FILE__ ) . '/wps_wp_query.php');
require_once (dirname ( __FILE__ ) . '/wps-visitor-counter-count.php');
require_once (dirname ( __FILE__ ) . '/wps_visitor_counter_views.php');
require_once (dirname ( __FILE__ ) . '/wps-visitor-counter-widgets.php');



function wps_visitor_counter_option() {
	require_once (dirname ( __FILE__ ) . '/wps-visitor-counter-options-general.php');
}

function wps_visitor_counter_widgets_init() {
register_widget('wps_visitor_counter');
}

function wps_visitor_counter_admin_menu() {
	add_options_page('Plugin Stats WPS', 'WPS Visitor Counter', "manage_options", 'wps_options_general', 'wps_visitor_counter_option');
}


function wps_visitor_counter_deactivation_hook(){
	// global $wpdb;
	// $sql = "DROP TABLE `". WPS_VC_TABLE_NAME . "`;";
	// $wpdb->query($sql);
}

function wps_visitor_counter_styles($path, $exclude = ".|..|.svn|.DS_Store", $recursive = true) {
    $path = rtrim($path, "/") . "/";
    $folder_handle = opendir($path) or die("Eof");
    $exclude_array = explode("|", $exclude);
    $result = array();
    while(false !== ($filename = readdir($folder_handle))) {
        if(!in_array(strtolower($filename), $exclude_array)) {
            if(is_dir($path . $filename . "")) {
                if($recursive) $result[] = wps_visitor_counter_styles($path . $filename . "", $exclude, true);
            } else {
                if ($filename === '0.gif') {
                    if (!isset($done[$path])) {
                        $result[] = $path;
                        $done[$path] = 1;
                    }
                }
            }
        }
    }
    return $result;
}

register_activation_hook(__FILE__, 'wps_visitor_counter_activation_hook');
register_deactivation_hook(__FILE__, 'wps_visitor_counter_deactivation_hook');
add_action('widgets_init', 'wps_visitor_counter_widgets_init');
add_action('admin_menu', 'wps_visitor_counter_admin_menu');
add_action('plugins_loaded', function() {
      load_plugin_textdomain( 'wps-visitor-counter', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    });





//add shortcode

function wps_visitor_init() {
        add_shortcode( 'wps_visitor_counter', 'wps_add_visitor_counter' );
        /*register_block_type( 'wps/wps-visitor-counter', array(
        		'editor_script' => 'wps-visitor-block',
                'render_callback' => 'wps_add_visitor_counter',
        ) );*/


         if ( !function_exists( 'register_block_type' ) ) {

		return;

	}



    wp_register_script(

        'wps-visitor-gutenberg-editor_scripts',

        plugin_dir_url(__FILE__) . 'wps-gutenberg-block.js',

        array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' )

    );

    register_block_type( 'wps/shortcode-script', array(

        'editor_script' => 'wps-visitor-gutenberg-editor_scripts',

    ));
    register_block_type( 'wps/shortcodeblock', array(

    'render_callback' => 'wps_add_visitor_counter'

    ));

    
}

add_action( 'init', 'wps_visitor_init' );
