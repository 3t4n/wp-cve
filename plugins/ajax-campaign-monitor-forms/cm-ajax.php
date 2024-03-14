<?php
/*
Plugin Name: Campaign Monitor Ajax Forms
Plugin URI: http://www.leewillis.co.uk/wordpress-plugins/?utm_source=wordpress&utm_medium=www&utm_campaign=ajax-campaign-monitor-forms
Description: Ajax shortcodes and widgets to allow visitors to sign up to Campaign Monitor (http://www.campaignmonitor.com) lists
Author: Lee Willis
Version: 1.5.0
Author URI: http://www.leewillis.co.uk/
License: GPLv3
Text Domain: cm_ajax
Domain Path: /languages
*/

if ( ! class_exists ( 'CS_REST_Subscribers' ) )
	require_once ( 'createsend-php/csrest_subscribers.php' );

if ( ! class_exists ( 'CS_REST_Lists' ) )
	require_once ( 'createsend-php/csrest_lists.php' );

require_once ( 'cm-ajax-widget.php' );
require_once ( 'cm-ajax-shortcode.php' );

function cm_ajax_init(){
	load_plugin_textdomain('cm_ajax', false, plugin_basename(dirname(__FILE__).'/languages'));
}
add_action('init', 'cm_ajax_init');
