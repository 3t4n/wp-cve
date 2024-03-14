<?php
/**
	Plugin Name: Scissors and Watermark
	Plugin URI: http://wordpress.org/extend/plugins/scissors-watermark/
	Description: Scissors and Watermark enhances WordPress' handling of images by introducing cropping, resizing, rotating, and watermarking functionality. Works from WordPress v3.5 and up.
	Version: 3.2.1
	Requires at least: 3.5
	Tested up to: 3.5
	Author: Apollo139
	Author URI: http://profiles.wordpress.org/aloziak/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
	Original Plugin Name: Scissors Continue
	Original Plugin URI: http://dev.huiz.net
	Description: Scissors Continued enhances WordPress' handling of images by introducing cropping, resizing, rotating, and watermarking functionality. Works from WordPress v2.9 and up.
	Original Version: 2.1
	Original Author: A. Huizinga
	Original Author URI: http://www.huiz.net/
	
	Copyright (C) 2011  A. Huizinga <anton@huiz.net>
	Copyright (C) 2012  Apollo1 <info@apollo1.cz>
	
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

global $wp_version;

if( version_compare( $wp_version, '3.5', '>=') ) {
		
	include 'functions.php';
	
	add_action('admin_init', 'scissors_autosize_add_settings');
	register_activation_hook(__FILE__, 'scissors_autosize_activation'); 
	
	add_action('admin_notices', 'scissors_activation');
	register_activation_hook(__FILE__, 'scissors_activation');
	add_action('admin_init', 'scissors_activation_notice_ignore');
	
	add_action('admin_init', 'scissors_cropping_add_settings');
	register_activation_hook(__FILE__, 'scissors_cropping_activation');
	
	add_filter('wp_generate_attachment_metadata', 'scissors_apply_initial_watermarks');
	add_filter('wp_delete_file', 'scissors_delete_watermark_meta');
	
	add_action('admin_print_scripts', 'scissors_admin_head_watermark'); 
	add_action('admin_init', 'scissors_watermarking_add_settings');
	register_activation_hook(__FILE__, 'scissors_watermarking_activation'); 
	
	$plugin = plugin_basename(__FILE__);
	add_filter("plugin_action_links_$plugin", 'scissors_plugin_settings_link', 10, 2 );
	
	//add_action('post-upload-ui', 'scissors_post_upload_ui');
	//add_action('post-html-upload-ui', 'scissors_post_upload_ui');
	
	add_action('add_meta_boxes', 'add_scissors_metabox', 99, 2 );
	add_action('admin_print_scripts', 'scissors_admin_head');
	add_action('admin_print_styles', 'scissors_styles');
	add_action('wp_ajax_scissorsCrop', 'scissors_action');
	add_action('wp_ajax_scissorsResize', 'scissors_action');
	add_action('wp_ajax_scissorsRotate', 'scissors_action');
	add_action('wp_ajax_scissorsWatermark', 'scissors_action');
	
	$ScissorsInstance = new Scissors();
	
} else {
	
	// Sorry, WordPress 2.8 and lower are not supported!
	// You need Scissors v1.3.7 or you could upgrade WordPress.
	// Anton, February 2011
	
	// Sorry, WordPress 3.4.2 and lower are not supported!
	// You need Scissors and Watermark v3.0 for WordPress up to 3.4.2 or you could upgrade WordPress.
	// Apollo139, December 2012

}

?>