<?php
/**
 * Plugin Name: Allow Shortcode in Text Widgets
 * Plugin URI: http://webworksofkc.com/allow-shortcode-in-text-widgets-wordpress-plugin-by-travis-pflanz
 * Description: Allow shortcode to be used in text widgets. There are no settings for this plugin. Simply activate, then use whatever shortcode you like within a text widget.
 * Version: 1.0
 * Author: Travis Pflanz
 * Author URI: http://Travis.Pflanz.ME
 * License: GPL2
 */
 
add_filter('widget_text', 'do_shortcode');
?>
