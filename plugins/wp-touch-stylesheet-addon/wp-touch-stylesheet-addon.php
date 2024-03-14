<?php
/*
Plugin Name: Custom Stylesheet Extension for WPtouch
Plugin URI: http://wordpress.org/plugins/wp-touch-stylesheet-addon/
Description: Adds a custom mobile only stylesheet for WPtouch! 
Version: 1.0.8
Author: Miles Stewart
Author URI: http://www.milesstewart.co.uk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// require class
require_once(plugin_dir_path( __FILE__ ) . "classes/ExtraStylesheetAddon.php");

// Instantiate Class
$wp_touch_addon = new ExtraStylesheetAddon(); 

// Add pages on plugin activation
register_activation_hook(  __FILE__ , array( &$wp_touch_addon, 'create_stylesheet_on_activation' ));

// Enqueue the css file depending on if the mobile theme is loaded or not
add_action('wp_enqueue_scripts', array( &$wp_touch_addon, 'mobile_extra_stylesheet' ));


?>