<?php
/*
Plugin Name:  Subscribe button Widget
Plugin URI:   https://wordpress.org/plugins/subscribe-button-widget/
Description:  Youtube Subscribe button display.
Version:      1.0.17
Author:       Apsara Aruna
Author URI:   https://profiles.wordpress.org/apsaraaruna
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  youtube-sw
 */

if (!defined('ABSPATH')) {
	exit();
}

require_once plugin_dir_path(__FILE__) . 'includes/youtubesubs-scripts.php';

require_once plugin_dir_path(__FILE__) . 'includes/youtubesubs-class.php';

function register_youtubesubs() {
	register_widget('YouTube_Subs_Widget');
}

add_action('widgets_init', 'register_youtubesubs');
