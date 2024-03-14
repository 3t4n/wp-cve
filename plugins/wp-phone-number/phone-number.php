<?php
/**
 * Plugin Name: WP Phone Number
 * Plugin URI: https://wordpress.org/plugins/wp-phone-number/
 * Description: Makes sure telephone numbers are clickable on mobile devices.
 * 
 * Version: 1.1.1
 * Author: Sergey Fritzler
 * Author URI: https://profiles.wordpress.org/sergey-fritzler/
 *
 * This plugin is based on the PHP Mobile Detect class (https://github.com/serbanghita/Mobile-Detect/).
 */



// Shortcode register
add_shortcode('phone', 'shortcode_phone_number_link_creator');

// Shortcode in widgets
add_filter('widget_text', 'do_shortcode');



function shortcode_phone_number_link_creator($atts, $content) {
	if (!empty($content)) {
		require_once(dirname(__FILE__).'/include/Mobile_Detect.php');
		
		$detect = new Mobile_Detect;
		
		if (!empty($atts['number']) && $detect->isMobile()) {
			return '<a href="tel:'.$atts['number'].'">'.$content.'</a>';
		} 
		else {
			return $content;
		}
	}
	
	return "";
}