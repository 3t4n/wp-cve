<?php
/*
Plugin Name: JavaScript to Footer
Plugin URI: http://www.prelovac.com/vladimir/wordpress-plugins/footer-javascript
Description: This plugin automatically moves JavaScript code to page footer, speeding up page loading time.
Version: 0.4.1
Author: Vladimir Prelovac
Author URI: http://www.prelovac.com/vladimir
*/

// super easy way to move javascript to footer
remove_action('wp_head', 'wp_print_scripts');
remove_action('wp_head', 'wp_print_head_scripts', 9);
remove_action('wp_head', 'wp_enqueue_scripts', 1);
add_action('wp_footer', 'wp_print_scripts', 5);
add_action('wp_footer', 'wp_enqueue_scripts', 5);
add_action('wp_footer', 'wp_print_head_scripts', 5);

?>